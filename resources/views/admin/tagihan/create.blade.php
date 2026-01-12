@extends('layouts.master')
@section('title', 'Generate Tagihan')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Generate Tagihan</h2>
        <p style="color: var(--muted); margin: 0;">Buat tagihan massal untuk santri.</p>
    </div>

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    @if(session('error'))
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('tagihan.store') }}" method="POST">
        @csrf
        
        <div class="form-group" style="margin-bottom: 16px;">
            <label for="bulan" style="display: block; margin-bottom: 8px; font-weight: 500;">Bulan</label>
            <select name="bulan" id="bulan" class="form-control" required>
                <option value="">Pilih Bulan</option>
                @php
                    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                @endphp
                @foreach($months as $bulan)
                    <option value="{{ $bulan }}" {{ old('bulan') == $bulan ? 'selected' : '' }}>{{ $bulan }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label for="tahun" style="display: block; margin-bottom: 8px; font-weight: 500;">Tahun</label>
            <input type="number" name="tahun" id="tahun" class="form-control" value="{{ old('tahun', date('Y')) }}" required>
        </div>

        <div class="form-group" style="margin-bottom: 16px;">
            <label for="tarif_id" style="display: block; margin-bottom: 8px; font-weight: 500;">Jenis Tarif</label>
            <select name="tarif_id" id="tarif_id" class="form-control" required>
                <option value="">Pilih Tarif</option>
                @foreach($tarifs as $tarif)
                    <option value="{{ $tarif->id }}" {{ old('tarif_id') == $tarif->id ? 'selected' : '' }}>
                        {{ $tarif->nama_tarif }} - Rp {{ number_format($tarif->nominal, 0, ',', '.') }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group" style="margin-bottom: 24px;">
            <label for="kelas_id" style="display: block; margin-bottom: 8px; font-weight: 500;">Target Kelas (Opsional)</label>
            <select name="kelas_id" id="kelas_id" class="form-control">
                <option value="">Semua Kelas</option>
                @foreach($kelas as $item)
                    <option value="{{ $item->id }}" {{ old('kelas_id') == $item->id ? 'selected' : '' }}>{{ $item->nama_kelas }}</option>
                @endforeach
            </select>
            <p style="color: var(--muted); font-size: 0.85rem; margin-top: 4px;">Kosongkan untuk generate ke semua santri aktif.</p>
        </div>

        <div style="display: flex; gap: 12px; justify-content: flex-end;">
            <a href="{{ route('tagihan.index') }}" class="btn btn-outline">Batal</a>
            <button type="submit" class="btn btn-primary">Generate Tagihan</button>
        </div>
    </form>
</div>

<style>
    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid var(--border);
        border-radius: 8px;
        font-size: 0.95rem;
        outline: none;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 3px var(--primary-soft);
    }
    .btn {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
        border: 1px solid transparent;
    }
    .btn-primary {
        background: var(--primary);
        color: white;
    }
    .btn-outline {
        background: transparent;
        border-color: var(--border);
        color: var(--text);
    }
</style>
@endsection
