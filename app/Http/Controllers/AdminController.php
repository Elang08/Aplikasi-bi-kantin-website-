<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
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
        $validated = $this->validateMenu($request);
        $foto = $request->file('foto');

        if ($foto) {
            $validated['foto'] = $foto->store('menu-photos', 'public');
        }

        Menu::query()->create($validated);

        return back()->with('success', 'Menu berhasil ditambahkan.');
    }

    public function updateMenu(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $this->validateMenu($request);
        $foto = $request->file('foto');

        if ($foto) {
            $oldFoto = $menu->foto;
            $validated['foto'] = $foto->store('menu-photos', 'public');
            $menu->update($validated);

            if ($oldFoto && str_starts_with($oldFoto, 'menu-photos/')) {
                Storage::disk('public')->delete($oldFoto);
            }
        } else {
            $menu->update($validated);
        }

        return back()->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroyMenu(Menu $menu): RedirectResponse
    {
        if ($menu->foto && str_starts_with($menu->foto, 'menu-photos/')) {
            Storage::disk('public')->delete($menu->foto);
        }

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
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
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
