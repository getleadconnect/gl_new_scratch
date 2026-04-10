<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>QR Codes - {{ $section->section_name }}</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; background: #fff; }
        .page-header {
            text-align: center;
            margin-bottom: 18px;
            padding-bottom: 10px;
            border-bottom: 2px solid #18181b;
        }
        .page-header h1 { font-size: 16px; font-weight: 700; color: #18181b; }
        .page-header p  { font-size: 11px; color: #6b7280; margin-top: 3px; }
        .grid {
            width: 100%;
        }
        .grid-row {
            width: 100%;
            margin-bottom: 10px;
        }
        .grid-row:after { content: ""; display: table; clear: both; }
        .qr-cell {
            float: left;
            width: 25%;
            padding: 6px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
        }
        .qr-cell img {
            width: 90px;
            height: 90px;
            display: block;
            margin: 0 auto 5px;
        }
        .qr-cell .code {
            font-size: 10px;
            font-weight: 700;
            color: #18181b;
            word-break: break-all;
        }
        .qr-cell .no-qr {
            width: 90px;
            height: 90px;
            background: #f3f4f6;
            display: block;
            margin: 0 auto 5px;
            line-height: 90px;
            font-size: 10px;
            color: #9ca3af;
        }
        table.qr-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 4px;
        }
        table.qr-table td {
            width: 20%;
            text-align: center;
            vertical-align: top;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            padding: 5px 3px;
        }
        table.qr-table td img {
            width: 75px;
            height: 75px;
        }
        table.qr-table td .code {
            display: block;
            font-size: 8px;
            font-weight: 700;
            color: #18181b;
            margin-top: 4px;
            word-break: break-all;
        }
        table.qr-table td .no-img {
            display: block;
            width: 68px;
            height: 68px;
            background: #f3f4f6;
            margin: 0 auto;
            line-height: 68px;
            font-size: 8px;
            color: #9ca3af;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 9px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
            padding-top: 6px;
        }
    </style>
</head>
<body>

    <div class="page-header">
        <h1>QR Code PDF</h1>
        <p>Section: {{ $section->section_name }} &nbsp;|&nbsp; Total: {{ $links->count() }} links</p>
    </div>

    @php $perRow = 6; $chunks = $links->chunk($perRow); @endphp

    <table class="qr-table">
        @foreach($chunks as $row)
        <tr>
            @foreach($row as $link)
            <td>
                @if($link->qr_base64)
                    <img src="{{ $link->qr_base64 }}" alt="QR">
                @else
                    <span class="no-img">No QR</span>
                @endif
                <span class="code">{{ $link->short_code }}</span>
            </td>
            @endforeach
            {{-- fill empty cells to keep 5 columns --}}
            @for($i = $row->count(); $i < $perRow; $i++)
            <td style="border:none;"></td>
            @endfor
        </tr>
        @endforeach
    </table>

    <div class="footer">
        Generated on {{ date('d-m-Y H:i') }}
    </div>

</body>
</html>
