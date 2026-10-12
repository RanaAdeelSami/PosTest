@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Warehouse</h1>

<form method="POST" action="{{ route('admin.warehouses.store') }}" class="bg-white p-6 rounded-lg shadow max-w-xl">
    @csrf

    <div class="mb-3">
        <label class="block mb-1 font-medium">Name *</label>
        <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Code</label>
        <input name="code" value="{{ old('code') }}" class="w-full border rounded p-2" placeholder="WH-001">
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Branch</label>
        <select name="branch_id" class="w-full border rounded p-2">
            <option value="">-- None (independent warehouse) --</option>
            @foreach($branches as $id => $name)
                <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Address</label>
        <textarea name="address" rows="2" class="w-full border rounded p-2">{{ old('address') }}</textarea>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Phone</label>
        <input name="phone" value="{{ old('phone') }}" class="w-full border rounded p-2">
    </div>

    <div class="mb-3 flex gap-4">
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}>
            <span>Active</span>
        </label>
        <label class="flex items-center gap-2">
            <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
            <span>Default Warehouse</span>
        </label>
    </div>

    <div class="flex justify-end items-center gap-3 pt-3 border-t">
        <a href="{{ route('admin.warehouses.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700">Cancel</a>
        <button class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Save Warehouse</button>
    </div>
</form>
@endsection