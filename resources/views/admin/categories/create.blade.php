@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">New Category</h1>

<form method="POST" action="{{ route('admin.categories.store') }}" class="bg-white p-6 rounded-lg shadow max-w-xl">
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
        <label class="block mb-1 font-medium">Name *</label>
        <input name="name" value="{{ old('name') }}" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Description</label>
        <textarea name="description" rows="3" class="w-full border rounded p-2">{{ old('description') }}</textarea>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Save</button>
    <a href="{{ route('admin.categories.index') }}" class="ml-2 text-gray-600">Cancel</a>
</form>
@endsection