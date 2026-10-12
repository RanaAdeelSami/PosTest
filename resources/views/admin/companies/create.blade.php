@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Company</h1>

<form method="POST" action="{{ route('admin.companies.store') }}" class="bg-white p-6 rounded-lg shadow max-w-xl">
    @csrf
    <div class="mb-3">
        <label class="block mb-1 font-medium">Name *</label>
        <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
        @error('name')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Email *</label>
        <input name="email" type="email" value="{{ old('email') }}" class="w-full border rounded p-2" required>
        @error('email')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Phone</label>
        <input name="phone" value="{{ old('phone') }}" class="w-full border rounded p-2">
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Address</label>
        <textarea name="address" rows="3" class="w-full border rounded p-2">{{ old('address') }}</textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    <a href="{{ route('admin.companies.index') }}" class="ml-2 text-gray-600">Cancel</a>
</form>
@endsection