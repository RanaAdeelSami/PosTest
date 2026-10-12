@extends('layouts.admin')

@section('content')
<div style="display:flex;flex-direction:column;height:100%;gap:16px;">

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;flex-shrink:0;">
        <div>
            <h1 style="font-size:24px;font-weight:700;color:#1a1a1a;margin-bottom:4px;">Cash Shifts</h1>
            <p style="color:#6b7280;font-size:14px;">Manage cashier shifts</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('admin.shifts.index') }}" title="Refresh" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;font-size:16px;">🔄</a>
            <a href="{{ route('admin.shifts.create') }}" style="display:flex;align-items:center;gap:8px;padding:11px 20px;background:#16a34a;color:white;border-radius:10px;font-size:13.5px;font-weight:600;text-decoration:none;box-shadow:0 2px 6px rgba(22,163,74,0.3);">🟢 Open Shift</a>
        </div>
    </div>

    <x-data-table
        :items="$shifts"
        :columns="[
            ['label' => 'Shift #', 'render' => fn($s) => '<span style=\'font-family:monospace;font-size:12.5px;color:#1e40af;font-weight:600;\'>'.e($s->shift_number).'</span>'],
            ['label' => 'Cashier', 'render' => fn($s) => '<span style=\'font-weight:600;color:#1a1a1a;\'>'.e($s->user->name ?? '—').'</span>'],
            ['label' => 'Branch', 'render' => fn($s) => '<span style=\'color:#6b7280;\'>'.e($s->branch->name ?? '—').'</span>'],
            ['label' => 'Opened', 'render' => fn($s) => '<div style=\'color:#374151;font-weight:500;\'>'.e($s->opened_at->format('d M Y')).'</div><div style=\'font-size:11.5px;color:#9ca3af;\'>'.e($s->opened_at->format('H:i A')).'</div>'],
            ['label' => 'Sales', 'align' => 'right', 'render' => fn($s) => '<span style=\'font-weight:700;color:#1a1a1a;\'>'.number_format($s->total_sales, 2).'</span>'],
            ['label' => 'Difference', 'align' => 'right', 'render' => function($s) {
                if ($s->status !== 'closed') return '<span style=\'color:#d1d5db;\'>—</span>';
                $color = $s->difference > 0 ? '#16a34a' : ($s->difference < 0 ? '#dc2626' : '#6b7280');
                return '<span style=\'font-weight:700;color:'.$color.';\'>'.number_format($s->difference, 2).'</span>';
            }],
            ['label' => 'Status', 'align' => 'center', 'render' => fn($s) => $s->status === 'open'
                ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:700;\'>🟢 OPEN</span>'
                : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>⚪ CLOSED</span>'
            ],
            ['label' => '', 'align' => 'right', 'render' => fn($s) => '<a href=\''.route('admin.shifts.show', $s).'\' title=\'View\' style=\'display:inline-flex;width:34px;height:34px;background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;align-items:center;justify-content:center;text-decoration:none;font-size:14px;\'>👁️</a>'],
        ]"
        empty-icon="⏰"
        empty-message="No shifts found"
        :empty-action-url="route('admin.shifts.create')"
        empty-action-label="+ Open Shift"
    />
</div>
@endsection