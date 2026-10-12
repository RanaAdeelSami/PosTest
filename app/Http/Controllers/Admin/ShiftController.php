<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Sale;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $query = Shift::with(['branch', 'user'])->latest();

        // Salesman: only their own shifts
        if ($user->hasRole('salesman')) {
            $query->where('user_id', $user->id);
        }

$perPage = (int) request()->get('per_page', 10);
if (!in_array($perPage, [10, 25, 50, 100])) $perPage = 10;
$shifts = $query->latest()->paginate($perPage);
        return view('admin.shifts.index', compact('shifts'));
    }

    public function create()
    {
        $user = auth()->user();

        // Check if user already has open shift
        $openShift = Shift::where('user_id', $user->id)
            ->where('status', 'open')
            ->first();

        if ($openShift) {
            return redirect()->route('admin.shifts.show', $openShift)
                ->with('error', 'Aap ka already ek shift open hai. Pehle usay close karein.');
        }

        $branches = $user->hasRole('super_admin')
            ? Branch::pluck('name', 'id')
            : Branch::where('company_id', $user->company_id)->pluck('name', 'id');

        return view('admin.shifts.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'opening_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $user = auth()->user();

        // Double-check: no open shift
        $existing = Shift::where('user_id', $user->id)
            ->where('status', 'open')
            ->exists();

        if ($existing) {
            return back()->with('error', 'Aap ka already ek shift open hai.');
        }

        $shift = Shift::create([
            'company_id' => $user->company_id,
            'branch_id' => $validated['branch_id'],
            'user_id' => $user->id,
            'shift_number' => $this->generateShiftNumber(),
            'opening_cash' => $validated['opening_cash'],
            'expected_cash' => $validated['opening_cash'],
            'opened_at' => now(),
            'status' => 'open',
            'notes' => $validated['notes'] ?? null,
        ]);

        return redirect()->route('admin.shifts.show', $shift)
            ->with('success', 'Shift opened successfully. Opening cash: ' . number_format($validated['opening_cash'], 2));
    }

    public function show(Shift $shift)
    {
        $shift->load(['branch', 'user', 'sales' => function ($q) {
            $q->latest()->limit(20);
        }]);

        // Recalculate stats
        $this->recalculate($shift);
        $shift->refresh();

        return view('admin.shifts.show', compact('shift'));
    }

    public function close(Request $request, Shift $shift)
    {
        if ($shift->status === 'closed') {
            return back()->with('error', 'Ye shift already closed hai.');
        }

        $validated = $request->validate([
            'closing_cash' => 'required|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Recalculate before closing
        $this->recalculate($shift);
        $shift->refresh();

        $expected = $shift->opening_cash + $shift->cash_sales;
        $difference = $validated['closing_cash'] - $expected;

        $shift->update([
            'closing_cash' => $validated['closing_cash'],
            'expected_cash' => $expected,
            'difference' => $difference,
            'closed_at' => now(),
            'status' => 'closed',
            'notes' => $validated['notes'] ?? $shift->notes,
        ]);

        $msg = 'Shift closed. ';
        if ($difference > 0) {
            $msg .= 'EXCESS: ' . number_format($difference, 2);
        } elseif ($difference < 0) {
            $msg .= 'SHORT: ' . number_format(abs($difference), 2);
        } else {
            $msg .= 'Perfectly balanced!';
        }

        return redirect()->route('admin.shifts.show', $shift)
            ->with('success', $msg);
    }

    /**
     * Recalculate shift stats from its sales.
     */
    private function recalculate(Shift $shift): void
    {
        $sales = Sale::where('shift_id', $shift->id)
            ->where('status', '!=', 'refunded')
            ->get();

        $totalSales = 0;
        $cashSales = 0;
        $cardSales = 0;
        $creditSales = 0;

        foreach ($sales as $sale) {
            $totalSales += $sale->total;

            if ($sale->payment_method === 'cash') {
                $cashSales += $sale->total;
            } elseif ($sale->payment_method === 'card') {
                $cardSales += $sale->total;
            } else {
                $creditSales += $sale->total;
            }
        }

        $shift->update([
            'total_sales' => $totalSales,
            'cash_sales' => $cashSales,
            'card_sales' => $cardSales,
            'credit_sales' => $creditSales,
            'expected_cash' => $shift->opening_cash + $cashSales,
        ]);
    }

    private function generateShiftNumber(): string
    {
        $prefix = 'SHF-' . date('Ymd') . '-';
        $last = Shift::where('shift_number', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();

        $number = $last ? ((int) substr($last->shift_number, -4)) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}