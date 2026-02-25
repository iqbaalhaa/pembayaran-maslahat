<!DOCTYPE html>
<html>
<head>
    <title>Laporan Tunggakan</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #555; }
        .meta { margin-bottom: 15px; }
        .meta table { width: 100%; border: none; }
        .meta td { padding: 2px 0; }
        table.data { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table.data th, table.data td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        table.data th { background-color: #f2f2f2; }
        .summary { width: 40%; margin-left: auto; border: 1px solid #ddd; padding: 10px; }
        .summary div { display: flex; justify-content: space-between; margin-bottom: 5px; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #777; }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logoPath = \App\Models\Setting::getValue('app_logo');
            $logoFile = $logoPath ? public_path('assets-file/' . $logoPath) : null;
        @endphp
        @if($logoFile && file_exists($logoFile))
            <img src="{{ $logoFile }}" alt="Logo" style="height: 60px; margin-bottom: 8px;">
        @endif
        <h2>Laporan Tunggakan</h2>
        <p>Pembayaran Maslahat</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="100">Bulan/Tahun</td>
                <td>: {{ $selectedMonth ?? 'Semua' }} {{ $selectedYear ?? '' }}</td>
            </tr>
            @if($selectedTingkatan)
            <tr>
                <td>Tingkatan</td>
                <td>: {{ $selectedTingkatan }}</td>
            </tr>
            @endif
            @if($selectedKelasId)
            <tr>
                <td>Kelas</td>
                <td>: {{ $namaKelas ?? '-' }}</td>
            </tr>
            @endif
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th>NIS</th>
                <th>Nama Santri</th>
                <th>Kelas</th>
                <th>Jenis Tagihan</th>
                <th>Bulan</th>
                <th>Tahun</th>
                <th>Nominal</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tagihans as $item)
            <tr>
                <td style="text-align: center;">{{ $loop->iteration }}</td>
                <td>{{ $item->santri->nis }}</td>
                <td>{{ $item->santri->nama }}</td>
                <td>{{ optional($item->santri->kelas)->nama_kelas ?? ($item->santri->kelas ?? '-') }}</td>
                <td>{{ optional($item->tarif)->nama_tarif ?? '-' }}</td>
                <td>{{ $item->bulan }}</td>
                <td>{{ $item->tahun }}</td>
                <td style="text-align: right;">Rp {{ number_format($item->jumlah, 0, ',', '.') }}</td>
                <td>{{ $item->status == 'menunggu_konfirmasi' ? 'Menunggu Konfirmasi' : 'Belum Lunas' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align: center;">Tidak ada data tunggakan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div style="font-weight: bold; border-bottom: 1px solid #eee; padding-bottom: 5px; margin-bottom: 5px;">Ringkasan</div>
        <table style="width: 100%; border: none;">
            <tr>
                <td>Total Item</td>
                <td style="text-align: right;">{{ $totalTransaksi }}</td>
            </tr>
            <tr>
                <td>Total Nominal</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($totalJumlah, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak pada: {{ date('d/m/Y H:i') }}
    </div>
</body>
</html>
