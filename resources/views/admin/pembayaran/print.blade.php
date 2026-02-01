<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kwitansi Pembayaran</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 14px;
            line-height: 1.5;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #000;
            padding: 30px;
            margin-bottom: 20px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header h2 {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: normal;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 12px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th, .items-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f0f0f0;
            text-align: center;
        }
        .items-table td.amount {
            text-align: right;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
        }
        .signature {
            margin-top: 80px;
            border-top: 1px solid #000;
            display: inline-block;
            width: 200px;
            text-align: center;
            padding-top: 5px;
        }
        @media print {
            body { 
                padding: 0; 
                -webkit-print-color-adjust: exact;
                background: white;
            }
            .container {
                border: none;
                padding: 0;
                margin: 0;
                width: 100%;
                max-width: none;
                page-break-after: always;
            }
            .container:last-child {
                page-break-after: auto;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px; padding: 10px; background: #f0f0f0;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">Cetak Halaman</button>
        <button onclick="window.close()" style="padding: 10px 20px; cursor: pointer; font-size: 16px;">Tutup</button>
    </div>

    @foreach($groupedTagihans as $santriId => $tagihans)
    @php 
        $santri = $tagihans->first()->santri; 
        $total = 0;
        $logoPath = \App\Models\Setting::getValue('app_logo');
        $logoUrl = $logoPath ? asset('storage/' . $logoPath) : null;
    @endphp
    <div class="container">
        <div class="header">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Logo" style="height: 70px; margin-bottom: 10px;">
            @endif
            <h1>Pesantren Darussalam Al-Hafidz</h1>
            <h2>Kwitansi Pembayaran Maslahat</h2>
            <p>Jl. Contoh Alamat No. 123, Kota Jambi, Indonesia</p>
        </div>

        <table class="info-table">
            <tr>
                <td width="150"><strong>Nama Santri</strong></td>
                <td width="10">:</td>
                <td>{{ $santri->nama }}</td>
                <td width="150"><strong>Tanggal Cetak</strong></td>
                <td width="10">:</td>
                <td>{{ date('d M Y') }}</td>
            </tr>
            <tr>
                <td><strong>NIS / Kelas</strong></td>
                <td>:</td>
                <td>{{ $santri->nis }} / {{ is_object($santri->kelas) ? ($santri->kelas->nama_kelas ?? '-') : ($santri->kelas ?? '-') }}</td>
                <td><strong>Petugas</strong></td>
                <td>:</td>
                <td>{{ auth()->user()->name ?? 'Admin' }}</td>
            </tr>
        </table>

        <table class="items-table">
            <thead>
                <tr>
                    <th width="50">No</th>
                    <th>Keterangan Pembayaran</th>
                    <th width="150">Bulan / Tahun</th>
                    <th width="150">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tagihans as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>{{ $item->tarif->nama_tarif }}</td>
                    <td style="text-align: center;">
                        @php
                            $monthName = [
                                '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                                '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                                '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                            ];
                            $bulan = is_numeric($item->bulan) && isset($monthName[$item->bulan]) 
                                    ? $monthName[$item->bulan] 
                                    : $item->bulan;
                        @endphp
                        {{ $bulan }} {{ $item->tahun }}
                    </td>
                    <td class="amount">{{ number_format($item->jumlah, 0, ',', '.') }}</td>
                </tr>
                @php $total += $item->jumlah; @endphp
                @endforeach
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL PEMBAYARAN</td>
                    <td class="amount" style="font-weight: bold;">{{ number_format($total, 0, ',', '.') }}</td>
                </tr>
            </tbody>
        </table>

        <div style="margin-top: 10px; font-style: italic;">
            Terbilang: # {{ ucwords(\NumberFormatter::create('id', \NumberFormatter::SPELLOUT)->format($total)) }} Rupiah #
        </div>

        <div class="footer">
            <p>Jambi, {{ date('d F Y') }}</p>
            <div class="signature">
                Bagian Keuangan
            </div>
        </div>
    </div>
    @endforeach

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
