@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit Company</h1>

<form method="POST" action="{{ route('admin.companies.update', $company) }}" class="bg-white p-6 rounded-lg shadow max-w-xl">
    @csrf @method('PUT')
    <div class="mb-3">
        <label class="block mb-1 font-medium">Name *</label>
        <input name="name" value="{{ old('name', $company->name) }}" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Email *</label>
        <input name="email" type="email" value="{{ old('email', $company->email) }}" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Phone</label>
        <input name="phone" value="{{ old('phone', $company->phone) }}" class="w-full border rounded p-2">
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Address</label>
        <textarea name="address" rows="3" class="w-full border rounded p-2">{{ old('address', $company->address) }}</textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    <a href="{{ route('admin.companies.index') }}" class="ml-2 text-gray-600">Cancel</a>
</form>
@endsection