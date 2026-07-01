<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Data Kontak - JavaCRM</title>
    <style>
        body {
            font-family: 'Courier', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #2563EB;
            padding-bottom: 10px;
        }
        .header h1 {
            font-size: 20px;
            margin: 0;
            color: #1E3A8A;
        }
        .header p {
            margin: 5px 0 0;
            color: #666;
            font-size: 12px;
        }
        .meta-table {
            width: 100%;
            margin-bottom: 20px;
            border: none;
        }
        .meta-table td {
            padding: 3px 0;
            border: none;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th {
            background-color: #1E3A8A;
            color: white;
            text-align: left;
            padding: 8px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        .data-table td {
            padding: 8px;
            border: 1px solid #ddd;
            vertical-align: top;
        }
        .data-table tr:nth-child(even) {
            background-color: #F3F4F6;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN DATA KONTAK</h1>
        <p>Sistem Management Relationship (CRM) - JavaCRM</p>
    </div>

    <table class="meta-table">
        <tr>
            <td style="width: 50%;"><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y H:i:s') }}</td>
            <td style="width: 50%; text-align: right;"><strong>Total Data:</strong> {{ count($persons) }} Kontak</td>
        </tr>
        <tr>
            <td><strong>Dicetak Oleh:</strong> {{ auth()->guard('user')->user()->name }}</td>
            <td style="text-align: right;"><strong>Perusahaan:</strong> {{ auth()->guard('user')->user()->company->name }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">ID</th>
                <th style="width: 25%;">Nama Lengkap</th>
                <th style="width: 25%;">Email</th>
                <th style="width: 15%;">Telepon</th>
                <th style="width: 15%;">Pekerjaan</th>
                <th style="width: 15%;">Organisasi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($persons as $person)
                <tr>
                    <td>{{ $person->id }}</td>
                    <td><strong>{{ $person->name }}</strong></td>
                    <td>
                        @if(is_array($person->emails))
                            {{ implode(', ', $person->emails) }}
                        @else
                            {{ $person->emails }}
                        @endif
                    </td>
                    <td>
                        @if(is_array($person->contact_numbers))
                            {{ implode(', ', $person->contact_numbers) }}
                        @else
                            {{ $person->contact_numbers }}
                        @endif
                    </td>
                    <td>{{ $person->job_title ?? '-' }}</td>
                    <td>{{ $person->organization?->name ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center;">Tidak ada data kontak tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Dokumen ini dihasilkan secara otomatis oleh JavaCRM. Halaman 1 dari 1
    </div>
</body>
</html>
