@props([
    'id',
    'title' => 'Modal',
    'maxWidth' => '480px',
])

<div id="{{ $id }}" style="position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;display:none;align-items:center;justify-content:center;padding:20px;">
    <div style="background:white;border-radius:14px;width:100%;max-width:{{ $maxWidth }};max-height:90vh;overflow:hidden;display:flex;flex-direction:column;box-shadow:0 20px 50px rgba(0,0,0,0.2);">

        {{-- Header --}}
        <div style="padding:20px 24px;border-bottom:1px solid #f0f0f0;display:flex;justify-content:space-between;align-items:center;">
            <h2 style="font-size:18px;font-weight:700;color:#1a1a1a;">{{ $title }}</h2>
            <button type="button" onclick="closeModal('{{ $id }}')"
                    style="background:#dc2626;border:none;color:white;width:28px;height:28px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:bold;">
                ×
            </button>
        </div>

        {{-- Body --}}
        <div style="padding:20px 24px;overflow-y:auto;flex:1;">
            {{ $slot }}
        </div>

    </div>
</div>