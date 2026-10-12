@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Product</h1>

<form method="POST" action="{{ route('admin.products.store') }}" class="bg-white p-6 rounded-lg shadow max-w-xl">
    @csrf
    @if(auth()->user()->hasRole('super_admin'))
    <div class="mb-3">
        <label class="block mb-1 font-medium">Company *</label>
        <select name="company_id" class="w-full border rounded p-2" required>
            <option value="">-- Select Company --</option>
            @foreach($companies as $id => $name)
                <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    @else
    <input type="hidden" name="company_id" value="{{ auth()->user()->company_id }}">
    <div class="mb-3 p-3 bg-gray-100 rounded">
        <span class="text-gray-600">Company: </span>
        <strong>{{ auth()->user()->company->name ?? '-' }}</strong>
    </div>
@endif
    <div class="mb-3">
        <label class="block mb-1 font-medium">Category</label>
        <select name="category_id" class="w-full border rounded p-2">
            <option value="">-- None --</option>
            @foreach($categories as $id => $name)
                <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Product Name *</label>
        <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">SKU</label>
        <input name="sku" value="{{ old('sku') }}" class="w-full border rounded p-2">
    </div>
    <div class="grid grid-cols-2 gap-3 mb-3">
        <div>
            <label class="block mb-1 font-medium">Price *</label>
            <input name="price" type="number" step="0.01" value="{{ old('price', 0) }}" class="w-full border rounded p-2" required>
        </div>
        <div>
            <label class="block mb-1 font-medium">Stock *</label>
            <input name="stock" type="number" value="{{ old('stock', 0) }}" class="w-full border rounded p-2" required>
        </div>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" rows="3" class="w-full border rounded p-2">{{ old('description') }}</textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    <a href="{{ route('admin.products.index') }}" class="ml-2 text-gray-600">Cancel</a>
</form>
@endsection