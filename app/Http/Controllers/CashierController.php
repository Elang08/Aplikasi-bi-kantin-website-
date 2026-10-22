<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CashierController extends Controller
{
    private const PAYMENT_STATUSES = ['Lunas', 'Siap Diambil'];

    public function index(): View
    {
        $orders = DB::table('pesanan')
            ->leftJoin('users', 'pesanan.user_id', '=', 'users.id')
            ->leftJoin('menu', 'pesanan.menu_id', '=', 'menu.id')
            ->select('pesanan.*', 'users.username', 'menu.nama_menu')
            ->orderByDesc('pesanan.id')
            ->get();

        return view('cashier.dashboard', ['orders' => $orders]);
    }

    public function updateOrderStatus(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(self::PAYMENT_STATUSES)],
        ]);

        $allowedCurrentStatuses = $validated['status'] === 'Lunas'
            ? ['Menunggu Konfirmasi', 'Sedang Dimasak']
            : ['Lunas'];

        if (! in_array($pesanan->status, $allowedCurrentStatuses, true)) {
            return back()->withErrors([
                'order' => 'Status pesanan ini tidak dapat diubah ke status pembayaran yang dipilih.',
            ]);
        }

        $pesanan->update(['status' => $validated['status']]);

        return back()->with('success', 'Pembayaran berhasil divalidasi.');
    }
}
