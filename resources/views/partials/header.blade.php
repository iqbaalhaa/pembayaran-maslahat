<header class="topbar">
  <div class="left">
    <button class="hamburger" data-toggle-sidebar title="Menu">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd" d="M3 6.75A.75.75 0 0 1 3.75 6h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 6.75ZM3 12a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75A.75.75 0 0 1 3 12Zm0 5.25a.75.75 0 0 1 .75-.75h16.5a.75.75 0 0 1 0 1.5H3.75a.75.75 0 0 1-.75-.75Z" clip-rule="evenodd" />
      </svg>
    </button>

    <div class="search" title="Cari (Ctrl+K)">
      <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 1 0 0 13.5 6.75 6.75 0 0 0 0-13.5ZM2.25 10.5a8.25 8.25 0 1 1 14.59 5.28l4.69 4.69a.75.75 0 1 1-1.06 1.06l-4.69-4.69A8.25 8.25 0 0 1 2.25 10.5Z" clip-rule="evenodd" />
      </svg>
      <input type="text" placeholder="Cari pendaftar / NISN / status..." />
    </div>
  </div>

  <div class="right">
    {{-- <button class="icon-btn" title="Notifikasi" onclick="alert('Notifikasi dummy')">
      <svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor">
        <path fill-rule="evenodd" d="M5.25 9a6.75 6.75 0 0 1 13.5 0v.75c0 2.123.8 4.057 2.118 5.52a.75.75 0 0 1-.297 1.206c-1.544.57-3.16.99-4.831 1.243a3.75 3.75 0 1 1-7.48 0 24.585 24.585 0 0 1-4.831-1.244.75.75 0 0 1-.298-1.205A8.217 8.217 0 0 0 5.25 9.75V9Zm4.502 8.9c.465 1.418 1.779 2.35 3.248 2.35 1.47 0 2.783-.932 3.248-2.35a25.547 25.547 0 0 1-6.496 0Z" clip-rule="evenodd" />
      </svg>
    </button> --}}

    <div class="avatar-dropdown">
      <button class="avatar-trigger" onclick="this.parentElement.classList.toggle('open')">
        <span class="dot"></span>
        <div class="meta">
          <strong>{{ auth()->user()->name ?? 'Admin' }}</strong>
          <span>MASLAHAT {{ date('Y') }}/{{ date('Y')+1 }}</span>
        </div>
        <svg class="arrow" width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
          <path fill-rule="evenodd" d="M12.53 16.28a.75.75 0 0 1-1.06 0l-7.5-7.5a.75.75 0 0 1 1.06-1.06L12 14.69l6.97-6.97a.75.75 0 1 1 1.06 1.06l-7.5 7.5Z" clip-rule="evenodd" />
        </svg>
      </button>

      <div class="dropdown-menu">
        <a href="{{ route('profile.settings') }}" class="dropdown-item">Pengaturan Akun</a>
        <form action="{{ route('logout') }}" method="POST" style="margin:0;">
          @csrf
          <button type="submit" class="dropdown-item logout">Logout</button>
        </form>
      </div>
    </div>
  </div>
</header>
