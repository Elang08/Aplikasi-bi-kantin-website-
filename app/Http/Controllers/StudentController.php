<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $menus = Menu::query()->orderBy('kategori')->orderBy('nama_menu')->get();
        $cart = $this->cart($request);
        $cartItems = Menu::query()->whereIn('id', array_keys($cart))->get()->keyBy('id');
        $validCart = $cartItems->mapWithKeys(
            fn (Menu $menu): array => [$menu->id => $cart[$menu->id]],
        )->all();

        if (count($validCart) !== count($cart)) {
            $request->session()->put('kantin_cart', $validCart);
            $request->session()->flash('warning', 'Menu yang sudah tidak tersedia dihapus dari keranjang.');
            $cart = $validCart;
        }

        $orders = collect();

        if ($request->user()) {
            $orders = Pesanan::query()
                ->where('user_id', $request->user()->id)
                ->with('menu')
                ->orderByDesc('id')
                ->get();
        }

        return view($request->user() ? 'student.dashboard' : 'welcome', [
            'menus' => $menus,
            'cart' => $cart,
            'cartItems' => $cartItems,
            'orders' => $orders,
        ]);
    }

    public function addToCart(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'menu_id' => ['required', 'integer', 'exists:menu,id'],
            'jumlah' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cart($request);
        $id = (int) $validated['menu_id'];
        $cart[$id] = min(99, ($cart[$id] ?? 0) + (int) $validated['jumlah']);
        $request->session()->put('kantin_cart', $cart);

        return back()->with('success', 'Menu ditambahkan ke keranjang.');
    }

    public function updateCart(Request $request, Menu $menu): RedirectResponse
    {
        $validated = $request->validate([
            'jumlah' => ['required', 'integer', 'min:1', 'max:99'],
        ]);

        $cart = $this->cart($request);
        $cart[$menu->id] = (int) $validated['jumlah'];
        $request->session()->put('kantin_cart', $cart);

        return back()->with('success', 'Jumlah pesanan diperbarui.');
    }

    public function removeFromCart(Request $request, Menu $menu): RedirectResponse
    {
        $cart = $this->cart($request);
        unset($cart[$menu->id]);
        $request->session()->put('kantin_cart', $cart);

        return back()->with('success', 'Menu dihapus dari keranjang.');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $cart = $this->cart($request);

        if ($cart === []) {
            throw ValidationException::withMessages([
                'cart' => 'Keranjang masih kosong.',
            ]);
        }

        DB::transaction(function () use ($cart, $request): void {
            foreach ($cart as $menuId => $quantity) {
                $menu = Menu::query()->lockForUpdate()->find($menuId);

                if (! $menu) {
                    throw ValidationException::withMessages([
                        'cart' => 'Salah satu menu di keranjang sudah tidak tersedia. Silakan periksa kembali.',
                    ]);
                }

                $total = $menu->harga * $quantity;

                if ($total > 2147483647) {
                    throw ValidationException::withMessages([
                        'cart' => 'Total salah satu menu melebihi batas transaksi. Kurangi jumlah pesanan.',
                    ]);
                }

                Pesanan::query()->create([
                    'user_id' => $request->user()->id,
                    'menu_id' => $menu->id,
                    'jumlah' => $quantity,
                    'total_harga' => $total,
                    'status' => 'Menunggu Konfirmasi',
                ]);
            }
        });

        $request->session()->forget('kantin_cart');

        return redirect()->route('student.dashboard')->with('success', 'Pesanan berhasil dibuat dan menunggu konfirmasi.');
    }

    private function cart(Request $request): array
    {
        $cart = $request->session()->get('kantin_cart', []);

        return is_array($cart) ? $cart : [];
    }
}
