@extends('layouts.admin')

@section('content')
<x-data-table
    title="Branches"
    subtitle="Manage your branches"
    search-placeholder="Search branch name or code..."
    :refresh-route="route('admin.branches.index')"
    :create-route="route('admin.branches.create')"
    create-label="➕ New Branch"
    :filters="[
        ['name' => 'company_id', 'placeholder' => 'All Companies', 'options' => $companies->toArray()],
    ]"
    :items="$branches"
    :columns="[
        ['field' => 'name', 'label' => 'Branch Name', 'type' => 'bold'],
        ['field' => 'code', 'label' => 'Code', 'type' => 'monospace'],
        ['field' => 'company.name', 'label' => 'Company'],
        ['field' => 'phone', 'label' => 'Phone', 'type' => 'muted'],
        ['field' => 'address', 'label' => 'Address', 'type' => 'muted'],
        ['label' => 'Status', 'align' => 'center', 'render' => fn($b) => $b->is_active
            ? '<span style=\'background:#dcfce7;color:#166534;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Active</span>'
            : '<span style=\'background:#f3f4f6;color:#6b7280;padding:4px 10px;border-radius:6px;font-size:11.5px;font-weight:600;\'>Inactive</span>'
        ],
    ]"
    :row-actions="[
        ['route' => 'admin.branches.edit', 'icon' => '✏️', 'label' => 'Edit'],
        ['render' => function($b) {
            $url = route('admin.branches.destroy', $b);
            return '<form method=\'POST\' action=\''.$url.'\' style=\'display:inline;\' onsubmit=\'return confirm(&quot;Delete this branch?&quot;)\'>'.csrf_field().method_field('DELETE').'<button type=\'submit\' title=\'Delete\' style=\'display:inline-flex;width:34px;height:34px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;align-items:center;justify-content:center;font-size:14px;cursor:pointer;\'>🗑️</button></form>';
        }],
    ]"
    empty-icon="🏢"
    empty-message="No branches found"
    :empty-action-url="route('admin.branches.create')"
    empty-action-label="+ New Branch"
/>
@endsection