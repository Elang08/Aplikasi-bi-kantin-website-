@extends('layouts.app')

@section('title', 'Dashboard admin')

@section('content')
<section class="page-heading">
    <div><span class="eyebrow">PANEL PENGELOLA</span><h1>Dashboard kantin</h1><p>Kelola menu dan pantau pesanan yang masuk.</p></div>
    <div class="dashboard-stats"><span><strong>{{ $menus->count() }}</strong> menu</span><span><strong>{{ $orders->count() }}</strong> pesanan</span></div>
</section>

<section class="admin-grid">
    <div class="panel">
        <div class="panel-heading"><div><span class="eyebrow">KATALOG</span><h2>Tambah menu</h2></div><span class="stat-icon">＋</span></div>
        <form action="{{ route('admin.menu.store') }}" method="post" class="stack-form" enctype="multipart/form-data">
            @csrf
            <label for="nama_menu">Nama menu</label><input id="nama_menu" name="nama_menu" maxlength="100" placeholder="Contoh: Nasi Goreng Spesial" required>
            <div class="form-row">
                <div><label for="harga">Harga (Rp)</label><input id="harga" name="harga" type="number" min="1" max="21691752" placeholder="15000" required></div>
                <div><label for="kategori">Kategori</label><input id="kategori" name="kategori" maxlength="50" placeholder="Makanan" required></div>
            </div>
            <div><label for="foto">Foto menu</label><input id="foto" name="foto" type="file" accept="image/jpeg,image/png,image/webp"></div>
            <button class="button button-primary" type="submit">Simpan menu <span aria-hidden="true">→</span></button>
        </form>
    </div>
    <div class="panel menu-management">
        <div class="panel-heading"><div><span class="eyebrow">MENU AKTIF</span><h2>Daftar menu</h2></div><span class="cart-count">{{ $menus->count() }}</span></div>
        @forelse ($menus as $menu)
            <article class="manage-menu">
                <form action="{{ route('admin.menu.update', $menu) }}" method="post" class="manage-form" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <input aria-label="Nama menu" name="nama_menu" value="{{ $menu->nama_menu }}" maxlength="100" required>
                    <input aria-label="Harga menu" name="harga" type="number" value="{{ $menu->harga }}" min="1" max="21691752" required>
                    <input aria-label="Kategori menu" name="kategori" value="{{ $menu->kategori }}" maxlength="50" required>
                    <input aria-label="Foto menu {{ $menu->nama_menu }}" name="foto" type="file" accept="image/jpeg,image/png,image/webp">
                    <button class="button button-outline button-small" type="submit">Simpan</button>
                </form>
                <form action="{{ route('admin.menu.destroy', $menu) }}" method="post" onsubmit="return confirm('Hapus menu ini?')">
                    @csrf @method('DELETE')
                    <button class="remove-button" type="submit" aria-label="Hapus {{ $menu->nama_menu }}">×</button>
                </form>
            </article>
        @empty
            <div class="cart-empty">Belum ada menu. Tambahkan menu pertamamu.</div>
        @endforelse
    </div>
</section>

<section class="panel table-panel">
    <div class="panel-heading"><div><span class="eyebrow">PESANAN MASUK</span><h2>Semua pesanan</h2></div><span class="muted">Status diperbarui manual</span></div>
    @if ($orders->isEmpty())
        <div class="empty-state"><span class="empty-icon">🧾</span><h3>Belum ada pesanan</h3><p>Pesanan siswa akan muncul di sini.</p></div>
    @else
        <div class="table-scroll"><table>
            <thead><tr><th>No.</th><th>Siswa</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Status</th><th>Ubah status</th></tr></thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td><td>{{ $order->username ?? 'Akun siswa' }}</td><td>{{ $order->nama_menu ?? 'Menu dihapus' }}</td><td>{{ $order->jumlah }}</td><td>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td><span class="status-badge status-progress">{{ $order->status }}</span></td>
                        <td><form class="status-form" action="{{ route('admin.orders.update', $order->id) }}" method="post">@csrf @method('PATCH')<select name="status" aria-label="Status pesanan {{ $order->id }}">@foreach ($orderStatuses as $status)<option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>@endforeach</select><button class="button button-outline button-small" type="submit">Perbarui</button></form></td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
    @endif
</section>
@endsection
