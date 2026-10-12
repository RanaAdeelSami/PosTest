@extends('layouts.admin')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h1 class="text-2xl font-bold">Companies</h1>
    <a href="{{ route('admin.companies.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">+ New Company</a>
</div>

<div class="bg-white rounded-lg shadow overflow-hidden">
    <table class="w-full">
        <thead class="bg-gray-200">
            <tr>
                <th class="p-3 text-left">Name</th>
                <th class="p-3 text-left">Email</th>
                <th class="p-3 text-left">Phone</th>
                <th class="p-3 text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($companies as $company)
                <tr class="border-t">
                    <td class="p-3">{{ $company->name }}</td>
                    <td class="p-3">{{ $company->email }}</td>
                    <td class="p-3">{{ $company->phone }}</td>
                    <td class="p-3 text-right">
                        <a href="{{ route('admin.companies.edit', $company) }}" class="text-blue-600">Edit</a>
                        <form method="POST" action="{{ route('admin.companies.destroy', $company) }}" class="inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="text-red-600 ml-3">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="p-6 text-center text-gray-500">No companies yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $companies->links() }}</div>
@endsection