@props([
    'id' => 'confirmModal',
])

<div id="{{ $id }}" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:99999;display:none;align-items:center;justify-content:center;padding:20px;">
    <div style="background:white;border-radius:14px;width:100%;max-width:420px;overflow:hidden;box-shadow:0 20px 50px rgba(0,0,0,0.25);">

        {{-- Header --}}
        <div style="padding:20px 24px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:18px;font-weight:700;color:#1a1a1a;">Confirm Delete</h2>
            <button type="button" onclick="closeConfirmModal()"
                    style="background:#dc2626;border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;">
                ×
            </button>
        </div>

        {{-- Body --}}
        <div style="padding:24px;">
            <div style="text-align:center;">
                <div style="width:64px;height:64px;border-radius:50%;background:#fee2e2;color:#dc2626;display:flex;align-items:center;justify-content:center;font-size:32px;margin:0 auto 16px;">
                    🗑️
                </div>
                <div style="font-size:16px;font-weight:600;color:#1a1a1a;margin-bottom:6px;">Are you sure?</div>
                <div id="confirmMessage" style="font-size:13.5px;color:#6b7280;line-height:1.5;">
                    This action cannot be undone.
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div style="padding:16px 24px;border-top:1px solid #f0f0f0;display:flex;justify-content:flex-end;gap:10px;background:#fafafa;">
            <button type="button" onclick="closeConfirmModal()"
                    style="padding:10px 22px;background:#f3f4f6;color:#374151;border:1px solid #e5e7eb;border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;">
                Cancel
            </button>
            <button type="button" id="confirmDeleteBtn"
                    style="padding:10px 22px;background:#dc2626;color:white;border:none;border-radius:8px;font-size:13.5px;font-weight:600;cursor:pointer;font-family:inherit;box-shadow:0 2px 8px rgba(220,38,38,0.3);">
                Yes, Delete
            </button>
        </div>

    </div>
</div>