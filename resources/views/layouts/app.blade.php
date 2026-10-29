<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('refresh')
    <title>@yield('title', 'BI-Kantin') · BI-Kantin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Manrope:wght@500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="role-{{ auth()->check() ? auth()->user()->role : 'guest' }}">
    <a class="skip-link" href="#main-content">Lewati ke konten utama</a>
    <header class="site-header">
        <a class="brand" href="{{ route('home') }}" aria-label="BI-Kantin beranda">
            <img class="brand-logo" src="{{ asset('images/logo-smk-bina-informatika.png') }}" alt="">
            <span>BI<span class="brand-accent">·</span>Kantin</span>
        </a>
        <nav class="nav-links" aria-label="Navigasi utama">
            @auth
                @if (auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                @elseif (auth()->user()->role === 'kasir')
                    <a href="{{ route('cashier.dashboard') }}">Pembayaran</a>
                @else
                    <a href="{{ route('student.dashboard') }}">Menu & pesanan</a>
                    <a href="{{ route('student.payment') }}">Pembayaran</a>
                @endif
                <span class="user-chip">{{ auth()->user()->username }}</span>
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button class="button button-quiet button-small" type="submit">Keluar</button>
                </form>
            @else
                <a href="{{ route('login') }}">Masuk</a>
                <a class="button button-primary button-small" href="{{ route('register') }}">Daftar</a>
            @endauth
        </nav>
    </header>

    <main id="main-content">
        @if (session('success'))
            <div class="flash flash-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('warning'))
            <div class="flash flash-error" role="status">{{ session('warning') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash flash-error" role="alert">
                <strong>Periksa kembali data kamu.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="site-footer">
        <span>BI-Kantin</span>
        <span>Jajan enak, pesan lebih gampang.</span>
    </footer>
</body>
</html>
