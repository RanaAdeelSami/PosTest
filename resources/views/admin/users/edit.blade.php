@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-4">Edit User</h1>

<form method="POST" action="{{ route('admin.users.update', $user) }}" class="bg-white p-6 rounded-lg shadow max-w-xl">
    @csrf @method('PUT')

    <div class="mb-3">
        <label class="block mb-1 font-medium">Name *</label>
        <input name="name" value="{{ old('name', $user->name) }}" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Email *</label>
        <input name="email" type="email" value="{{ old('email', $user->email) }}" class="w-full border rounded p-2" required>
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Password (leave blank to keep current)</label>
        <input name="password" type="password" class="w-full border rounded p-2">
    </div>
    <div class="mb-3">
        <label class="block mb-1 font-medium">Role *</label>
        <select name="role" class="w-full border rounded p-2" required>
            @foreach($roles as $role)
                <option value="{{ $role }}" {{ old('role', $user->getRoleNames()->first()) == $role ? 'selected' : '' }}>{{ $role }}</option>
            @endforeach
        </select>
    </div>
        @if(auth()->user()->hasRole('super_admin'))
    <div class="mb-3">
        <label class="block mb-1 font-medium">Company</label>
        <select name="company_id" class="w-full border rounded p-2">
            <option value="">-- None --</option>
            @foreach($companies as $id => $name)
                <option value="{{ $id }}" {{ old('company_id', $user->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
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
        <label class="block mb-1 font-medium">Branch</label>
        <select name="branch_id" class="w-full border rounded p-2">
            <option value="">-- None --</option>
            @foreach($branches as $id => $name)
                <option value="{{ $id }}" {{ old('branch_id', $user->branch_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
            @endforeach
        </select>
    </div>
    <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    <a href="{{ route('admin.users.index') }}" class="ml-2 text-gray-600">Cancel</a>
</form>
@endsection