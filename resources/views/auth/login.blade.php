@extends('layouts.app')

@section('title', 'Masuk')

@section('content')
<section class="auth-wrap">
    <div class="auth-aside">
        <span class="eyebrow">SELAMAT DATANG KEMBALI</span>
        <h1>Jajan favoritmu, tinggal beberapa klik.</h1>
        <p>Masuk untuk pesan menu kantin dan pantau pesananmu sampai siap diambil.</p>
        <div class="aside-note"><span class="note-icon">✳</span><span>Praktis buat istirahat yang lebih santai.</span></div>
    </div>
    <form class="auth-card" action="{{ route('login.store') }}" method="post">
        @csrf
        <span class="eyebrow">AKUN BI-KANTIN</span>
        <h2>Masuk ke akun</h2>
        <p class="muted">Gunakan username yang sudah terdaftar.</p>
        <label for="username">Username</label>
        <input id="username" name="username" value="{{ old('username') }}" autocomplete="username" required autofocus>
        @error('username')<span class="field-error">{{ $message }}</span>@enderror
        <label for="password">Password</label>
        <input id="password" name="password" type="password" autocomplete="current-password" required>
        @error('password')<span class="field-error">{{ $message }}</span>@enderror
        <button class="button button-primary button-full" type="submit">Masuk <span aria-hidden="true">→</span></button>
        <p class="auth-switch">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai siswa</a></p>
    </form>
</section>
@endsection
