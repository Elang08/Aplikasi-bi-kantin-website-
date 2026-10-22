@extends('layouts.app')

@section('title', 'Menu kantin')
@section('refresh')
<meta http-equiv="refresh" content="20">
@endsection

@section('content')
<section class="page-heading">
    <div><span class="eyebrow">HALO, {{ strtoupper(auth()->user()->username) }}</span><h1>Waktunya pilih yang enak.</h1><p>Pilih menu, masukkan ke keranjang, dan kami siapkan untukmu.</p></div>
    <a class="button button-outline" href="#keranjang">Keranjang <span class="cart-count">{{ array_sum($cart) }}</span></a>
</section>

<section class="section-block compact-section">
    <div class="section-heading"><div><span class="eyebrow">MENU KANTIN</span><h2>Pilih menu favorit</h2></div><span class="muted">{{ $menus->count() }} menu tersedia</span></div>
    @if ($menus->isEmpty())
        <div class="empty-state"><span class="empty-icon">☕</span><h3>Belum ada menu</h3><p>Admin akan segera menambahkan menu kantin.</p></div>
    @else
        <div class="menu-grid">
            @foreach ($menus as $menu)
                <article class="menu-card">
                    <div class="menu-illustration {{ $loop->iteration % 3 === 0 ? 'illustration-green' : ($loop->iteration % 2 === 0 ? 'illustration-yellow' : '') }}">
                        <span>{{ $menu->kategori === 'Minuman' ? '🥤' : '🍛' }}</span>
                        <small>{{ $menu->kategori }}</small>
                    </div>
                    <div class="menu-info"><h3>{{ $menu->nama_menu }}</h3><span class="price">Rp{{ number_format($menu->harga, 0, ',', '.') }}</span></div>
                    <form action="{{ route('student.cart.add') }}" method="post" class="add-form">
                        @csrf
                        <input type="hidden" name="menu_id" value="{{ $menu->id }}">
                        <label class="sr-only" for="jumlah-{{ $menu->id }}">Jumlah {{ $menu->nama_menu }}</label>
                        <input id="jumlah-{{ $menu->id }}" class="quantity-input" type="number" name="jumlah" value="1" min="1" max="99">
                        <button class="button button-primary" type="submit">Tambah <span aria-hidden="true">+</span></button>
                    </form>
                </article>
            @endforeach
        </div>
    @endif
</section>

<section class="split-section" id="keranjang">
    <div class="panel">
        <div class="panel-heading"><div><span class="eyebrow">SIAP DIPESAN</span><h2>Keranjangmu</h2></div><span class="cart-count">{{ array_sum($cart) }}</span></div>
        @if ($cartItems->isEmpty())
            <div class="cart-empty">Keranjangmu masih kosong. Yuk, pilih menu favorit!</div>
        @else
            <div class="cart-list">
                @php($cartTotal = 0)
                @foreach ($cartItems as $menu)
                    @php($quantity = $cart[$menu->id] ?? 0)
                    @php($cartTotal += $menu->harga * $quantity)
                    <div class="cart-row">
                        <div><strong>{{ $menu->nama_menu }}</strong><small>Rp{{ number_format($menu->harga, 0, ',', '.') }} / item</small></div>
                        <form action="{{ route('student.cart.update', $menu) }}" method="post" class="quantity-form">
                            @csrf @method('PATCH')
                            <input aria-label="Jumlah {{ $menu->nama_menu }}" type="number" name="jumlah" value="{{ $quantity }}" min="1" max="99">
                            <button class="text-button" type="submit">Ubah</button>
                        </form>
                        <form action="{{ route('student.cart.remove', $menu) }}" method="post">
                            @csrf @method('DELETE')
                            <button class="remove-button" type="submit" aria-label="Hapus {{ $menu->nama_menu }}">×</button>
                        </form>
                    </div>
                @endforeach
            </div>
            <div class="cart-total"><span>Total sementara</span><strong>Rp{{ number_format($cartTotal, 0, ',', '.') }}</strong></div>
            <form action="{{ route('student.checkout') }}" method="post">
                @csrf
                <button class="button button-primary button-full" type="submit">Buat pesanan <span aria-hidden="true">→</span></button>
            </form>
        @endif
    </div>
    <div class="orders-panel">
        <div class="panel-heading"><div><span class="eyebrow">PANTAU PESANAN</span><h2>Pesanan terakhir</h2></div></div>
        @if ($orders->isEmpty())
            <div class="cart-empty">Pesananmu akan muncul di sini setelah checkout.</div>
        @else
            <div class="order-list">
                @foreach ($orders as $order)
                    <article class="order-card">
                        <div class="order-main"><span class="order-id">PESANAN #{{ $order->id }}</span><strong>{{ $order->menu?->nama_menu ?? 'Menu dihapus' }}</strong><small>{{ $order->jumlah }} item · Rp{{ number_format($order->total_harga, 0, ',', '.') }}</small></div>
                        <span class="status-badge {{ $order->status === 'Selesai' ? 'status-done' : ($order->status === 'Menunggu Konfirmasi' ? 'status-wait' : 'status-progress') }}">{{ $order->status }}</span>
                    </article>
                @endforeach
            </div>
        @endif
    </div>
</section>
@endsection
