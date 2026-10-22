<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function showRegistration(): View
    {
        return view('auth.register');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string', 'max:255'],
        ]);

        $user = User::query()->where('username', $credentials['username'])->first();
        $storedPassword = $user?->getRawOriginal('password');

        if (! $user || ! is_string($storedPassword) || ! $this->passwordMatches($credentials['password'], $storedPassword)) {
            throw ValidationException::withMessages([
                'username' => 'Username atau password tidak sesuai.',
            ]);
        }

        if (password_get_info($storedPassword)['algo'] === null) {
            $user->password = $credentials['password'];
            $user->save();
        }

        Auth::login($user);
        $request->session()->regenerate();

        return $this->dashboardRedirect($user->role);
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'min:3', 'max:50', 'alpha_dash', 'unique:users,username'],
            'password' => ['required', 'string', 'min:8', 'max:255', 'confirmed'],
        ]);

        $user = User::query()->create([
            'username' => $validated['username'],
            'password' => $validated['password'],
            'role' => 'siswa',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('student.dashboard')->with('success', 'Akun berhasil dibuat. Selamat berbelanja!');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Kamu berhasil keluar.');
    }

    private function passwordMatches(string $given, string $stored): bool
    {
        if (password_get_info($stored)['algo'] !== null) {
            return Hash::check($given, $stored);
        }

        return hash_equals($stored, $given);
    }

    private function dashboardRedirect(string $role): RedirectResponse
    {
        return match ($role) {
            'admin' => redirect()->route('admin.dashboard'),
            'kasir' => redirect()->route('cashier.dashboard'),
            'siswa' => redirect()->route('student.dashboard'),
            default => abort(403),
        };
    }
}
