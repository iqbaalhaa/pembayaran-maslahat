@extends('layouts.master')
@section('title', 'Cetak Kwitansi')

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
    .history-card {
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
    .history-card:hover {
        border-color: var(--primary);
        background: #f0f9ff;
    }
    .history-card.selected {
        border-color: var(--primary);
        background: #eff6ff;
        box-shadow: 0 0 0 1px var(--primary);
    }
    .history-info h4 {
        margin: 0 0 4px;
        font-size: 1rem;
        color: var(--text);
    }
    .history-info p {
        margin: 0;
        color: var(--muted);
        font-size: 0.9rem;
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
</style>
@endpush

@section('content')
<div class="card">
    <div style="margin-bottom: 24px;">
        <h2 style="font-size: 1.5rem; font-weight: 700; margin: 0 0 8px;">Cetak Kwitansi</h2>
        <p style="color: var(--muted); margin: 0;">Pilih santri dan riwayat pembayaran untuk dicetak.</p>
    </div>

    <div class="row" style="display: flex; flex-wrap: wrap; gap: 24px;">
        <div style="flex: 1; min-width: 300px;">
            <!-- Search Filter -->
            <div class="form-group" style="margin-bottom: 24px;">
                <div style="display: flex; gap: 10px; margin-bottom: 15px;">
                    <button type="button" class="btn btn-mode active" data-mode="santri" style="flex: 1; padding: 10px; border: 1px solid var(--primary); background: var(--primary); color: white; border-radius: 8px; cursor: pointer;">Per Santri</button>
                    <button type="button" class="btn btn-mode" data-mode="kelas" style="flex: 1; padding: 10px; border: 1px solid var(--border); background: var(--surface); color: var(--text); border-radius: 8px; cursor: pointer;">Per Kelas</button>
                </div>

                <div id="santri_search_container">
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

                <div id="kelas_search_container" style="display: none;">
                    <label for="kelas_id" style="display: block; margin-bottom: 8px; font-weight: 600;">Pilih Kelas</label>
                    <select id="kelas_id" class="form-control" style="width: 100%;">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                        @endforeach
                    </select>
                    
                    <div style="margin-top: 10px; display: flex; justify-content: flex-end;">
                        <button type="button" id="btn-select-all-class" class="btn btn-sm btn-outline-primary" style="display: none;">Pilih Semua</button>
                    </div>
                </div>
            </div>

            <!-- List Riwayat -->
            <div id="history-container">
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <p>Silakan pilih santri terlebih dahulu untuk melihat riwayat pembayaran.</p>
                </div>
            </div>
        </div>

        <!-- Summary & Action -->
        <div style="width: 350px;">
            <div class="summary-card">
                <h3 style="margin: 0 0 16px; font-size: 1.1rem; font-weight: 600;">Opsi Cetak</h3>
                
                <form action="{{ route('pembayaran.print') }}" method="POST" id="print-form" target="_blank">
                    @csrf
                    <div id="selected-bills-input"></div>

                    <div style="display: flex; justify-content: space-between; margin-bottom: 12px;">
                        <span style="color: var(--muted);">Item Terpilih</span>
                        <span style="font-weight: 600;" id="total-items">0</span>
                    </div>
                    
                    <div style="display: flex; justify-content: space-between; margin-bottom: 24px; padding-top: 12px; border-top: 1px dashed var(--border);">
                        <span style="font-weight: 600;">Total Nominal</span>
                        <span style="font-weight: 700; color: var(--success); font-size: 1.25rem;" id="total-amount">Rp 0</span>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center;" id="btn-print" disabled>
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M6 9V2h12v7"></path>
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                            <rect x="6" y="14" width="12" height="8"></rect>
                        </svg>
                        Cetak Kwitansi
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

        // Mode Toggling
        $('.btn-mode').on('click', function() {
            $('.btn-mode').removeClass('active').css({
                'background': 'var(--surface)', 
                'color': 'var(--text)',
                'border-color': 'var(--border)'
            });
            $(this).addClass('active').css({
                'background': 'var(--primary)', 
                'color': 'white',
                'border-color': 'var(--primary)'
            });
            
            const mode = $(this).data('mode');
            
            if (mode === 'santri') {
                $('#santri_search_container').show();
                $('#kelas_search_container').hide();
                $('#santri_id').val('').trigger('change');
            } else {
                $('#santri_search_container').hide();
                $('#kelas_search_container').show();
                $('#kelas_id').val('').trigger('change');
                $('#santri-info').slideUp();
            }
            
            resetHistory();
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

                // Fetch History
                fetchHistory(santriId);
            } else {
                $('#santri-info').slideUp();
                resetHistory();
            }
        });

        // Handle Kelas Selection
        $('#kelas_id').on('change', function() {
            const kelasId = $(this).val();
            
            if (kelasId) {
                $('#btn-select-all-class').show();
                fetchHistoryByClass(kelasId);
            } else {
                $('#btn-select-all-class').hide();
                resetHistory();
            }
        });

        // Handle Select All Class
        $('#btn-select-all-class').on('click', function() {
            const allSelected = $('.history-card').length === $('.history-card.selected').length;
            
            if (allSelected) {
                $('.history-card').removeClass('selected');
                $(this).text('Pilih Semua');
            } else {
                $('.history-card').addClass('selected');
                $(this).text('Batalkan Semua');
            }
            calculateTotal();
        });

        function fetchHistory(id) {
            const container = $('#history-container');
            container.html('<div style="text-align: center; padding: 20px;">Loading...</div>');

            $.get(`{{ url('admin/pembayaran/get-riwayat') }}/${id}`)
                .done(function(data) {
                    if (data.length === 0) {
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
                        let html = '';
                        data.forEach(item => {
                            const date = new Date(item.updated_at);
                            const formattedDate = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                            
                            html += `
                                <div class="history-card" onclick="toggleItem(this)" data-id="${item.id}" data-amount="${item.jumlah}">
                                    <div class="history-info">
                                        <h4>${item.tarif.nama_tarif}</h4>
                                        <p>${formatMonth(item.bulan)} ${item.tahun}</p>
                                    </div>
                                    <div>
                                        <div class="history-amount">
                                            Rp ${new Intl.NumberFormat('id-ID').format(item.jumlah)}
                                        </div>
                                        <div class="history-date">
                                            Dibayar: ${formattedDate}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        container.html(html);
                    }
                    resetSummary();
                })
                .fail(function() {
                    container.html('<div style="color: var(--danger); text-align: center;">Gagal memuat data riwayat.</div>');
                });
        }

        function fetchHistoryByClass(id) {
            const container = $('#history-container');
            container.html('<div style="text-align: center; padding: 20px;">Loading...</div>');

            $.get(`{{ url('admin/pembayaran/get-history-by-class') }}/${id}`)
                .done(function(data) {
                    if (data.length === 0) {
                        container.html(`
                            <div class="empty-state">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5; color: var(--success);">
                                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                </svg>
                                <p>Belum ada riwayat pembayaran untuk kelas ini.</p>
                            </div>
                        `);
                    } else {
                        let html = '';
                        data.forEach(item => {
                            const date = new Date(item.updated_at);
                            const formattedDate = date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
                            
                            html += `
                                <div class="history-card" onclick="toggleItem(this)" data-id="${item.id}" data-amount="${item.jumlah}">
                                    <div class="history-info">
                                        <h4>${item.santri.nama} (${item.santri.nis})</h4>
                                        <p>${item.tarif.nama_tarif} - ${formatMonth(item.bulan)} ${item.tahun}</p>
                                    </div>
                                    <div>
                                        <div class="history-amount">
                                            Rp ${new Intl.NumberFormat('id-ID').format(item.jumlah)}
                                        </div>
                                        <div class="history-date">
                                            ${formattedDate}
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        container.html(html);
                    }
                    resetSummary();
                })
                .fail(function() {
                    container.html('<div style="color: var(--danger); text-align: center;">Gagal memuat data riwayat.</div>');
                });
        }

        window.toggleItem = function(element) {
            $(element).toggleClass('selected');
            calculateTotal();
            
            // Update Select All Button text if needed
            if ($('#kelas_search_container').is(':visible')) {
                const total = $('.history-card').length;
                const selected = $('.history-card.selected').length;
                if (total === selected && total > 0) {
                    $('#btn-select-all-class').text('Batalkan Semua');
                } else {
                    $('#btn-select-all-class').text('Pilih Semua');
                }
            }
        }

        function calculateTotal() {
            let total = 0;
            let count = 0;
            let inputsHtml = '';

            $('.history-card.selected').each(function() {
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
                $('#btn-print').removeAttr('disabled');
            } else {
                $('#btn-print').attr('disabled', 'disabled');
            }
        }

        function resetHistory() {
            $('#history-container').html(`
                <div class="empty-state">
                    <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" stroke-linecap="round" stroke-linejoin="round" style="margin-bottom: 12px; opacity: 0.5;">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <line x1="16" y1="13" x2="8" y2="13"></line>
                        <line x1="16" y1="17" x2="8" y2="17"></line>
                        <polyline points="10 9 9 9 8 9"></polyline>
                    </svg>
                    <p>Silakan pilih santri atau kelas untuk melihat riwayat pembayaran.</p>
                </div>
            `);
            resetSummary();
            $('#btn-select-all-class').hide();
        }

        function resetSummary() {
            $('#selected-bills-input').html('');
            $('#total-items').text('0');
            $('#total-amount').text('Rp 0');
            $('#btn-print').attr('disabled', 'disabled');
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
