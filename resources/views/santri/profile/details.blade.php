@extends('layouts.master')
@section('title', 'Data Diri Santri')

@section('content')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 42px;
        border: 1px solid var(--border);
        border-radius: 8px;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 40px;
        padding-left: 12px;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
</style>

<div class="card" style="max-width: 1100px; margin: 0 auto; padding: 24px 24px 28px; border-radius: 16px;">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Data Diri Santri</h2>
        <p style="color: var(--muted); margin: 0;">Lengkapi biodata Anda untuk keperluan administrasi.</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; border: 1px solid #a7f3d0; color: #047857; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; padding: 12px; border-radius: 8px; margin-bottom: 24px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update.details') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="profile-grid">
            <div class="profile-card">
                <h3 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 6px;">Informasi Kelas</h3>
                <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 18px;">Pilih tingkatan dan kelas sesuai dengan status Anda saat ini.</p>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="tingkatan" style="display: block; margin-bottom: 8px; font-weight: 500;">Tingkatan</label>
                    <select name="tingkatan" id="tingkatan" class="form-control select2" required>
                        <option value="">-- Pilih Tingkatan --</option>
                        @foreach($tingkatan as $t)
                            <option value="{{ $t }}" {{ old('tingkatan', optional($user->santri->kelas)->tingkatan) == $t ? 'selected' : '' }}>
                                {{ $t }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="kelas_id" style="display: block; margin-bottom: 8px; font-weight: 500;">Kelas</label>
                    <select name="kelas_id" id="kelas_id" class="form-control select2" required>
                        <option value="">-- Pilih Tingkatan Terlebih Dahulu --</option>
                    </select>
                    <small style="color: var(--muted); display: block; margin-top: 4px;">Pilih tingkatan terlebih dahulu, lalu pilih kelas.</small>
                </div>
            </div>

            <div class="profile-card">
                <h3 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 6px;">Data Pribadi</h3>
                <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 18px;">Lengkapi informasi dasar untuk keperluan administrasi.</p>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="tempat_lahir" style="display: block; margin-bottom: 8px; font-weight: 500;">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control" value="{{ old('tempat_lahir', $user->santri->tempat_lahir) }}" placeholder="Contoh: Jakarta" required>
                </div>

                <div class="form-group" style="margin-bottom: 20px;">
                    <label for="tanggal_lahir" style="display: block; margin-bottom: 8px; font-weight: 500;">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" id="tanggal_lahir" class="form-control" value="{{ old('tanggal_lahir', $user->santri->tanggal_lahir) }}" required>
                </div>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="jenis_kelamin" style="display: block; margin-bottom: 8px; font-weight: 500;">Jenis Kelamin</label>
                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="L" {{ old('jenis_kelamin', $user->santri->jenis_kelamin) == 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $user->santri->jenis_kelamin) == 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                </div>
            </div>

            <div class="profile-card profile-card-full">
                <h3 style="font-size: 1.05rem; font-weight: 600; margin-bottom: 6px;">Alamat Lengkap</h3>
                <p style="color: var(--muted); font-size: 0.9rem; margin-bottom: 18px;">Gunakan alamat yang aktif dan mudah dijangkau.</p>

                <div class="form-group" style="margin-bottom: 0;">
                    <label for="alamat" style="display: block; margin-bottom: 8px; font-weight: 500;">Alamat Lengkap</label>
                    <textarea name="alamat" id="alamat" class="form-control" rows="3" placeholder="Masukkan alamat lengkap" required>{{ old('alamat', $user->santri->alamat) }}</textarea>
                </div>
            </div>
        </div>

        <div class="profile-actions">
            <button type="submit" class="btn btn-primary">Simpan Data Diri</button>
        </div>
    </form>
</div>

<!-- jQuery (Required for Select2) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        const allKelas = @json($kelas->map(function ($k) {
            return ['id' => $k->id, 'nama_kelas' => $k->nama_kelas, 'tingkatan' => $k->tingkatan];
        }));
        const initialKelasId = "{{ old('kelas_id', $user->santri->kelas_id) }}";

        let initialTingkatan = "";
        if (initialKelasId) {
            const found = allKelas.find(function (k) { return k.id == initialKelasId; });
            if (found) {
                initialTingkatan = found.tingkatan;
            }
        }

        $('#tingkatan').select2({
            placeholder: "-- Pilih Tingkatan --",
            allowClear: true,
            width: '100%'
        });

        $('#kelas_id').select2({
            placeholder: "-- Pilih Tingkatan Terlebih Dahulu --",
            allowClear: true,
            width: '100%'
        });

        function populateKelas(tingkatan, selectedId) {
            const kelasSelect = $('#kelas_id');
            kelasSelect.empty();

            if (!tingkatan) {
                kelasSelect.append(new Option('-- Pilih Tingkatan Terlebih Dahulu --', '', true, true));
                kelasSelect.prop('disabled', true);
                kelasSelect.trigger('change');
                return;
            }

            kelasSelect.prop('disabled', false);
            kelasSelect.append(new Option('-- Pilih Kelas --', ''));

            allKelas.forEach(function (k) {
                if (k.tingkatan === tingkatan) {
                    const isSelected = selectedId && selectedId == k.id;
                    kelasSelect.append(new Option(k.nama_kelas, k.id, isSelected, isSelected));
                }
            });

            if (selectedId) {
                kelasSelect.val(selectedId).trigger('change');
            } else {
                kelasSelect.val('').trigger('change');
            }
        }

        $('#tingkatan').on('change', function() {
            const selectedTingkatan = $(this).val();
            populateKelas(selectedTingkatan, null);
        });

        if (initialTingkatan) {
            $('#tingkatan').val(initialTingkatan).trigger('change');
            populateKelas(initialTingkatan, initialKelasId);
        } else {
            populateKelas('', null);
        }
    });
</script>

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
    .profile-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 24px;
        margin-top: 20px;
    }
    .profile-card {
        border: 1px solid var(--border);
        border-radius: 16px;
        padding: 18px 18px 20px;
        background: #ffffff;
        box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
    }
    .profile-card-full {
        grid-column: 1 / -1;
    }
    .profile-actions {
        margin-top: 24px;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    @media (min-width: 900px) {
        .profile-grid {
            grid-template-columns: minmax(0, 1.2fr) minmax(0, 1.1fr);
        }
    }
</style>
@endsection
