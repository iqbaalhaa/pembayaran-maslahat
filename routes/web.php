<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\SantriController;
use App\Http\Controllers\Admin\AkunSantriController;
use App\Http\Controllers\Admin\KelasController;


Route::get('/', function () {
    $role = Auth::user()->role;
    if ($role === 'admin') {
        $totalSantri = \App\Models\Santri::count();
        $totalAkun = \App\Models\User::count();
        return view('admin.dashboard', compact('totalSantri', 'totalAkun'));
    } elseif ($role === 'santri') {
        return view('santri.dashboard');
    }
    return abort(403);
})->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/profile/settings', [App\Http\Controllers\ProfileController::class, 'settings'])->name('profile.settings');
    Route::put('/profile/settings', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    
    // Route khusus untuk detail profil santri
    Route::get('/profile/details', [App\Http\Controllers\ProfileController::class, 'details'])->name('profile.details');
    Route::put('/profile/details', [App\Http\Controllers\ProfileController::class, 'updateDetails'])->name('profile.update.details');
    Route::get('/profile/kelas', [App\Http\Controllers\ProfileController::class, 'kelas'])->name('profile.kelas');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Master Data
    Route::resource('santri', SantriController::class);
    Route::get('santri/export/excel', [SantriController::class, 'export'])->name('santri.export');
    Route::post('santri/import/excel', [SantriController::class, 'import'])->name('santri.import');

    // Akun Santri
    Route::post('akun-santri/import', [AkunSantriController::class, 'import'])->name('akun-santri.import');
    Route::get('akun-santri/template', [AkunSantriController::class, 'downloadTemplate'])->name('akun-santri.template');
    Route::resource('akun-santri', AkunSantriController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy']);
    
    Route::resource('kelas', KelasController::class)
        ->parameters(['kelas' => 'kelas'])
        ->except(['create', 'show']);

    Route::resource('tarif', \App\Http\Controllers\Admin\TarifController::class);
    
    // Tagihan
    Route::resource('tagihan', \App\Http\Controllers\Admin\TagihanController::class);
    Route::get('/tagihan/detail', function () { return 'Halaman Detail Tagihan'; });

    // Pembayaran
    Route::get('/pembayaran/create', [\App\Http\Controllers\Admin\PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('/pembayaran', [\App\Http\Controllers\Admin\PembayaranController::class, 'store'])->name('pembayaran.store');
    Route::get('/pembayaran/get-tagihan/{id}', [\App\Http\Controllers\Admin\PembayaranController::class, 'getTagihan'])->name('pembayaran.get-tagihan');
    Route::get('/pembayaran/get-pending', [\App\Http\Controllers\Admin\PembayaranController::class, 'getPending'])->name('pembayaran.get-pending');
    Route::get('/pembayaran/get-by-class/{kelasId}', [\App\Http\Controllers\Admin\PembayaranController::class, 'getByClass'])->name('pembayaran.get-by-class');
    Route::get('/pembayaran/get-history-by-class/{kelasId}', [\App\Http\Controllers\Admin\PembayaranController::class, 'getHistoryByClass'])->name('pembayaran.get-history-by-class');
    Route::get('/pembayaran/get-riwayat/{id}', [\App\Http\Controllers\Admin\PembayaranController::class, 'getRiwayat'])->name('pembayaran.get-riwayat');
    Route::get('/pembayaran/cetak', [\App\Http\Controllers\Admin\PembayaranController::class, 'cetak'])->name('pembayaran.cetak');
    Route::post('/pembayaran/print', [\App\Http\Controllers\Admin\PembayaranController::class, 'print'])->name('pembayaran.print');

    // Laporan
    Route::get('/laporan/bulanan', [\App\Http\Controllers\Admin\LaporanController::class, 'bulanan'])->name('laporan.bulanan');
    Route::get('/laporan/bulanan/export', [\App\Http\Controllers\Admin\LaporanController::class, 'exportBulanan'])->name('laporan.bulanan.export');
    Route::get('/laporan/bulanan/export-pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportBulananPdf'])->name('laporan.bulanan.export-pdf');
    Route::get('/laporan/tunggakan', [\App\Http\Controllers\Admin\LaporanController::class, 'tunggakan'])->name('laporan.tunggakan');
    Route::get('/laporan/tunggakan/export', [\App\Http\Controllers\Admin\LaporanController::class, 'exportTunggakan'])->name('laporan.tunggakan.export');
    Route::get('/laporan/tunggakan/export-pdf', [\App\Http\Controllers\Admin\LaporanController::class, 'exportTunggakanPdf'])->name('laporan.tunggakan.export-pdf');
    Route::get('/laporan/rekap', function () { return 'Halaman Rekap Laporan'; });
});

Route::middleware(['auth', 'role:santri'])->prefix('santri')->group(function () {
    Route::get('/dashboard', function () { return view('santri.dashboard'); })->name('santri.dashboard');
    Route::get('/tagihan', [App\Http\Controllers\Santri\TagihanController::class, 'index'])->name('santri.tagihan');
    Route::get('/tagihan/{id}/bayar', [App\Http\Controllers\Santri\TagihanController::class, 'bayar'])->name('santri.tagihan.bayar');
    Route::put('/tagihan/{id}/bayar', [App\Http\Controllers\Santri\TagihanController::class, 'processBayar'])->name('santri.tagihan.process-bayar');
    Route::get('/riwayat', [App\Http\Controllers\Santri\TagihanController::class, 'riwayat'])->name('santri.riwayat');
    Route::get('/riwayat/{id}/kwitansi', [App\Http\Controllers\Santri\TagihanController::class, 'kwitansi'])->name('santri.riwayat.kwitansi');
    Route::get('/kwitansi', function () { return 'Halaman Unduh Kwitansi'; });
    Route::get('/upload', function () { return 'Halaman Upload Bukti'; });
});


Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'login' => ['required'],
        'password' => ['required'],
    ]);

    $login_type = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

    if (Auth::attempt([$login_type => $request->login, 'password' => $request->password])) {
        $request->session()->regenerate();

        // Cek apakah santri masih menggunakan password default (NISN/Username)
        $user = Auth::user();
        if ($user->role === 'santri') {
            if ($request->password === $user->username) {
                session()->flash('alert_ganti_password', true);
            }
            
            // Cek apakah data profil santri belum lengkap
            if ($user->santri && (
                empty($user->santri->tempat_lahir) || 
                empty($user->santri->tanggal_lahir) || 
                empty($user->santri->jenis_kelamin) || 
                empty($user->santri->alamat)
            )) {
                session()->flash('alert_lengkapi_profil', true);
            }
        }

        return redirect()->intended('/');
    }

    return back()->withErrors([
        'login' => 'Kombinasi login dan password tidak ditemukan.',
    ])->onlyInput('login');
});

Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');
