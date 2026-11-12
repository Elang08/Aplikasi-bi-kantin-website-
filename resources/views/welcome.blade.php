@extends('layouts.app')

@section('title', 'Kantin online sekolah')

@section('content')
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow">KANTIN SMK BINA INFORMATIKA</span>
        <h1>Pesan makan<br><span>jadi lebih mudah.</span></h1>
        <p>Lihat menu kantin, pilih jumlahnya, lalu pantau pesananmu dari satu tempat.</p>
        <div class="hero-actions">
            <a class="button button-primary" href="{{ route('register') }}">Mulai pesan <span aria-hidden="true">→</span></a>
            <a class="text-link" href="{{ route('login') }}">Saya sudah punya akun</a>
        </div>
        <div class="hero-proof"><span><strong>Menu · Pesanan · Pembayaran</strong><br>Semua bisa dicek dari dashboard siswa.</span></div>
    </div>
    <div class="hero-menu-photos" aria-label="Contoh menu kantin">
        <figure class="hero-menu-featured">
            <img src="{{ asset('images/menu/mie-ayam.jpg') }}" alt="Mie Ayam">
            <figcaption>Mie Ayam</figcaption>
        </figure>
        <figure class="hero-menu-secondary">
            <img src="{{ asset('images/menu/nasi-goreng.jpg') }}" alt="Nasi Goreng Spesial">
            <figcaption>Nasi Goreng Spesial</figcaption>
        </figure>
    </div>
</section>

<section class="section-block">
    <div class="section-heading">
        <div><span class="eyebrow">MENU KANTIN</span><h2>Pilihan menu</h2></div>
        <p>Menu dan harga yang tersedia saat ini.</p>
    </div>
    @if ($menus->isEmpty())
        <div class="empty-state"><span class="empty-icon">☕</span><h3>Menu segera hadir</h3><p>Menu kantin akan tampil di sini setelah admin menambahkannya.</p></div>
    @else
        <div class="menu-grid">
            @foreach ($menus as $menu)
                <article class="menu-card">
                    <div class="menu-illustration">
                        @if ($menu->foto)
                            <img src="{{ str_starts_with($menu->foto, 'images/') ? asset($menu->foto) : asset('storage/'.$menu->foto) }}" alt="{{ $menu->nama_menu }}" loading="lazy">
                        @else
                            <span>{{ $menu->kategori === 'Minuman' ? '🥤' : '🍛' }}</span>
                        @endif
                        <small>{{ $menu->kategori }}</small>
                    </div>
                    <div class="menu-info"><h3>{{ $menu->nama_menu }}</h3><span class="price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</span></div>
                    <a class="button button-outline button-full" href="{{ route('register') }}">Pesan menu <span aria-hidden="true">→</span></a>
                </article>
            @endforeach
        </div>
    @endif
</section>

<section class="steps-block">
    <span class="eyebrow">CARA PESAN</span>
    <h2>Tiga langkah memesan</h2>
    <div class="steps-grid">
        <article><span class="step-number">01</span><h3>Pilih favoritmu</h3><p>Lihat menu makanan dan minuman yang tersedia di kantin.</p></article>
        <article><span class="step-number">02</span><h3>Checkout pesanan</h3><p>Masukkan menu ke keranjang dan konfirmasi pesananmu.</p></article>
        <article><span class="step-number">03</span><h3>Pantau & ambil</h3><p>Lihat status pesanan, lalu ambil saat sudah siap.</p></article>
    </div>
</section>
@endsection
