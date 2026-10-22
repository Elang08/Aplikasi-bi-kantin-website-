@extends('layouts.app')

@section('title', 'Kantin online sekolah')

@section('content')
<section class="hero">
    <div class="hero-copy">
        <span class="eyebrow"><span class="live-dot"></span> KANTIN SEKOLAH, CARA BARU</span>
        <h1>Istirahat enak.<br><span>Antre nggak perlu.</span></h1>
        <p>Pilih menu kesukaanmu, pesan dari mana saja, lalu ambil saat sudah siap. Waktu istirahat jadi milikmu lagi.</p>
        <div class="hero-actions">
            <a class="button button-primary" href="{{ route('register') }}">Mulai pesan <span aria-hidden="true">→</span></a>
            <a class="text-link" href="{{ route('login') }}">Saya sudah punya akun</a>
        </div>
        <div class="hero-proof"><span class="proof-avatars">B <b>✓</b></span><span><strong>Pesan dengan mudah</strong><br>dan pantau statusnya langsung</span></div>
    </div>
    <div class="hero-art" aria-hidden="true">
        <div class="art-sun"></div>
        <div class="art-card art-card-back"><span>MENU HARI INI</span><b>Fresh & favorit</b><i>✦</i></div>
        <div class="food-bowl"><div class="bowl-rice"></div><div class="food-leaf leaf-one"></div><div class="food-leaf leaf-two"></div><div class="food-egg"></div><div class="food-chili"></div></div>
        <div class="art-card art-card-front"><span class="mini-check">✓</span><span><b>Pesanan siap!</b><small>Selamat menikmati</small></span></div>
        <span class="art-spark spark-one">✳</span><span class="art-spark spark-two">✦</span>
    </div>
</section>

<section class="section-block">
    <div class="section-heading">
        <div><span class="eyebrow">PILIHAN KANTIN</span><h2>Menu yang bikin semangat</h2></div>
        <p>Intip menu hari ini dan temukan favoritmu.</p>
    </div>
    @if ($menus->isEmpty())
        <div class="empty-state"><span class="empty-icon">☕</span><h3>Menu segera hadir</h3><p>Menu kantin akan tampil di sini setelah admin menambahkannya.</p></div>
    @else
        <div class="menu-grid">
            @foreach ($menus as $menu)
                <article class="menu-card">
                    <div class="menu-illustration {{ $loop->iteration % 3 === 0 ? 'illustration-green' : ($loop->iteration % 2 === 0 ? 'illustration-yellow' : '') }}">
                        <span>{{ $menu->kategori === 'Minuman' ? '🥤' : '🍛' }}</span>
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
    <span class="eyebrow">GAMPANG BANGET</span>
    <h2>Dari pilih menu sampai siap diambil</h2>
    <div class="steps-grid">
        <article><span class="step-number">01</span><h3>Pilih favoritmu</h3><p>Lihat menu makanan dan minuman yang tersedia di kantin.</p></article>
        <article><span class="step-number">02</span><h3>Checkout pesanan</h3><p>Masukkan menu ke keranjang dan konfirmasi pesananmu.</p></article>
        <article><span class="step-number">03</span><h3>Pantau & ambil</h3><p>Lihat status pesanan, lalu ambil saat sudah siap.</p></article>
    </div>
</section>
@endsection
