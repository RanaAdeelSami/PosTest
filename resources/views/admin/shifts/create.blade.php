@extends('layouts.admin')

@section('content')
<div class="max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Open New Shift</h1>

    <div class="bg-blue-50 border border-blue-200 text-blue-800 p-3 rounded mb-4 text-sm">
        POS use karne se pehle shift open karna zaroori hai. Opening cash daalein (drawer me jo paisa hai).
    </div>

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-800 p-3 rounded mb-4">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.shifts.store') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf

        <div class="mb-3">
            <label class="block mb-1 font-medium">Branch *</label>
            <select name="branch_id" class="w-full border rounded p-2" required>
                <option value="">-- Select Branch --</option>
                @foreach($branches as $id => $name)
                    <option value="{{ $id }}" {{ old('branch_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('branch_id')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3">
            <label class="block mb-1 font-medium">Opening Cash *</label>
            <input name="opening_cash" type="number" step="0.01" min="0"
                   value="{{ old('opening_cash', 0) }}"
                   class="w-full border rounded p-2 text-lg font-bold" required>
            <div class="text-xs text-gray-500 mt-1">Drawer me jitna cash hai woh daalein.</div>
            @error('opening_cash')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium">Notes</label>
            <textarea name="notes" rows="2" class="w-full border rounded p-2">{{ old('notes') }}</textarea>
        </div>

        <div class="flex justify-end items-center gap-3 pt-3 border-t">
            <a href="{{ route('admin.shifts.index') }}" class="px-4 py-2 border border-gray-300 rounded text-gray-700">Cancel</a>
            <button class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700 font-bold">
                🟢 Open Shift
            </button>
        </div>
    </form>
</div>
@endsection