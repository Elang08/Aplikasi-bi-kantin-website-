<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AdminController extends Controller
{
    private const ORDER_STATUSES = [
        'Menunggu Konfirmasi',
        'Lunas',
        'Sedang Dimasak',
        'Siap Diambil',
        'Selesai',
    ];

    public function index(): View
    {
        return view('admin.dashboard', [
            'menus' => Menu::query()->orderByDesc('id')->get(),
            'orders' => $this->orders(),
            'orderStatuses' => self::ORDER_STATUSES,
        ]);
    }

    public function storeMenu(Request $request): RedirectResponse
    {
        Menu::query()->create($this->validateMenu($request));

        return back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function updateMenu(Request $request, Menu $menu): RedirectResponse
    {
        $menu->update($this->validateMenu($request));

        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroyMenu(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return back()->with('success', 'Menu berhasil dihapus.');
    }

    public function updateOrderStatus(Request $request, Pesanan $pesanan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', Rule::in(self::ORDER_STATUSES)],
        ]);

        $pesanan->update(['status' => $validated['status']]);

        return back()->with('success', 'Status pesanan diperbarui.');
    }

    private function validateMenu(Request $request): array
    {
        return $request->validate([
            'nama_menu' => ['required', 'string', 'min:2', 'max:100'],
            'harga' => ['required', 'integer', 'min:1', 'max:21691752'],
            'kategori' => ['required', 'string', 'min:2', 'max:50'],
        ]);
    }

    private function orders()
    {
        return DB::table('pesanan')
            ->leftJoin('users', 'pesanan.user_id', '=', 'users.id')
            ->leftJoin('menu', 'pesanan.menu_id', '=', 'menu.id')
            ->select('pesanan.*', 'users.username', 'menu.nama_menu')
            ->orderByDesc('pesanan.id')
            ->get();
    }
}
