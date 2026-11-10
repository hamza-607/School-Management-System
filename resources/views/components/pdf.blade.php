@props([
    'title' => null, // text
    'paperTitle' => null, // text
    'noDataMessage' => '', // text
    'countTitle' => null, // text
    'countValue' => null,
    'yearModel' => null, // model
    'multiHeader' => false, // boolean
    'headersTable' => [], // []
    'mainHeader' => [], // [] 
    'secundHeader' => [], // []
    'dataOfTable' => [], // [[],[],[].....]
    'total' => [], // []
    'filters' => [], // [[label, value], [label, value]...]
    'moreInfo' => [], // [[label, value], [label, value]...]
])

<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="utf-8">
    <title>{{ $title ?? 'PDF' }}</title>
    <style>
        @page {
            size: A4;
            margin: 15mm;
        }

        body {
            color: #000;
            font-size: 15px;
            margin: 0;
        }

        .toolbar {
            display: flex;
            gap: 8px;
            padding: 12px 15mm;
            background: #f5f8f7;
            border-bottom: 1px solid #dbe6e3;
        }

        .toolbar button {
            font-family: inherit;
            font-weight: 600;
            padding: 6px 16px;
            border: 1px solid #006559;
            background: #006559;
            color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .toolbar button.secondary {
            background: #fff;
            color: #006559;
        }

        .page {
            padding: 0 15mm;
        }

        .report-header {
            text-align: center;
            margin: 16px 0 14px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        .report-header h1 {
            font-size: 20px;
            margin: 0 0 2px;
        }

        .report-header h2 {
            font-size: 16px;
            margin: 0 0 6px;
        }

        .filter {
            display: flex;
            flex-wrap: wrap;
            gap: 4px 20px;
            font-size: 12px;
            text-align: start;
        }

        .report-meta {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 4px 20px;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: center;
        }

        th {
            background: #eee;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        td.name {
            text-align: right;
            font-weight: 600;
        }

        /* يكرر الترويسة بكل صفحة */
        thead {
            display: table-header-group;
        }

        /* ما يقطع صف بنص صفحتين */
        tr {
            page-break-inside: avoid;
        }

        .report-footer {
            margin-top: 14px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
        }

        @media print {
            .toolbar {
                display: none;
            }

            .page {
                padding: 0;
            }
        }
    </style>
</head>

<body onload="window.print()">

    <div class="toolbar">
        <button type="button" onclick="window.print()">طباعة / تصدير PDF</button>
        <button type="button" class="secondary" onclick="window.close()">إغلاق</button>
    </div>

    <div class="page">

        <div class="report-header">
            <h1>مدرسة أُفق</h1>

            @if ($paperTitle)
                <h2>{{ $paperTitle ?? 'PDF' }}</h2>
            @endif
            <div class="report-meta">
                @if ($yearModel)
                    <span>
                        السنة الدراسية: {{ $yearModel ? $yearModel->name : '-' }}
                    </span>
                @endif
                <span>تاريخ الطباعة: {{ now()->format('Y-m-d') }}</span>
            </div>
        </div>

        @if ($filters)
            <div class="filter" style="margin: 16px 0 10px; border-bottom: 2px solid #000; padding-bottom: 10px;">
                @foreach ($filters as $filter)
                    <span>{{ $filter['label'] }}: {{ $filter['value'] }}</span>
                @endforeach
            </div>
        @endif

        @if ($moreInfo)
            <div class="filter" style="margin: 16px 0 10px; border-bottom: 2px solid #000; padding-bottom: 10px;">
                @foreach ($moreInfo as $info)
                    <span>{{ $info['label'] }}: {{ $info['value'] }}</span>
                @endforeach
            </div>
        @endif

        <table>
            <thead>
                @if ($multiHeader)
                    <tr>
                        <th rowspan="2" style="text-align: center; vertical-align: middle;">اسم المادة</th>
                        @foreach ($mainHeader as $oneHeader)
                            <th colspan="{{ count($secundHeader) }}">{{ $oneHeader }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($mainHeader as $m)
                            @foreach ($secundHeader as $oneHeader)
                                <th>{{ $oneHeader }}</th>
                            @endforeach
                        @endforeach
                    </tr>
                @else
                    <tr>
                        <th style="width: 35px;">#</th>
                        @foreach ($headersTable as $header)
                            <th>{{$header}}</th>
                        @endforeach
                    </tr>
                @endif
            </thead>
            <tbody>
                @forelse ($dataOfTable as $index => $oneLineOfData)
                    <tr>
                        @if (!$multiHeader)
                            <td>{{ $index + 1 }}</td>
                        @endif
                        @foreach ($oneLineOfData as $data)
                            <td>{{ $data ?? 'غير محدد' }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td
                            colspan="{{ $multiHeader ? count($mainHeader) * count($secundHeader) + 1 : count($headersTable) + 1 }}">
                            {{ $noDataMessage }}
                        </td>
                    </tr>
                @endforelse
            </tbody>

            <tfoot>
                @if ($total)
                    <tr class="table-secondary fw-bold" id="totalsRow">
                        <td>المجموع النهائي</td>
                        @foreach ($total as $s)
                            <td class="quizzes-col" colspan="2"></td>
                            <td class="total-col grand-total-cell">{{ $s }}</td>
                        @endforeach
                    </tr>
                @endif
            </tfoot>
        </table>

        <div class="report-footer">
            @if ($countTitle)
                <span>{{ $countTitle }}: {{ $countValue }}</span>
            @endif
            <span>طُبعت بواسطة: {{ auth()->user()->name }}</span>
        </div>

    </div>
</body>

</html>