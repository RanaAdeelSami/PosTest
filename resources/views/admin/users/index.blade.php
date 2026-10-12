@extends('layouts.admin')

@section('content')
<x-data-table
    title="Users"
    subtitle="Manage users & roles"
    search-placeholder="Search name or email..."
    :refresh-route="route('admin.users.index')"
    :create-route="route('admin.users.create')"
    create-label="➕ New User"
    :filters="[
        ['name' => 'role', 'placeholder' => 'All Roles', 'options' => $roles->mapWithKeys(fn($r) => [$r => ucfirst(str_replace('_', ' ', $r))])->toArray()],
        ['name' => 'company_id', 'placeholder' => 'All Companies', 'options' => $companies->toArray()],
    ]"
    :items="$users"
    :columns="[
        ['label' => 'User', 'render' => function($u) {
            $initial = strtoupper(substr($u->name ?? '?', 0, 1));
            return '<div style=\'display:flex;align-items:center;gap:10px;\'>'
                . '<div style=\'width:36px;height:36px;border-radius:9px;background:linear-gradient(135deg,#3b82f6,#1e40af);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;flex-shrink:0;\'>'.e($initial).'</div>'
                . '<div><div style=\'font-weight:600;color:#1a1a1a;\'>'.e($u->name).'</div>'
                . '<div style=\'font-size:11.5px;color:#9ca3af;\'>'.e($u->email).'</div></div>'
                . '</div>';
        }],
        ['field' => 'company.name', 'label' => 'Company', 'type' => 'muted'],
        ['field' => 'branch.name', 'label' => 'Branch', 'type' => 'muted'],
        ['label' => 'Role', 'render' => function($u) {
            $roles = $u->getRoleNames();
            if ($roles->isEmpty()) return '<span style=\'color:#d1d5db;\'>—</span>';
            $colors = [
                'super_admin' => 'background:#fee2e2;color:#991b1b;',
                'company_head' => 'background:#fef3c7;color:#92400e;',
                'manager' => 'background:#dbeafe;color:#1e40af;',
                'salesman' => 'background:#dcfce7;color:#166534;',
            ];
            $out = '';
            foreach ($roles as $role) {
                $style = $colors[$role] ?? 'background:#f3f4f6;color:#6b7280;';
                $out .= '<span style=\'display:inline-block;'.$style.'padding:4px 10px;border-radius:6px;font-size:11px;font-weight:600;margin-right:4px;\'>'.e(ucfirst(str_replace('_', ' ', $role))).'</span>';
            }
            return $out;
        }],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($u) => $u->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ]"
    :row-actions="[
        ['route' => 'admin.users.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($u) {
            if ($u->id === auth()->id()) return '';
            $url = route('admin.users.destroy', $u);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this user?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]"
    empty-icon="👥"
    empty-message="No users found"
    :empty-action-url="route('admin.users.create')"
    empty-action-label="+ New User"
/>
@endsection