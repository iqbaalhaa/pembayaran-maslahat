@extends('layouts.master')
@section('title', 'Input Pembayaran')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container .select2-selection--single {
        height: 42px;
        border: 1px solid var(--border);
        border-radius: 8px;
        display: flex;
        align-items: center;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }
    .bill-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        background: var(--surface);
        display: flex;
        justify-content: space-between;
        align-items: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    .bill-card:hover {
        border-color: var(--primary);
        background: #f0f9ff;
    }
    .bill-card.selected {
        border-color: var(--primary);
        background: #eff6ff;
        box-shadow: 0 0 0 1px var(--primary);
    }
    .bill-info h4 {
        margin: 0 0 4px;
        font-size: 1rem;
        color: var(--text);
    }
    .bill-info p {
        margin: 0;
        color: var(--muted);
        font-size: 0.9rem;
    }
    .bill-amount {
        font-weight: 700;
        color: var(--primary);
        font-size: 1.1rem;
    }
    .history-card {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 10px;
        background: #f8fafc;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .history-amount {
        font-weight: 700;
        color: var(--success);
        font-size: 1.1rem;
    }
    .history-date {
        font-size: 0.8rem;
        color: var(--muted);
        text-align: right;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: var(--muted);
    }
    .summary-card {
        background: var(--surface);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 20px;
        position: sticky;
        top: 80px;
    }
    .tabs {
        display: flex;
        border-bottom: 1px solid var(--border);
        margin-bottom: 20px;
        gap: 20px;
    }
    .tab-item {
        padding: 10px 0;
        cursor: pointer;
        border-bottom: 2px solid transparent;
        font-weight: 600;
        color: var(--muted);
        position: relative;
        top: 1px;
    }
    .tab-item:hover {
        color: var(--primary);
    }
    .tab-item.active {
        color: var(--primary);
        border-bottom-color: var(--primary);
    }
    .tab-content {
        display: none;
    }
    .tab-content.active {
        display: block;
    }
    .btn-tab {
        padding: 8px 16px;
        border: 1px solid var(--border);
        background: var(--surface);
        border-radius: 99px;
        cursor: pointer;
        font-size: 0.9rem;
        transition: all 0.2s;
    }
    .btn-tab.active {
        background: var(--primary);
        color: white;
        border-color: var(--primary);
    }
</style>
@endpush

@section('content')
<div class="card">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Input Pembayaran</h2>
        <p style="color: var(--muted); margin: 0;">Cari santri dan pilih tagihan yang akan dibayarkan.</p>
    </div>

    <div class="row" style="display: flex; flex-wrap: wrap; gap: 24px;">
        <div style="flex: 1; min-width: 300px;">
            <!-- Main Tabs -->
            <div style="display: flex; gap: 10px; margin-bottom: 20px;">
                <button type="button" class="btn btn-tab active" onclick="switchMode('santri')" id="btn-mode-santri">Per Santri</button>
                <button type="button" class="btn btn-tab" onclick="switchMode('kelas')" id="btn-mode-kelas">Per Kelas</button>
                <button type="button" class="btn btn-tab" onclick="switchMode('verifikasi')" id="btn-mode-verifikasi">Verifikasi Pembayaran</button>
            </div>

            <!-- MODE: SANTRI -->
            <div id="mode-santri" class="mode-content">
                <!-- Search Santri -->
                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="santri_id" style="display: block; margin-bottom: 8px; font-weight: 600;">Cari Santri</label>
                    <select id="santri_id" class="form-control" style="width: 100%;">
                        <option value="">-- Pilih Santri --</option>
                        @foreach($santris as $santri)
                            <option value="{{ $santri->id }}" data-nis="{{ $santri->nis }}" data-kelas="{{ is_object($santri->kelas) ? ($santri->kelas->nama_kelas ?? '-') : ($santri->kelas ?? '-') }}">
                                {{ $santri->nama }} ({{ $santri->nis }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Tabs -->
                <div class="tabs">
                    <div class="tab-item active" data-tab="tagihan">Tagihan Belum Lunas</div>
                    <div class="tab-item" data-tab="riwayat">Riwayat Pembayaran</div>
                </div>

                <!-- List Tagihan -->
                <div id="tagihan-tab" class="tab-content active">
                    <div id="tagihan-container">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <p>Silakan pilih santri terlebih dahulu untuk melihat tagihan.</p>
                        </div>
                    </div>
                </div>

                <!-- List Riwayat -->
                <div id="riwayat-tab" class="tab-content">
                    <div id="riwayat-container">
                        <div class="empty-state">
                            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5;">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <p>Silakan pilih santri terlebih dahulu untuk melihat riwayat.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- MODE: KELAS -->
            <div id="mode-kelas" class="mode-content" style="display: none;">
                <div class="form-group" style="margin-bottom: 24px;">
                    <label for="kelas_id" style="display: block; margin-bottom: 8px; font-weight: 600;">Pilih Kelas</label>
                    <select id="kelas_id" class="form-control" style="width: 100%;">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }} ({{ $k->tingkatan }})</option>
                        @endforeach
                    </select>
                </div>
                <div id="kelas-container">
                    <div class="empty-state">
                        <p>Silakan pilih kelas untuk memuat tagihan.</p>
                    </div>
                </div>
            </div>

            <!-- MODE: VERIFIKASI -->
            <div id="mode-verifikasi" class="mode-content" style="display: none;">
                <div style="margin-bottom: 16px; display: flex; justify-content: space-between; align-items: center;">
                    <h3 style="font-size: 1rem; margin: 0;">Menunggu Konfirmasi Pembayaran</h3>
                    <button type="button" class="btn btn-sm btn-secondary" onclick="fetchPending()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21.5 2v6h-6M2.5 22v-6h6M2 11.5a10 10 0 0 1 18.8-4.3M22 12.5a10 10 0 0 1-18.8 4.3"/></svg>
                        Refresh
                    </button>
                </div>
                <div id="verifikasi-container">
                    <div class="empty-state">
                        <p>Memuat data...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary & Action -->
        <div style="width: 350px;">
            <div class="summary-card">
                <h3 style="margin: 0 0 16px; font-size: 1.1rem; font-weight: 600;">Ringkasan Pembayaran</h3>
                
                <form action="{{ route('pembayaran.store') }}" method="POST" id="payment-form">
                    @csrf
                    <div id="selected-bills-input"></div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: var(--muted);">Total Item</span>
                        <span style="font-weight: 600;" id="total-items">0</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 24px; padding-top: 12px; border-top: 1px dashed var(--border);">
                        <span style="font-weight: 600;">Total Bayar</span>
                        <span style="font-weight: 700; color: var(--primary); font-size: 1.25rem;" id="total-amount">Rp 0</span>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" id="btn-pay" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        Proses Pembayaran
                    </button>
                </form>
            </div>

            <!-- Santri Info Card (Hidden initially) -->
            <div id="santri-info" class="card" style="margin-top: 24px; display: none; background: #f8fafc;">
                <h4 style="margin: 0 0 12px; font-size: 1rem; font-weight: 600;">Data Santri</h4>
                <div style="font-size: 0.9rem;">
                    <p style="margin-bottom: 8px;"><strong>Nama:</strong> <span id="info-nama">-</span></p>
                    <p style="margin-bottom: 8px;"><strong>NIS:</strong> <span id="info-nis">-</span></p>
                    <p style="margin-bottom: 0;"><strong>Kelas:</strong> <span id="info-kelas">-</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#santri_id').select2({
            placeholder: "Cari nama atau NIS santri...",
            allowClear: true
        });

        $('#kelas_id').select2({
            placeholder: "Pilih Kelas...",
            allowClear: true
        });

        // Switch Mode
        window.switchMode = function(mode) {
            $('.mode-content').hide();
            $('#mode-' + mode).show();
            $('.btn-tab').removeClass('active');
            $('#btn-mode-' + mode).addClass('active');

            if (mode === 'santri') {
                $('#santri_id').val('').trigger('change');
            } else if (mode === 'kelas') {
                $('#kelas_id').val('').trigger('change');
            } else if (mode === 'verifikasi') {
                fetchPending();
            }
            
            resetSummary();
        }

        // Handle Kelas Selection
        $('#kelas_id').on('change', function() {
            const kelasId = $(this).val();
            if (kelasId) {
                fetchByClass(kelasId);
            } else {
                $('#kelas-container').html('<div class="empty-state"><p>Silakan pilih kelas untuk memuat tagihan.</p></div>');
            }
        });

        // Handle Santri Selection
        $('#santri_id').on('change', function() {
            const santriId = $(this).val();
            const option = $(this).find(':selected');
            
            if (santriId) {
                // Show Santri Info
                $('#info-nama').text(option.text().split('(')[0].trim());
                $('#info-nis').text(option.data('nis'));
                $('#info-kelas').text(option.data('kelas'));
                $('#santri-info').slideDown();

                // Fetch Data
                fetchTagihan(santriId);
                fetchHistory(santriId);
            } else {
                $('#santri-info').slideUp();
                resetTagihan();
                resetHistory();
            }
        });

        // Handle Tabs
        $('.tab-item').on('click', function() {
            $('.tab-item').removeClass('active');
            $(this).addClass('active');
            
            const tabId = $(this).data('tab');
            $('.tab-content').removeClass('active');
            $('#' + tabId + '-tab').addClass('active');
        });

        window.fetchPending = function() {
            const container = $('#verifikasi-container');
            container.html('<div style="text-align: center; padding: 20px;">Loading...</div>');

            $.get(`{{ url('admin/pembayaran/get-pending') }}`)
                .done(function(data) {
                    renderBills(data, container, true);
                })
                .fail(function() {
                    container.html('<div style="color: var(--danger); text-align: center;">Gagal memuat data.</div>');
                });
        }

        window.fetchByClass = function(kelasId) {
            const container = $('#kelas-container');
            container.html('<div style="text-align: center; padding: 20px;">Loading...</div>');

            $.get(`{{ url('admin/pembayaran/get-by-class') }}/${kelasId}`)
                .done(function(data) {
                    renderBills(data, container, true); // Use true to show Santri Name
                })
                .fail(function() {
                    container.html('<div style="color: var(--danger); text-align: center;">Gagal memuat data kelas.</div>');
                });
        }

        function renderBills(data, container, showSantri = false) {
            if (!data || data.length === 0) {
                container.html(`
                    <div class="empty-state">
                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5; color: var(--success);">
                            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                            <polyline points="22 4 12 14.01 9 11.01"></polyline>
                        </svg>
                        <p>Tidak ada tagihan yang ditemukan.</p>
                    </div>
                `);
            } else {
                try {
                    let html = '';
                    let currentSantriId = null;

                    data.forEach(item => {
                        // If showSantri is true, group by santri visually
                        let santriHeader = '';
                        if (showSantri && item.santri_id !== currentSantriId) {
                            currentSantriId = item.santri_id;
                            const santriName = item.santri ? item.santri.nama : 'Santri tidak ditemukan';
                            const santriNis = item.santri ? item.santri.nis : '-';
                            santriHeader = `<div style="background: #e2e8f0; padding: 8px 12px; font-weight: 600; font-size: 0.9rem; margin-top: 10px; border-radius: 4px;">${santriName} (${santriNis})</div>`;
                        }

                        const namaTarif = item.tarif ? item.tarif.nama_tarif : 'Tarif tidak tersedia';
                        let statusBadge = '';
                        let buktiLink = '';

                        if (item.status === 'menunggu_konfirmasi') {
                            statusBadge = '<span style="font-size: 0.75rem; background: #fff7ed; color: #c2410c; padding: 2px 8px; border-radius: 99px; border: 1px solid #fed7aa; margin-left: 8px; display: inline-block;">Menunggu Konfirmasi</span>';
                            
                            if (item.bukti_bayar) {
                                buktiLink = `<div style="margin-top: 4px;"><a href="/assets-file/${item.bukti_bayar}" target="_blank" onclick="event.stopPropagation()" style="font-size: 0.85rem; color: var(--primary); display: flex; align-items: center; gap: 4px; text-decoration: none;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                                    Lihat Bukti Bayar
                                </a></div>`;
                            }
                        }

                        const formattedAmount = new Intl.NumberFormat('id-ID').format(item.jumlah || 0);
                        const monthName = formatMonth(item.bulan || '-');

                        html += santriHeader + `
                            <div class="bill-card" onclick="toggleBill(this)" data-id="${item.id}" data-amount="${item.jumlah}">
                                <div class="bill-info">
                                    <div style="display: flex; align-items: center; margin-bottom: 4px;">
                                        <h4 style="margin: 0;">${namaTarif}</h4>
                                        ${statusBadge}
                                    </div>
                                    <p>${monthName} ${item.tahun}</p>
                                    ${buktiLink}
                                </div>
                                <div class="bill-amount">
                                    Rp ${formattedAmount}
                                </div>
                            </div>
                        `;
                    });
                    container.html(html);
                } catch (e) {
                    console.error('Error rendering bills:', e);
                    container.html('<div style="color: var(--danger); text-align: center;">Terjadi kesalahan saat menampilkan data. Cek konsol browser.</div>');
                }
            }
        }

        function fetchHistory(id) {
            const container = $('#riwayat-container');
            container.html('<div style="text-align: center; padding: 20px;">Loading...</div>');

            $.get(`{{ url('admin/pembayaran/get-riwayat') }}/${id}`)
                .done(function(data) {
                    if (!data || data.length === 0) {
                        container.html(`
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5; color: var(--success);">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <p>Belum ada riwayat pembayaran.</p>
                            </div>
                        `);
                    } else {
                        try {
                            let html = '';
                            data.forEach(item => {
                                const date = new Date(item.updated_at);
                                const formattedDate = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                                const namaTarif = item.tarif ? item.tarif.nama_tarif : 'Tarif tidak tersedia';
                                const formattedAmount = new Intl.NumberFormat('id-ID').format(item.jumlah || 0);
                                const monthName = formatMonth(item.bulan || '-');
                                
                                html += `
                                    <div class="history-card">
                                        <div class="history-info">
                                            <h4 style="margin: 0 0 4px; font-size: 1rem; color: var(--text);">${namaTarif}</h4>
                                            <p style="margin: 0; color: var(--muted); font-size: 0.9rem;">${monthName} ${item.tahun}</p>
                                        </div>
                                        <div>
                                            <div class="history-amount">
                                                Rp ${formattedAmount}
                                            </div>
                                            <div class="history-date">
                                                Dibayar: ${formattedDate}
                                            </div>
                                        </div>
                                    </div>
                                `;
                            });
                            container.html(html);
                        } catch (e) {
                            console.error('Error rendering history:', e);
                            container.html('<div style="color: var(--danger); text-align: center;">Gagal memuat riwayat.</div>');
                        }
                    }
                })
                .fail(function() {
                    container.html('<div style="color: var(--danger); text-align: center;">Gagal memuat data riwayat.</div>');
                });
        }

        function fetchTagihan(id) {
            const container = $('#tagihan-container');
            container.html('<div style="text-align: center; padding: 20px;">Loading...</div>');

            $.get(`{{ url('admin/pembayaran/get-tagihan') }}/${id}`)
                .done(function(data) {
                    console.log('Tagihan data:', data);
                    renderBills(data, container, false);
                    resetSummary();
                })
                .fail(function(jqXHR, textStatus, errorThrown) {
                    console.error('Failed to fetch tagihan:', textStatus, errorThrown);
                    container.html('<div style="color: var(--danger); text-align: center;">Gagal memuat data tagihan.</div>');
                });
        }

        window.toggleBill = function(element) {
            $(element).toggleClass('selected');
            calculateTotal();
        }

        function calculateTotal() {
            let total = 0;
            let count = 0;
            let inputsHtml = '';

            $('.bill-card.selected').each(function() {
                const id = $(this).data('id');
                const amount = parseFloat($(this).data('amount'));
                
                total += amount;
                count++;
                inputsHtml += `<input type="hidden" name="tagihan_ids[]" value="${id}">`;
            });

            $('#selected-bills-input').html(inputsHtml);
            $('#total-items').text(count);
            $('#total-amount').text('Rp ' + new Intl.NumberFormat('id-ID').format(total));
            
            if (count > 0) {
                $('#btn-pay').removeAttr('disabled');
            } else {
                $('#btn-pay').attr('disabled', 'disabled');
            }
        }

        function resetTagihan() {
            $('#tagihan-container').html(`
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <p>Silakan pilih santri terlebih dahulu untuk melihat tagihan.</p>
                </div>
            `);
            resetSummary();
        }

        function resetHistory() {
            $('#riwayat-container').html(`
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <p>Silakan pilih santri terlebih dahulu untuk melihat riwayat.</p>
                </div>
            `);
        }

        function resetSummary() {
            $('.bill-card').removeClass('selected');
            $('#selected-bills-input').html('');
            $('#total-items').text('0');
            $('#total-amount').text('Rp 0');
            $('#btn-pay').attr('disabled', 'disabled');
        }

        // Helper for month name
        function formatMonth(month) {
            const months = {
                'Januari': 'Januari', 'Februari': 'Februari', 'Maret': 'Maret', 'April': 'April',
                'Mei': 'Mei', 'Juni': 'Juni', 'Juli': 'Juli', 'Agustus': 'Agustus',
                'September': 'September', 'Oktober': 'Oktober', 'November': 'November', 'Desember': 'Desember',
                '01': 'Januari', '02': 'Februari', '03': 'Maret', '04': 'April',
                '05': 'Mei', '06': 'Juni', '07': 'Juli', '08': 'Agustus',
                '09': 'September', '10': 'Oktober', '11': 'November', '12': 'Desember'
            };
            return months[month] || month;
        }
    });
</script>
@endpush
