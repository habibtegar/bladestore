@extends('layouts.app')

@section('title', 'Pesanan Berhasil #' . $order->order_code . ' — LaraShop')

@section('content')
<div class="row justify-content-center mb-5">
    <div class="col-lg-8">
        <!-- Success Banner Card -->
        <div class="card border-0 bg-white p-4 p-md-5 text-center mb-4 rounded-4 shadow-sm">
            <div class="mb-3">
                <span class="p-3 rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 72px; height: 72px; background-color: var(--success-subtle); color: var(--success-color);">
                    <i class="bi bi-check-lg display-5"></i>
                </span>
            </div>
            
            <h3 class="fw-bold text-dark mb-1">Pesanan Anda Berhasil Dibuat</h3>
            <p class="text-muted small mb-3">Terima kasih atas kepercayaan Anda berbelanja di LaraShop. Data transaksi telah tercatat rapi di sistem kami.</p>

            <div class="d-inline-flex align-items-center justify-content-center gap-2 p-2 px-3 rounded-pill bg-light border mx-auto mb-3">
                <span class="text-muted x-small">Kode Pesanan:</span>
                <span class="fw-bold text-dark font-monospace fs-6">{{ $order->order_code }}</span>
                <button class="btn btn-sm btn-link text-decoration-none p-0 text-primary" onclick="navigator.clipboard.writeText('{{ $order->order_code }}'); alert('Kode pesanan {{ $order->order_code }} berhasil disalin!');" title="Salin Kode">
                    <i class="bi bi-clipboard"></i>
                </button>
            </div>

            <div class="d-flex justify-content-center">
                <span class="badge {{ $order->status_badge_class }} px-3 py-2">
                    {{ $order->status_label }}
                </span>
            </div>
        </div>

        <!-- Payment Instructions Box -->
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
                            <div class="x-small text-muted mb-2">a.n. PT LaraShop Indonesia</div>
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
                            <div class="x-small text-muted mb-2">a.n. PT LaraShop Indonesia</div>
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
                            <div class="x-small text-muted mb-2">a.n. PT LaraShop Indonesia</div>
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
                    <a href="https://wa.me/6281234567890?text={{ urlencode('Halo Admin LaraShop, saya ingin konfirmasi pembayaran untuk pesanan ' . $order->order_code . ' sebesar ' . $order->formatted_total_price) }}" 
                       target="_blank" 
                       rel="noopener noreferrer"
                       class="btn btn-success btn-sm d-flex align-items-center gap-2 text-nowrap px-3 py-2 fw-semibold">
                        <i class="bi bi-whatsapp"></i> Konfirmasi WhatsApp
                    </a>
                </div>
            @else
                <!-- COD Instructions -->
                <div class="alert alert-success border-0 mb-3 rounded-3">
                    <div class="d-flex gap-2">
                        <i class="bi bi-check-circle-fill fs-5 mt-1"></i>
                        <div>
                            <strong>Metode Cash on Delivery (COD) Dipilih</strong>
                            <p class="mb-0 small mt-1">
                                Anda tidak perlu membayar saat ini. Silakan siapkan uang pas sebesar 
                                <strong class="text-dark">{{ $order->formatted_total_price }}</strong> 
                                saat kurir mengantarkan paket pesanan ke alamat Anda.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="x-small text-muted">
                    <i class="bi bi-telephone me-1"></i> Kurir kami akan mengonfirmasi via WhatsApp atau telepon ke nomor <strong>{{ $order->customer_phone }}</strong> sebelum pengiriman dilakukan.
                </div>
            @endif
        </div>

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
                                        <span class="fw-semibold text-dark small">{{ $item->product->name ?? 'Produk' }}</span>
                                    </div>
                                </td>
                                <td class="text-center text-nowrap small text-secondary">{{ $item->formatted_price }}</td>
                                <td class="text-center small">{{ $item->quantity }}x</td>
                                <td class="text-end fw-semibold text-nowrap small text-primary">{{ $item->formatted_subtotal }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="border-top">
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Subtotal Produk:</td>
                            <td class="text-end fw-semibold small">{{ $order->formatted_total_price }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end text-muted small">Ongkos Kirim:</td>
                            <td class="text-end text-success fw-bold small">Gratis</td>
                        </tr>
                        <tr class="table-light">
                            <td colspan="3" class="text-end fw-bold">Total Tagihan:</td>
                            <td class="text-end fw-bold text-primary fs-5">{{ $order->formatted_total_price }}</td>
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
        <div class="d-flex justify-content-between align-items-center">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-receipt me-1"></i> Pesanan Saya
            </a>
            <a href="{{ route('products.index') }}" class="btn btn-primary px-4">
                <i class="bi bi-bag me-1"></i> Lanjut Belanja
            </a>
        </div>
    </div>
</div>
@endsection
