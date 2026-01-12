<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'Admin Panel') — PEMBAYARAN MASLAHAT | DARUSSALAM AL-HAFIDZ</title>

  <link rel="stylesheet" href="{{ asset('backend/css/admin.css') }}?v={{ time() }}" />
  @stack('styles')
</head>
<body>

  <div class="container">

    {{-- Sidebar --}}
    @include('partials.sidebar')

    {{-- Content --}}
    <section class="content">

      {{-- Header / Topbar --}}
      @include('partials.header')


    {{-- Mobile backdrop --}}
    <div data-sidebar-backdrop
      style="display:none; position:fixed; inset:0; background:rgba(2,6,23,.45); z-index:55"></div>

    {{-- Main Content --}}
    <main class="main">
      @yield('content')
    </main>

    {{-- Footer --}}
    @include('partials.footer')
  </section>

</div>
<script src="{{ asset('backend/js/admin.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            icon: 'success',
            confirmButtonColor: '#16a34a',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Gagal!',
            text: "{{ session('error') }}",
            icon: 'error',
            confirmButtonColor: '#dc2626',
            confirmButtonText: 'OK'
        });
    });
</script>
@endif
@if(session('alert_ganti_password'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Keamanan Akun',
            text: "Anda masih menggunakan password default (NISN). Demi keamanan akun Anda, harap segera ganti password.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Ganti Password Sekarang',
            cancelButtonText: 'Nanti Saja'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('profile.settings') }}";
            }
        });
    });
</script>
@elseif(session('alert_lengkapi_profil'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            title: 'Lengkapi Profil',
            text: "Data profil Anda belum lengkap (Tempat/Tanggal Lahir, Jenis Kelamin, Alamat). Mohon lengkapi data Anda.",
            icon: 'info',
            showCancelButton: true,
            confirmButtonColor: '#0ea5e9',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Lengkapi Sekarang',
            cancelButtonText: 'Nanti Saja'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ route('profile.details') }}";
            }
        });
    });
</script>
@endif
@stack('scripts')
</body>
</html>
