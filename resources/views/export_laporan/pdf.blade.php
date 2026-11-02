<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            font-size: 9px;
        }

        h3 {
            text-align: center;
            margin: 0 0 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
            word-wrap: break-word;
        }

        th {
            background: #d9d9d9;
            text-align: center;
        }
    </style>
</head>

<body>
    <h3>KPI Tim Kerja PSL Tahun {{ $tahun->tahun }}{{ $unit ? ' - Unit ' . $unit->nama_unit : '' }}</h3>
    @include('export_laporan.tabel')
</body>

</html>
