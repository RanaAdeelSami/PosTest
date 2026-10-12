<?php

namespace App\Http\Controllers\Admin;

use App\Exports\GenericExport;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Supplier;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    // ================= SALES EXPORT =================
    public function sales(Request $request)
{
    $this->authorizeExport('export.sales');

    $user = auth()->user();
    $query = Sale::with(['branch', 'warehouse', 'user']);   // ← warehouse

    if (!$user->hasRole('super_admin') && $user->company_id) {
        $query->where('company_id', $user->company_id);
    }

    // Filters
    if ($request->filled('search')) {
        $s = $request->search;
        $query->where(function ($q) use ($s) {
            $q->where('invoice_number', 'like', "%$s%")
              ->orWhere('customer_name', 'like', "%$s%")
              ->orWhere('customer_phone', 'like', "%$s%");
        });
    }
    if ($request->filled('from_date')) {
        $query->whereDate('created_at', '>=', $request->from_date);
    }
    if ($request->filled('to_date')) {
        $query->whereDate('created_at', '<=', $request->to_date);
    }
    if ($request->filled('branch_id')) {
        $query->where('branch_id', $request->branch_id);
    }
    if ($request->filled('warehouse_id')) {                  // ← warehouse filter
        $query->where('warehouse_id', $request->warehouse_id);
    }
    if ($request->filled('payment_method')) {
        $query->where('payment_method', $request->payment_method);
    }

    $sales = $query->latest()->get();

    $data = $sales->map(function ($sale) {
        return [
            'Invoice' => $sale->invoice_number,
            'Date' => $sale->created_at->format('Y-m-d H:i'),
            'Customer' => $sale->customer_name ?? 'Walk-in',
            'Phone' => $sale->customer_phone ?? '',
            'Branch' => $sale->branch->name ?? '-',
            'Warehouse' => $sale->warehouse->name ?? '-',      // ← warehouse column
            'Cashier' => $sale->user->name ?? '-',
            'Subtotal' => number_format($sale->subtotal, 2),
            'Discount' => number_format($sale->discount, 2),
            'Tax' => number_format($sale->tax, 2),
            'Total' => number_format($sale->total, 2),
            'Payment' => ucfirst($sale->payment_method),
            'Type' => ucfirst($sale->type ?? 'sale'),
            'Status' => ucfirst($sale->status),
        ];
    });

    return $this->export($data, 'Sales Report', 'sales', $request->format ?? 'xlsx');
}

    // ================= PURCHASES EXPORT =================
    public function purchases(Request $request)
    {
        $this->authorizeExport('export.purchases');

        $user = auth()->user();
        $query = Purchase::with(['supplier', 'branch', 'user']);

        // Company scope
        if (!$user->hasRole('super_admin') && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('purchase_number', 'like', "%$s%")
                  ->orWhere('supplier_invoice_no', 'like', "%$s%");
            });
        }
        if ($request->filled('from_date')) {
            $query->whereDate('purchase_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('purchase_date', '<=', $request->to_date);
        }
        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $purchases = $query->latest()->get();

        $data = $purchases->map(function ($p) {
            return [
                'Purchase #' => $p->purchase_number,
                'Date' => $p->purchase_date->format('Y-m-d'),
                'Supplier' => $p->supplier->name ?? '-',
                'Supplier Inv #' => $p->supplier_invoice_no ?? '',
                'Branch' => $p->branch->name ?? '-',
                'Total' => number_format($p->total, 2),
                'Paid' => number_format($p->paid_amount, 2),
                'Due' => number_format($p->due_amount, 2),
                'Payment' => ucfirst($p->payment_method),
                'Status' => ucfirst($p->status),
            ];
        });

        return $this->export($data, 'Purchases Report', 'purchases', $request->format ?? 'xlsx');
    }

    // ================= INVENTORY EXPORT =================
    public function inventory(Request $request)
    {
        $this->authorizeExport('export.inventory');

        $user = auth()->user();
        $query = Product::with('category');

        // Company scope
        if (!$user->hasRole('super_admin') && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%$s%")->orWhere('sku', 'like', "%$s%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'out') {
                $query->where('stock', '<=', 0);
            } elseif ($request->stock_status === 'low') {
                $query->whereBetween('stock', [1, 10]);
            } elseif ($request->stock_status === 'ok') {
                $query->where('stock', '>', 10);
            }
        }

        $products = $query->orderBy('name')->get();

        $data = $products->map(function ($p) {
            return [
                'Name' => $p->name,
                'SKU' => $p->sku ?? '',
                'Category' => $p->category->name ?? '-',
                'Cost Price' => number_format($p->cost_price, 2),
                'Sale Price' => number_format($p->price, 2),
                'Stock' => $p->stock,
                'Stock Value' => number_format($p->stock * $p->price, 2),
                'Status' => $p->stock <= 0 ? 'Out of Stock' : ($p->stock <= 10 ? 'Low' : 'OK'),
            ];
        });

        return $this->export($data, 'Inventory Report', 'inventory', $request->format ?? 'xlsx');
    }

    // ================= PROFIT EXPORT =================
    public function profit(Request $request)
    {
        $this->authorizeExport('export.profit');

        $user = auth()->user();
        $query = Sale::with(['items', 'branch']);

        // Company scope
        if (!$user->hasRole('super_admin') && $user->company_id) {
            $query->where('company_id', $user->company_id);
        }

        // Filters
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('invoice_number', 'like', "%$s%")
                  ->orWhere('customer_name', 'like', "%$s%");
            });
        }
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $sales = $query->latest()->get();

        $data = $sales->map(function ($sale) {
            $cost = 0;
            $profit = 0;

            foreach ($sale->items as $item) {
                $qty = $item->is_return ? -$item->quantity : $item->quantity;
                $cost += $item->cost_price * $qty;
                $profit += ($item->unit_price - $item->cost_price) * $qty;
            }

            return [
                'Invoice' => $sale->invoice_number,
                'Date' => $sale->created_at->format('Y-m-d'),
                'Customer' => $sale->customer_name ?? 'Walk-in',
                'Branch' => $sale->branch->name ?? '-',
                'Sale' => number_format($sale->total, 2),
                'Cost' => number_format($cost, 2),
                'Profit' => number_format($profit, 2),
                'Margin %' => $sale->total > 0 ? number_format($profit / $sale->total * 100, 1) : '0.0',
            ];
        });

        return $this->export($data, 'Profit Report', 'profit_report', $request->format ?? 'xlsx');
    }

    // ================= HELPERS =================
    private function authorizeExport(string $permission): void
    {
        if (!auth()->user()->can($permission)) {
            abort(403, "Aap ko is export ki permission nahi hai. Owner se permission lein.");
        }
    }

    private function export($data, string $title, string $filename, string $format)
    {
        $filename .= '_' . date('Ymd_His');

        if ($format === 'pdf') {
            $pdf = Pdf::loadView('exports.generic', [
                'data' => $data,
                'title' => $title,
            ])->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        // Excel export — single GenericExport class
        return Excel::download(new GenericExport($data), $filename . '.xlsx');
    }
}