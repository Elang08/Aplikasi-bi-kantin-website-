<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KantinWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_register_add_to_cart_and_checkout(): void
    {
        $menu = Menu::query()->create([
            'nama_menu' => 'Nasi Goreng',
            'harga' => 15000,
            'kategori' => 'Makanan',
        ]);

        $this->post(route('register.store'), [
            'username' => 'siswa_baru',
            'password' => 'password-aman',
            'password_confirmation' => 'password-aman',
        ])->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseHas('users', ['username' => 'siswa_baru', 'role' => 'siswa']);
        $this->assertNotSame('password-aman', User::query()->where('username', 'siswa_baru')->value('password'));

        $this->post(route('student.cart.add'), [
            'menu_id' => $menu->id,
            'jumlah' => 2,
        ])->assertRedirect();

        $this->post(route('student.checkout'))
            ->assertRedirect(route('student.dashboard'));

        $this->assertDatabaseHas('pesanan', [
            'menu_id' => $menu->id,
            'jumlah' => 2,
            'total_harga' => 30000,
            'status' => 'Menunggu Konfirmasi',
        ]);
    }

    public function test_legacy_plaintext_password_is_upgraded_after_successful_login(): void
    {
        DB::table('users')->insert([
            'username' => 'admin_lama',
            'password' => 'admin123',
            'role' => 'admin',
        ]);

        $this->post(route('login.store'), [
            'username' => 'admin_lama',
            'password' => 'admin123',
        ])->assertRedirect(route('admin.dashboard'));

        $storedPassword = DB::table('users')->where('username', 'admin_lama')->value('password');
        $this->assertNotSame('admin123', $storedPassword);
        $this->assertTrue(password_verify('admin123', $storedPassword));
    }

    public function test_admin_can_create_menu_and_update_order_status(): void
    {
        $admin = User::query()->create([
            'username' => 'admin',
            'password' => 'admin-password',
            'role' => 'admin',
        ]);
        $student = User::query()->create([
            'username' => 'siswa',
            'password' => 'siswa-password',
            'role' => 'siswa',
        ]);
        $menu = Menu::query()->create([
            'nama_menu' => 'Es Teh',
            'harga' => 4000,
            'kategori' => 'Minuman',
        ]);
        $order = Pesanan::query()->create([
            'user_id' => $student->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'total_harga' => 4000,
            'status' => 'Menunggu Konfirmasi',
        ]);

        $this->actingAs($admin)
            ->post(route('admin.menu.store'), [
                'nama_menu' => 'Roti Bakar',
                'harga' => 8000,
                'kategori' => 'Makanan',
            ])
            ->assertRedirect();

        $this->actingAs($admin)
            ->patch(route('admin.orders.update', $order), ['status' => 'Sedang Dimasak'])
            ->assertRedirect();

        $this->actingAs($admin)
            ->delete(route('admin.menu.destroy', $menu))
            ->assertRedirect();

        $this->assertDatabaseHas('menu', ['nama_menu' => 'Roti Bakar', 'harga' => 8000]);
        $this->assertDatabaseMissing('menu', ['id' => $menu->id]);
        $this->assertDatabaseHas('pesanan', ['id' => $order->id, 'status' => 'Sedang Dimasak']);
        $this->assertDatabaseHas('pesanan', ['id' => $order->id, 'status' => 'Sedang Dimasak']);
    }

    public function test_cashier_can_validate_payment_but_student_cannot_access_cashier_dashboard(): void
    {
        $cashier = User::query()->create([
            'username' => 'kasir',
            'password' => 'kasir-password',
            'role' => 'kasir',
        ]);
        $student = User::query()->create([
            'username' => 'siswa',
            'password' => 'siswa-password',
            'role' => 'siswa',
        ]);
        $menu = Menu::query()->create([
            'nama_menu' => 'Jus',
            'harga' => 7000,
            'kategori' => 'Minuman',
        ]);
        $order = Pesanan::query()->create([
            'user_id' => $student->id,
            'menu_id' => $menu->id,
            'jumlah' => 1,
            'total_harga' => 7000,
            'status' => 'Menunggu Konfirmasi',
        ]);

        $this->actingAs($cashier)
            ->patch(route('cashier.orders.update', $order), ['status' => 'Lunas'])
            ->assertRedirect();
        $this->assertDatabaseHas('pesanan', ['id' => $order->id, 'status' => 'Lunas']);

        $this->actingAs($cashier)
            ->patch(route('cashier.orders.update', $order), ['status' => 'Siap Diambil'])
            ->assertRedirect();
        $this->assertDatabaseHas('pesanan', ['id' => $order->id, 'status' => 'Siap Diambil']);

        $this->actingAs($student)
            ->get(route('cashier.dashboard'))
            ->assertForbidden();
    }
}
