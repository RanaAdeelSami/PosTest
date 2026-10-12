@extends('layouts.admin')

@section('content')
<div style="display:flex;flex-direction:column;height:100%;gap:16px;">

    <div style="display:flex;justify-content:space-between;align-items:flex-start;flex-wrap:wrap;gap:12px;flex-shrink:0;">
        <div>
            <h1 style="font-size:24px;font-weight:700;color:#1a1a1a;margin-bottom:4px;">Stock Adjustments</h1>
            <p style="color:#6b7280;font-size:14px;">Manual stock changes</p>
        </div>
        <div style="display:flex;gap:8px;">
            <a href="{{ route('admin.stock-adjustments.index') }}" title="Refresh" style="display:flex;align-items:center;justify-content:center;width:42px;height:42px;background:#f3f4f6;color:#6b7280;border:1px solid #e5e7eb;border-radius:10px;text-decoration:none;font-size:16px;">🔄</a>
            <button type="button" onclick="openModal()" style="display:flex;align-items:center;gap:8px;padding:11px 20px;background:#ff8a3d;color:white;border-radius:10px;font-size:13.5px;font-weight:600;border:none;cursor:pointer;font-family:inherit;box-shadow:0 2px 6px rgba(255,138,61,0.3);">➕ New Adjustment</button>
        </div>
    </div>

    <x-data-table
        :items="$adjustments"
        :columns="[
            ['label' => 'Date', 'render' => fn($a) => '<span style=\'color:#374151;font-weight:500;\'>'.e($a->created_at->format('d M Y')).'</span><div style=\'font-size:11.5px;color:#9ca3af;\'>'.e($a->created_at->format('H:i A')).'</div>'],
            ['label' => 'Product', 'render' => fn($a) => '<span style=\'font-weight:600;color:#1a1a1a;\'>'.e($a->product->name ?? '—').'</span>'],
            ['label' => 'Type', 'align' => 'center', 'render' => fn($a) => $a->type === 'add'
                ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:700;\'>+ ADD</span>'
                : '<span style=\'background:#fee2e2;color:#991b1b;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:700;\'>- REMOVE</span>'
            ],
            ['label' => 'Qty', 'align' => 'right', 'render' => fn($a) => '<span style=\'font-weight:700;color:'.($a->type === 'add' ? '#16a34a' : '#dc2626').';font-size:14px;\'>'.($a->type === 'add' ? '+' : '-').$a->quantity.'</span>'],
            ['label' => 'Reason', 'render' => fn($a) => '<span style=\'color:#6b7280;\'>'.e($a->reason).'</span>'],
            ['label' => 'User', 'render' => fn($a) => '<span style=\'color:#6b7280;font-size:12px;\'>'.e($a->user->name ?? '—').'</span>'],
            ['label' => '', 'align' => 'right', 'render' => function($a) {
                $url = route('admin.stock-adjustments.destroy', $a);
                $csrf = csrf_field(); $method = method_field('DELETE');
                return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Reverse this adjustment?&quot;)\'>'.$csrf.$method.'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
            }],
        ]"
        empty-icon="⚙️"
        empty-message="No adjustments found"
    />
</div>
@endsection