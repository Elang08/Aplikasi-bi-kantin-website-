@extends('layouts.app')

@section('title', 'Validasi pembayaran')

@section('content')
<section class="page-heading">
    <div><span class="eyebrow">PANEL KASIR</span><h1>Validasi pembayaran</h1><p>Periksa pesanan dan konfirmasi pembayaran siswa.</p></div>
    <div class="dashboard-stats"><span><strong>{{ $orders->where('status', 'Menunggu Konfirmasi')->count() }}</strong> menunggu</span><span><strong>{{ $orders->count() }}</strong> pesanan</span></div>
</section>

<section class="panel table-panel">
    <div class="panel-heading"><div><span class="eyebrow">DAFTAR TRANSAKSI</span><h2>Pesanan kantin</h2></div><span class="muted">Pembayaran diverifikasi kasir</span></div>
    @if ($orders->isEmpty())
        <div class="empty-state"><span class="empty-icon">🧾</span><h3>Belum ada transaksi</h3><p>Pesanan siswa akan muncul di sini.</p></div>
    @else
        <div class="table-scroll"><table>
            <thead><tr><th>No.</th><th>Siswa</th><th>Menu</th><th>Jumlah</th><th>Total</th><th>Status</th><th>Validasi</th></tr></thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td><td>{{ $order->username ?? 'Akun siswa' }}</td><td>{{ $order->nama_menu ?? 'Menu dihapus' }}</td><td>{{ $order->jumlah }}</td><td>Rp{{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td><span class="status-badge {{ $order->status === 'Menunggu Konfirmasi' ? 'status-wait' : 'status-progress' }}">{{ $order->status }}</span></td>
                        <td>
                            @if (in_array($order->status, ['Menunggu Konfirmasi', 'Sedang Dimasak', 'Lunas'], true))
                                <form class="status-form" action="{{ route('cashier.orders.update', $order->id) }}" method="post">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="{{ $order->status === 'Lunas' ? 'Siap Diambil' : 'Lunas' }}">
                                    <button class="button button-primary button-small" type="submit">{{ $order->status === 'Lunas' ? 'Siap diambil' : 'Konfirmasi lunas' }}</button>
                                </form>
                            @else
                                <span class="muted">Tidak perlu tindakan</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table></div>
    @endif
</section>
@endsection
