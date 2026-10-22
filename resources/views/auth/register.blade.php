@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<section class="auth-wrap">
    <div class="auth-aside">
        <span class="eyebrow">BIKIN WAKTU ISTIRAHAT LEBIH SERU</span>
        <h1>Menu kantin favorit, siap menemani harimu.</h1>
        <p>Buat akun siswa untuk mulai melihat menu, menyusun keranjang, dan memantau status pesanan.</p>
        <div class="aside-note"><span class="note-icon">✓</span><span>Daftar cepat, pesan kapan saja.</span></div>
    </div>
    <form class="auth-card" action="{{ route('register.store') }}" method="post">
        @csrf
        <span class="eyebrow">AKUN BARU</span>
        <h2>Daftar siswa</h2>
        <p class="muted">Isi username dan password untuk membuat akun.</p>
        <label for="username">Username</label>
        <input id="username" name="username" value="{{ old('username') }}" autocomplete="username" minlength="3" maxlength="50" required autofocus>
        @error('username')<span class="field-error">{{ $message }}</span>@enderror
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" required>
        <span class="input-hint">Gunakan minimal 8 karakter.</span>
        <label for="password_confirmation">Ulangi password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>
        @error('password')<span class="field-error">{{ $message }}</span>@enderror
        <button class="button button-primary button-full" type="submit">Buat akun <span aria-hidden="true">→</span></button>
        <p class="auth-switch">Sudah punya akun? <a href="{{ route('login') }}">Masuk</a></p>
    </form>
</section>
@endsection
