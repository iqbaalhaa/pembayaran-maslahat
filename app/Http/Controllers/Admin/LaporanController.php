<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tagihan;
use App\Models\Kelas;
use Illuminate\Http\Request;
use App\Exports\LaporanBulananExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    private function getTunggakanParams(Request $request)
    {
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];
        $selectedMonth = $request->get('bulan');
        $selectedYear = $request->get('tahun');
        $selectedTingkatan = $request->get('tingkatan');
        $selectedKelasId = $request->get('kelas_id');
        return compact('months', 'selectedMonth', 'selectedYear', 'selectedTingkatan', 'selectedKelasId');
    }
    
    private function getTunggakanData($params)
    {
        $query = Tagihan::with(['santri.kelas', 'tarif'])
            ->whereIn('status', ['belum_lunas', 'menunggu_konfirmasi']);
        if ($params['selectedMonth']) {
            $query->where('bulan', $params['selectedMonth']);
        }
        if ($params['selectedYear']) {
            $query->where('tahun', $params['selectedYear']);
        }
        if ($params['selectedKelasId']) {
            $query->whereHas('santri', function ($q) use ($params) {
                $q->where('kelas_id', $params['selectedKelasId']);
            });
        } elseif ($params['selectedTingkatan']) {
            $query->whereHas('santri.kelas', function ($q) use ($params) {
                $q->where('tingkatan', $params['selectedTingkatan']);
            });
        }
        return $query->orderBy('tahun')->orderBy('bulan')->orderBy('santri_id')->get();
    }
    
    public function tunggakan(Request $request)
    {
        $params = $this->getTunggakanParams($request);
        $tagihans = $this->getTunggakanData($params);
        $totalJumlah = $tagihans->sum('jumlah');
        $totalTransaksi = $tagihans->count();
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $tingkatans = $kelas->pluck('tingkatan')->unique()->values();
        $viewData = array_merge($params, compact('tagihans', 'kelas', 'tingkatans', 'totalJumlah', 'totalTransaksi'));
        return view('admin.laporan.tunggakan', $viewData);
    }
    
    public function exportTunggakan(Request $request)
    {
        $params = $this->getTunggakanParams($request);
        $tagihans = $this->getTunggakanData($params);
        $fileName = 'laporan_tunggakan' 
            . ($params['selectedMonth'] ? '_' . strtolower($params['selectedMonth']) : '')
            . ($params['selectedYear'] ? '_' . $params['selectedYear'] : '')
            . '.xlsx';
        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\LaporanTunggakanExport($tagihans), $fileName);
    }
    
    public function exportTunggakanPdf(Request $request)
    {
        $params = $this->getTunggakanParams($request);
        $tagihans = $this->getTunggakanData($params);
        $totalJumlah = $tagihans->sum('jumlah');
        $totalTransaksi = $tagihans->count();
        $namaKelas = null;
        if ($params['selectedKelasId']) {
            $k = Kelas::find($params['selectedKelasId']);
            $namaKelas = $k ? $k->nama_kelas : '-';
        }
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.tunggakan_pdf', array_merge($params, compact('tagihans', 'totalJumlah', 'totalTransaksi', 'namaKelas')));
        $fileName = 'laporan_tunggakan' 
            . ($params['selectedMonth'] ? '_' . strtolower($params['selectedMonth']) : '')
            . ($params['selectedYear'] ? '_' . $params['selectedYear'] : '')
            . '.pdf';
        return $pdf->download($fileName);
    }
    private function getFilterParams(Request $request)
    {
        $months = [
            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
        ];

        $selectedMonth = $request->get('bulan') ?: $months[date('n') - 1];
        $selectedYear = $request->get('tahun') ?: date('Y');
        $selectedTingkatan = $request->get('tingkatan');
        $selectedKelasId = $request->get('kelas_id');

        return compact('months', 'selectedMonth', 'selectedYear', 'selectedTingkatan', 'selectedKelasId');
    }

    private function getFilteredData($params)
    {
        $query = Tagihan::with(['santri.kelas', 'tarif'])
            ->where('status', 'lunas');

        if ($params['selectedMonth']) {
            $query->where('bulan', $params['selectedMonth']);
        }

        if ($params['selectedYear']) {
            $query->where('tahun', $params['selectedYear']);
        }

        if ($params['selectedKelasId']) {
            $query->whereHas('santri', function ($q) use ($params) {
                $q->where('kelas_id', $params['selectedKelasId']);
            });
        } elseif ($params['selectedTingkatan']) {
            $query->whereHas('santri.kelas', function ($q) use ($params) {
                $q->where('tingkatan', $params['selectedTingkatan']);
            });
        }

        return $query->orderBy('updated_at', 'desc')->get();
    }

    public function bulanan(Request $request)
    {
        $params = $this->getFilterParams($request);
        $tagihans = $this->getFilteredData($params);

        $totalJumlah = $tagihans->sum('jumlah');
        $totalTransaksi = $tagihans->count();

        $kelas = Kelas::orderBy('nama_kelas')->get();
        $tingkatans = $kelas->pluck('tingkatan')->unique()->values();

        // Merge params into view data
        $viewData = array_merge($params, compact(
            'tagihans',
            'kelas',
            'tingkatans',
            'totalJumlah',
            'totalTransaksi'
        ));

        return view('admin.laporan.bulanan', $viewData);
    }

    public function exportBulanan(Request $request)
    {
        $params = $this->getFilterParams($request);
        $tagihans = $this->getFilteredData($params);

        $fileName = 'laporan_bulanan_' . str_replace(' ', '_', strtolower($params['selectedMonth'])) . '_' . $params['selectedYear'] . '.xlsx';

        return Excel::download(new LaporanBulananExport($tagihans), $fileName);
    }

    public function exportBulananPdf(Request $request)
    {
        $params = $this->getFilterParams($request);
        $tagihans = $this->getFilteredData($params);
        
        $totalJumlah = $tagihans->sum('jumlah');
        $totalTransaksi = $tagihans->count();
        
        $namaKelas = null;
        if ($params['selectedKelasId']) {
            $k = Kelas::find($params['selectedKelasId']);
            $namaKelas = $k ? $k->nama_kelas : '-';
        }

        $pdf = Pdf::loadView('admin.laporan.bulanan_pdf', array_merge($params, compact('tagihans', 'totalJumlah', 'totalTransaksi', 'namaKelas')));
        
        $fileName = 'laporan_bulanan_' . str_replace(' ', '_', strtolower($params['selectedMonth'])) . '_' . $params['selectedYear'] . '.pdf';
        
        return $pdf->download($fileName);
    }
}
