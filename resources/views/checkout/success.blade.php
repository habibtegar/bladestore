@extends('layouts.app')

@section('title', 'Pesanan #' . $order->order_code . ' — BladeStore')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-lg-8">
        <!-- Success Banner Card -->
        <div class="card border-0 bg-white p-4 p-md-5 text-center mb-4 rounded-4 shadow-sm">
            @if(in_array($order->status, ['processing', 'dikemas', 'shipped', 'dikirim', 'completed', 'selesai']))
                <div class="mb-3">
                    <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: var(--success-subtle); color: var(--success-color);">
                        <i class="bi bi-check-circle-fill display-5 text-success"></i>
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-1">Pembayaran Berhasil Diverifikasi</h3>
                <p class="text-muted small mb-3">Terima kasih! Pembayaran Anda sebesar <strong>{{ $order->formatted_total_price }}</strong> telah kami terima. Pesanan Anda saat ini sedang dikemas oleh toko.</p>
            @elseif(in_array($order->status, ['pending', 'belum_bayar']))
                <div class="mb-3">
                    <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #FEF3C7; color: #D97706;">
                        <i class="bi bi-hourglass-split display-5"></i>
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-1">Pesanan Dibuat — Menunggu Pembayaran</h3>
                <p class="text-muted small mb-3">Pesanan Anda telah dicatat oleh sistem dengan status <strong class="text-warning">Belum Bayar</strong>. Segera selesaikan pembayaran agar pesanan dapat diproses.</p>
            @else
                <div class="mb-3">
                    <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: #FEE2E2; color: #DC2626;">
                        <i class="bi bi-x-circle-fill display-5 text-danger"></i>
                    </span>
                </div>
                <h3 class="fw-bold text-dark mb-1">Status Pesanan: {{ $order->status_label }}</h3>
                <p class="text-muted small mb-3">Pesanan ini telah dibatalkan atau melewati batas waktu pembayaran.</p>
            @endif

            <div class="d-inline-flex align-items-center justify-content-center gap-2 p-2 px-3 rounded-pill bg-light border mx-auto mb-3">
                <span class="text-muted x-small">Kode Pesanan:</span>
                <span class="fw-bold text-dark font-monospace fs-6">{{ $order->order_code }}</span>
                <button class="btn btn-sm btn-link text-decoration-none p-0 text-primary" onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode pesanan {{ $order->order_code }} berhasil disalin!');" title="Salin Kode">
                    <i class="bi bi-clipboard"></i>
                </button>
            </div>

            <div class="d-flex justify-content-center">
                <span class="badge {{ $order->status_badge_class }} px-3 py-2 fs-6">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>

        @if($order->canBePaid())
            <!-- Action Card: Bayar Sekarang (HANYA MUNCUL JIKA BELUM BAYAR) -->
            <div class="card border-0 p-4 mb-4 rounded-4 shadow-sm text-center" style="background: linear-gradient(135deg, #FFFBEB 0%, #FEF3C7 100%); border: 1px solid #FDE68A !important;">
                <div class="mb-2">
                    <span class="badge bg-warning text-dark rounded-pill px-3 py-1.5 x-small fw-bold">
                        <i class="bi bi-exclamation-circle-fill me-1"></i> Menunggu Pembayaran
                    </span>
                </div>
                <h4 class="fw-bold text-dark mb-1">Selesaikan Pembayaran Sekarang</h4>
                <p class="text-muted small mb-3 mx-auto" style="max-width: 520px;">
                    Klik tombol di bawah ini untuk membuka popup pembayaran aman melalui Midtrans (QRIS, Virtual Account Bank, GoPay, dan E-Wallet lainnya).
                </p>
                <div class="d-flex justify-content-center align-items-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary btn-lg px-4 px-sm-5 py-3 fw-bold rounded-3 shadow d-inline-flex align-items-center justify-content-center gap-2 btn-pay-now">
                        <i class="bi bi-credit-card-2-front-fill fs-5"></i>
                        <span>Bayar Sekarang ({{ $order->formatted_total_price }})</span>
                        <i class="bi bi-arrow-right fs-5"></i>
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg px-3 py-3 fw-semibold rounded-3 d-inline-flex align-items-center gap-1.5 btn-sync-status" title="Periksa status jika baru saja membayar di simulator/tab lain">
                        <i class="bi bi-arrow-repeat fs-5"></i>
                        <span>Cek Status</span>
                    </button>
                </div>
                <div class="x-small text-muted mt-2.5">
                    <i class="bi bi-clock-history me-1"></i> Batas waktu pembayaran 24 jam. Jika sudah membayar di simulator, klik tombol "Cek Status" untuk verifikasi instan.
                </div>
            </div>
        @endif

        @if(in_array($order->status, ['processing', 'dikemas', 'shipped', 'dikirim', 'completed', 'selesai']))
            <!-- Informasi Pembayaran Lunas (Hapus instruksi bayar karena sudah bayar) -->
            <div class="card border-0 bg-white p-4 mb-4 rounded-3 shadow-sm">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-shield-check text-success"></i> Konfirmasi Pembayaran
                </h5>
                <div class="alert alert-success border-0 mb-0 rounded-3">
                    <div class="d-flex gap-2">
                        <i class="bi bi-check-circle-fill fs-5 mt-1"></i>
                        <div>
                            <strong>Pembayaran Telah Dikonfirmasi (Lunas)</strong>
                            <p class="mb-0 small mt-1">
                                Transaksi sebesar <strong>{{ $order->formatted_total_price }}</strong> telah lunas melalui <strong>{{ $order->payment_method }}</strong>. Anda tidak perlu melakukan pembayaran lagi. Pesanan Anda saat ini sedang dikemas dan dipersiapkan untuk pengiriman.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <!-- Payment Instructions Box (HANYA TAMPIL JIKA BELUM BAYAR) -->
            <div class="card border-0 bg-white p-4 mb-4 rounded-3 shadow-sm">
                <h5 class="fw-bold text-dark mb-3 d-flex align-items-center gap-2">
                    <i class="bi bi-wallet2 text-primary"></i> Instruksi Pembayaran: {{ $order->payment_method }}
                </h5>

                @if($order->payment_method === 'Transfer Bank Manual')
                    <div class="p-3 rounded-3 bg-light border mb-4">
                        <div class="d-flex gap-2">
                            <i class="bi bi-info-circle-fill text-primary fs-5 mt-1"></i>
                            <div>
                                <div class="small text-muted">Silakan lakukan transfer tepat sebesar:</div>
                                <strong class="text-primary fs-4 d-block">{{ $order->formatted_total_price }}</strong>
                                <div class="x-small text-muted mt-1">Pilih salah satu nomor rekening bank resmi berikut ini:</div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <!-- Bank BCA -->
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-white border h-100 position-relative shadow-sm">
                                <span class="badge bg-primary mb-2">BCA</span>
                                <div class="fw-bold font-monospace fs-6 text-dark">123-456-7890</div>
                                <div class="x-small text-muted mb-2">a.n. PT BladeStore Indonesia</div>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 x-small" onclick="navigator.clipboard.writeText('1234567890'); alert('Nomor rekening BCA berhasil disalin!');">
                                    <i class="bi bi-copy me-1"></i> Salin No. Rek
                                </button>
                            </div>
                        </div>
                        <!-- Bank Mandiri -->
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-white border h-100 position-relative shadow-sm">
                                <span class="badge bg-primary mb-2">MANDIRI</span>
                                <div class="fw-bold font-monospace fs-6 text-dark">987-654-3210</div>
                                <div class="x-small text-muted mb-2">a.n. PT BladeStore Indonesia</div>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 x-small" onclick="navigator.clipboard.writeText('9876543210'); alert('Nomor rekening Mandiri berhasil disalin!');">
                                    <i class="bi bi-copy me-1"></i> Salin No. Rek
                                </button>
                            </div>
                        </div>
                        <!-- Bank BRI -->
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-white border h-100 position-relative shadow-sm">
                                <span class="badge bg-primary mb-2">BRI</span>
                                <div class="fw-bold font-monospace fs-6 text-dark">0123-01-0001-501</div>
                                <div class="x-small text-muted mb-2">a.n. PT BladeStore Indonesia</div>
                                <button class="btn btn-sm btn-outline-secondary py-0 px-2 x-small" onclick="navigator.clipboard.writeText('0123010001501'); alert('Nomor rekening BRI berhasil disalin!');">
                                    <i class="bi bi-copy me-1"></i> Salin No. Rek
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light border d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
                        <div>
                            <div class="fw-bold text-dark small">Sudah Melakukan Transfer?</div>
                            <div class="x-small text-muted">Kirimkan foto atau screenshot bukti transfer beserta kode pesanan Anda untuk verifikasi instan.</div>
                        </div>
                        <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin BladeStore, saya ingin konfirmasi pembayaran untuk pesanan ' . $order->order_code . ' sebesar ' . $order->formatted_total_price) }}" 
                           target="_blank" 
                           rel="noopener noreferrer"
                           class="btn btn-success btn-sm d-flex align-items-center gap-2 text-nowrap px-3 py-2 fw-semibold">
                            <i class="bi bi-whatsapp"></i> Konfirmasi WhatsApp
                        </a>
                    </div>
                @elseif(str_contains(strtolower($order->payment_method), 'cod') || str_contains(strtolower($order->payment_method), 'delivery'))
                    <!-- COD Instructions -->
                    <div class="alert alert-success border-0 mb-3 rounded-3">
                        <div class="d-flex gap-2">
                            <i class="bi bi-cash-coin fs-5 mt-1"></i>
                            <div>
                                <strong>Metode Cash on Delivery (COD) Dipilih</strong>
                                <p class="mb-0 small mt-1">
                                    Anda tidak perlu membayar secara online saat ini. Silakan siapkan uang pas sebesar 
                                    <strong class="text-dark">{{ $order->formatted_total_price }}</strong> 
                                    saat kurir mengantarkan paket pesanan ke alamat Anda.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="x-small text-muted">
                        <i class="bi bi-telephone me-1"></i> Kurir kami akan mengonfirmasi via WhatsApp atau telepon ke nomor <strong>{{ $order->customer_phone }}</strong> sebelum pengiriman dilakukan.
                    </div>
                @else
                    <!-- Online Payment Instructions (QRIS, E-Wallet, etc.) -->
                    <div class="alert alert-info border-0 mb-0 rounded-3">
                        <div class="d-flex gap-2">
                            <i class="bi bi-qr-code-scan fs-5 mt-1"></i>
                            <div>
                                <strong>Pembayaran Online Instan ({{ $order->payment_method }})</strong>
                                <p class="mb-0 small mt-1">
                                    Silakan klik tombol <strong>"Bayar Sekarang"</strong> di atas untuk memunculkan kode QRIS / instruksi pembayaran melalui popup Midtrans.
                                </p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Order Items Detail Card -->
        <div class="card border-0 bg-white p-4 mb-4 rounded-3 shadow-sm">
            <h5 class="fw-bold text-dark mb-3">Rincian Pembelian</h5>
            
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light small text-secondary">
                        <tr>
                            <th>Produk</th>
                            <th class="text-center">Harga</th>
                            <th class="text-center">Jumlah</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div style="width: 48px; height: 60px; flex-shrink: 0; background-color: #F3F4F6; border-radius: 6px; overflow: hidden;" class="border">
                                            <img src="{{ optional($item->product)->image_url ?? \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}"
                                                 class="w-100 h-100 object-fit-cover"
                                                 alt="{{ $item->product->name ?? 'Produk' }}"
                                                 onerror="this.onerror=null; this.src='{{ \App\Models\Product::DEFAULT_FALLBACK_IMAGE }}';">
                                        </div>
                                        <div>
                                            <h6 class="mb-0 fw-semibold text-dark small">{{ $item->product->name ?? 'Produk' }}</h6>
                                            <span class="x-small text-muted">Kategori: {{ optional($item->product)->category_name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-center small">
                                    Rp {{ number_format((float)$item->price, 0, ',', '.') }}
                                </td>
                                <td class="text-center small">
                                    {{ $item->quantity }}x
                                </td>
                                <td class="text-end fw-semibold text-primary small">
                                    Rp {{ number_format((float)$item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="3" class="text-end text-muted small pt-3 border-0">Subtotal Produk:</td>
                            <td class="text-end fw-semibold text-dark small pt-3 border-0">{{ $order->formatted_total_price }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted small border-0">Ongkos Kirim:</td>
                            <td class="text-end fw-semibold text-success small border-0">Gratis</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold text-dark fs-6 pt-2 border-0">Total Tagihan:</td>
                            <td class="text-end fw-bold text-primary fs-5 pt-2 border-0">{{ $order->formatted_total_price }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Shipping Details -->
            <div class="border-top pt-3 mt-3">
                <div class="row g-3 x-small">
                    <div class="col-sm-6">
                        <span class="text-muted d-block">Alamat Pengiriman:</span>
                        <span class="fw-semibold text-dark">{{ $order->shipping_address }}</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="text-muted d-block">Nomor Telepon:</span>
                        <span class="fw-semibold text-dark">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="col-sm-3">
                        <span class="text-muted d-block">Waktu Pemesanan:</span>
                        <span class="fw-semibold text-dark">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>
                    @if($order->notes)
                        <div class="col-12">
                            <span class="text-muted d-block">Catatan Pengiriman:</span>
                            <span class="fst-italic text-secondary">{{ $order->notes }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-center gap-3">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-receipt me-1"></i> Pesanan Saya
            </a>
            <div class="d-flex align-items-center gap-2">
                @if($order->canBePaid())
                    <button type="button" class="btn btn-primary fw-bold shadow-sm d-inline-flex align-items-center gap-1.5 btn-pay-now">
                        <i class="bi bi-credit-card-2-front"></i>
                        <span>Bayar Sekarang</span>
                    </button>
                @endif
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary px-4">
                    <i class="bi bi-bag me-1"></i> Lanjut Belanja
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if($order->canBePaid())
    <script src="{{ config('midtrans.snap_url', 'https://app.sandbox.midtrans.com/snap/snap.js') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const snapToken = @json($order->snap_token);
            const orderId = @json($order->id);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            function syncAndReload() {
                fetch('/orders/' + orderId + '/sync-status', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) { return res.json(); })
                .finally(function() {
                    window.location.reload();
                });
            }

            document.querySelectorAll('.btn-sync-status').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    btn.disabled = true;
                    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Mengecek...';
                    syncAndReload();
                });
            });

            function triggerSnapPayment() {
                if (snapToken) {
                    window.snap.pay(snapToken, {
                        onSuccess: function(result) {
                            syncAndReload();
                        },
                        onPending: function(result) {
                            syncAndReload();
                        },
                        onError: function(result) {
                            alert('Pembayaran gagal atau terjadi kesalahan.');
                        },
                        onClose: function() {
                            syncAndReload();
                        }
                    });
                } else {
                    // Minta token baru ke controller jika belum tersedia
                    fetch('{{ route("orders.snap-token", $order->id) }}', {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if (data.success && data.snap_token) {
                            window.snap.pay(data.snap_token, {
                                onSuccess: function() { syncAndReload(); },
                                onPending: function() { syncAndReload(); },
                                onError: function() { alert('Pembayaran gagal.'); },
                                onClose: function() { syncAndReload(); }
                            });
                        } else {
                            alert(data.message || 'Gagal memuat popup pembayaran Midtrans.');
                        }
                    })
                    .catch(function(err) {
                        console.error('Snap error:', err);
                        alert('Terjadi kesalahan saat memuat popup pembayaran.');
                    });
                }
            }

            document.querySelectorAll('.btn-pay-now').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    triggerSnapPayment();
                });
            });
        });
    </script>
@endif
@endpush
