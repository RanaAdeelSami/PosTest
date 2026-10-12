<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 10px; padding: 20px; color: #333; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #2563eb; padding-bottom: 10px; }
        .header h1 { font-size: 18px; color: #1e40af; margin-bottom: 4px; }
        .header .subtitle { font-size: 11px; color: #666; }
        .company-info { font-size: 9px; color: #666; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #2563eb; color: white; padding: 8px 6px; text-align: left; font-size: 10px; font-weight: 600; }
        td { border-bottom: 1px solid #e5e7eb; padding: 6px; font-size: 9px; }
        tr:nth-child(even) td { background: #f9fafb; }
        tfoot td { background: #f3f4f6; font-weight: bold; border-top: 2px solid #2563eb; }
        .footer { margin-top: 20px; text-align: center; font-size: 8px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">
            Generated: {{ date('d M Y, H:i') }}
            @if(auth()->check())
                | By: {{ auth()->user()->name }}
            @endif
        </div>
    </div>

    @if($data->count() > 0)
        <table>
            <thead>
                <tr>
                    @foreach(array_keys($data->first()) as $heading)
                        <th>{{ $heading }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Total Records: {{ $data->count() }} | {{ config('app.name') }} - POS System
        </div>
    @else
        <p style="text-align: center; padding: 40px; color: #999;">No records found.</p>
    @endif

</body>
</html>