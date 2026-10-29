@extends('layouts.app')

@section('title', 'Pembayaran pesanan')

@section('content')
<section class="page-heading">
    <div><span class="eyebrow">PEMBAYARAN SISWA</span><h1>Tagihan pesananmu.</h1><p>Bayar dengan QRIS, lalu tunjukkan bukti pembayaran ke kasir untuk dikonfirmasi.</p></div>
    <div class="dashboard-stats"><span><strong>{{ $orders->count() }}</strong> tagihan</span><span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong> total</span></div>
</section>

<section class="split-section payment-section">
    <div class="panel">
        <div class="panel-heading"><div><span class="eyebrow">BELUM LUNAS</span><h2>Tagihan yang perlu dibayar</h2></div></div>
        @if ($orders->isEmpty())
            <div class="cart-empty">Tidak ada tagihan yang perlu dibayar. Pesanan baru akan muncul di sini setelah checkout.</div>
        @else
            <div class="order-list">
                @foreach ($orders as $order)
                    <article class="order-card">
                        <div class="order-main">
                            <span class="order-id">PESANAN #{{ $order->id }}</span>
                            <strong>{{ $order->menu?->nama_menu ?? 'Menu dihapus' }}</strong>
                            <small>{{ $order->jumlah }} item · Rp{{ number_format($order->total_harga, 0, ',', '.') }}</small>
                        </div>
                        <span class="status-badge {{ $order->status === 'Menunggu Konfirmasi' ? 'status-wait' : 'status-progress' }}">{{ $order->status }}</span>
                    </article>
                @endforeach
            </div>
            <div class="cart-total"><span>Total tagihan</span><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></div>
        @endif
    </div>

    <aside class="panel payment-guide">
        <span class="eyebrow">PEMBAYARAN QRIS</span>
        @if ($orders->isNotEmpty())
            <h2>Scan untuk membayar</h2>
            <img class="payment-qris" src="{{ asset('images/qris-bikantin.png') }}" alt="Kode QRIS resmi BI-Kantin" width="300" height="300">
            <p class="payment-amount">Total yang harus dibayar <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></p>
            <ol>
                <li>Scan QRIS dengan aplikasi pembayaran dan pastikan nama merchant sesuai.</li>
                <li>Masukkan nominal persis <strong>Rp{{ number_format($total, 0, ',', '.') }}</strong>, lalu selesaikan pembayaran.</li>
                <li>Tunjukkan bukti pembayaran dan username <strong>{{ auth()->user()->username }}</strong> ke kasir.</li>
                <li>Kasir akan memeriksa dan mengonfirmasi pembayaranmu.</li>
            </ol>
        @else
            <h2>Belum ada tagihan</h2>
            <p class="cart-empty">QRIS dan instruksi pembayaran akan muncul setelah ada tagihan yang belum lunas.</p>
        @endif
        <a class="button button-outline button-full" href="{{ route('student.dashboard') }}">Kembali ke menu & pesanan</a>
    </aside>
</section>

@if ($paidOrders->isNotEmpty())
    <section class="section-block payment-history">
        <div class="section-heading"><div><span class="eyebrow">RIWAYAT</span><h2>Pembayaran terkonfirmasi</h2></div></div>
        <div class="order-list">
            @foreach ($paidOrders as $order)
                <article class="order-card">
                    <div class="order-main">
                        <span class="order-id">PESANAN #{{ $order->id }}</span>
                        <strong>{{ $order->menu?->nama_menu ?? 'Menu dihapus' }}</strong>
                        <small>{{ $order->jumlah }} item · Rp{{ number_format($order->total_harga, 0, ',', '.') }}</small>
                    </div>
                    <span class="status-badge status-done">{{ $order->status }}</span>
                </article>
            @endforeach
        </div>
    </section>
@endif
@endsection
