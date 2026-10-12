@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Supplier</h1>

<form method="POST" action="{{ route('admin.suppliers.update', $supplier) }}" class="bg-white p-6 rounded-lg shadow max-w-xl">
    @csrf @method('PUT')

    <div class="mb-3">
        <label class="block mb-1 font-medium">Name *</label>
        <input name="name" value="{{ old('name', $supplier->name) }}" class="w-full border rounded p-2" required>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Company Name</label>
        <input name="company_name" value="{{ old('company_name', $supplier->company_name) }}" class="w-full border rounded p-2">
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Phone</label>
        <input name="phone" value="{{ old('phone', $supplier->phone) }}" class="w-full border rounded p-2">
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Email</label>
        <input name="email" type="email" value="{{ old('email', $supplier->email) }}" class="w-full border rounded p-2">
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Address</label>
        <textarea name="address" rows="2" class="w-full border rounded p-2">{{ old('address', $supplier->address) }}</textarea>
    </div>

    <div class="mb-3">
        <label class="block mb-1 font-medium">Opening Balance</label>
        <input name="opening_balance" type="number" step="0.01" value="{{ old('opening_balance', $supplier->opening_balance) }}" class="w-full border rounded p-2">
    </div>

    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    <a href="{{ route('admin.suppliers.index') }}" class="ml-2 text-gray-600">Cancel</a>
</form>
@endsection