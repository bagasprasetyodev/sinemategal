<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sinema Tegal') - Platform Streaming Film & Nada Khas Tegalan</title>
    <meta name="description" content="Platform streaming film lokal khas Tegal, dokumenter tradisi Sintren, komedi ngapak, wayang cepak Ki Enthus, dan tembang pantura legendaris.">
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    
    <!-- Vanilla CSS Stylesheet (Root Relative to prevent HTTPS Mixed Content) -->
    <link rel="stylesheet" href="/css/app.css">
    @yield('styles')
</head>
<body>

    <!-- Demo Persona Bar (Discreet Utility Bar) -->
    <div class="demo-bar">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.7rem;">Simulasi Peran:</span>
                <span style="color: var(--text-dim); font-size: 0.72rem;">Pilih profil untuk menguji hak akses konten:</span>
            </div>
            <div class="demo-pills">
                @php
                    $demoUsers = \App\Models\User::take(4)->get();
                    $currentUser = Auth::user();
                @endphp
                @foreach($demoUsers as $du)
                    <a href="{{ route('demo.switch', $du->id) }}" 
                       class="demo-pill {{ $currentUser && $currentUser->id === $du->id ? 'is-active' : '' }}"
                       title="{{ $du->email }}">
                        @if($du->role === 'admin') Admin (Ki Patih)
                        @elseif($du->is_vip) VIP (Kang Slamet)
                        @elseif($du->role === 'creator') Kreator (Mas Bowo)
                        @else Reguler (Yu Minah)
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Navigation Header (Minimalist Modern) -->
    <header class="site-navbar">
        <div class="container nav-container">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="nav-brand">
                <div class="brand-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="5 3 19 12 5 21 5 3"></polygon>
                    </svg>
                </div>
                <div style="display: flex; align-items: center;">
                    <span style="font-weight: 800; color: var(--text-main);">SINEMA</span><span style="font-weight: 800; color: var(--gold-600);">TEGAL</span>
                    <span class="brand-badge">Platform Budaya</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            Beranda
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('movies.index') }}" class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
                            Sinema & Film
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('music.index') }}" class="nav-link {{ request()->routeIs('music.*') ? 'active' : '' }}">
                            Nada Tradisi
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('subscriptions.index') }}" class="nav-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                            Langganan VIP
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('tickets.mine') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                            Tiket Saya
                        </a>
                    </li>
                    @endauth
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link nav-admin-link {{ request()->routeIs('admin.*') ? 'active' : '' }}" title="Pusat Manajemen Film, Lagu & Pengguna">
                            Kelola Admin
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Actions / Auth -->
            <div class="nav-actions">
                @auth
                    <!-- Dompet Bahari Pill -->
                    <a href="{{ route('wallet.index') }}" class="user-wallet-badge" title="Saldo Dompet Bahari">
                        <span style="color: var(--text-muted); font-size: 0.75rem;">Saldo:</span>
                        <span>Rp {{ number_format(Auth::user()->wallet_balance, 0, ',', '.') }}</span>
                    </a>

                    <!-- User Profile Dropdown Pill -->
                    <div class="user-menu-pill">
                        <img src="{{ Auth::user()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100' }}" alt="Avatar" class="user-avatar">
                        <span style="font-weight: 600; color: var(--text-main);">{{ Str::limit(Auth::user()->name, 14) }}</span>
                        @if(Auth::user()->hasVipAccess())
                            <span class="badge badge-vip" style="padding: 1px 5px; font-size: 0.6rem;">VIP</span>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" style="margin-left: 0.25rem;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.15rem 0.45rem; font-size: 0.72rem;" title="Keluar">
                                Keluar
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    <div class="container">
        @if(session('success'))
            <div class="flash-message flash-success">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="flash-message flash-error">
                <span>⚠️</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif
        @if(session('warning'))
            <div class="flash-message flash-warning">
                <span>💡</span>
                <span>{{ session('warning') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main style="flex-grow: 1;">
        @yield('content')
    </main>

    <!-- Minimalist Cultural Footer (Halobun Style) -->
    <footer style="background: #ffffff; border-top: 1px solid var(--border-subtle); padding: 3.5rem 0 2rem; margin-top: 4rem;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2.5rem; margin-bottom: 2.5rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 1.25rem; font-weight: 900; margin-bottom: 0.8rem; color: var(--text-main);">
                        <div class="brand-icon" style="width: 32px; height: 32px; font-size: 1rem;">🎭</div>
                        SINEMA TEGAL
                    </div>
                    <p style="color: var(--text-dim); font-size: 0.88rem; line-height: 1.6;">
                        Platform digital nguri-uri kabudayan Tegal: Tari Mistik Sintren Muarareja, Dokumenter Ki Enthus Susmono, Drama Komedi Ngapak, sarta tembang Pantura legendaris.
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">Kategori Budaya</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; color: var(--text-muted);">
                        <li><a href="{{ route('movies.index', ['category' => 'tradisi-mistik-sintren']) }}" style="color: var(--text-muted);">✨ Kesenian Tari Sintren</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'wayang-cepak-ki-enthus']) }}" style="color: var(--text-muted);">👺 Wayang Cepak Ki Enthus</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'komedi-drama-ngapak']) }}" style="color: var(--text-muted);">😂 Film Komedi Ngapak</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'sejarah-budaya-bahari']) }}" style="color: var(--text-muted);">🏛️ Babad Ki Gede Sebayu</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">Layanan & Fitur</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; color: var(--text-muted);">
                        <li><a href="{{ route('subscriptions.index') }}" style="color: var(--text-muted);">👑 Paket Langganan VIP Wasgitel</a></li>
                        <li><a href="{{ route('movies.index', ['access' => 'ticket']) }}" style="color: var(--text-muted);">🎟️ Bioskop Tiket Premiere</a></li>
                        <li><a href="{{ route('music.index') }}" style="color: var(--text-muted);">🎵 Nada Tegalan Pantura</a></li>
                        <li><a href="{{ route('admin.dashboard') }}" style="color: var(--gold-700); font-weight: 700;">⚙️ Panel Kelola Admin</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem; font-weight: 800; color: var(--text-main); margin-bottom: 1rem;">Filosofi Tegal</h4>
                    <div style="background: var(--gold-50); border: 1px solid var(--gold-100); border-radius: 12px; padding: 1rem;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--gold-700); margin-bottom: 0.35rem;">
                            "Wasgitel: Wangi, Sepet, Legi, tur Kentel."
                        </div>
                        <div style="font-size: 0.8rem; color: var(--text-dim); line-height: 1.5;">
                            Ciri khas karakter wong Tegal: ceplas-ceplos nanging tulus, guyub rukun ngguyoni urip sinambi ngeteh poci.
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center; border-top: 1px solid var(--border-subtle); padding-top: 1.5rem; font-size: 0.82rem; color: var(--text-dim);">
                © 2026 Sinema Tegal • Desain Minimalis & Modern Budaya Pantura
            </div>
        </div>
    </footer>

    <!-- Persistent Bottom Audio Player (Spotify / YouTube Music) -->
    <div id="bottomPlayer" class="bottom-player">
        <div class="player-inner">
            <!-- Left: Current Playing Info -->
            <div class="player-track-info">
                <img id="playerThumb" src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=100" alt="Track Cover" class="player-thumb">
                <div class="player-details">
                    <div id="playerTitle" class="player-track-title">Tegal Laka-Laka</div>
                    <div id="playerArtist" class="player-track-artist">Alm. Ki Enthus Susmono</div>
                </div>
            </div>

            <!-- Center: Controls & Timeline -->
            <div class="player-controls-wrap">
                <div class="player-buttons">
                    <button id="playerPrevBtn" class="player-ctrl-btn" title="Lagu Sebelumnya">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="19 20 9 12 19 4 19 20"></polygon><line x1="5" y1="19" x2="5" y2="5" stroke="currentColor" stroke-width="2"></line></svg>
                    </button>
                    <button id="playerPlayBtn" class="player-play-btn" title="Putar/Jeda">
                        <span id="playerPlayIcon">&#9654;</span>
                    </button>
                    <button id="playerNextBtn" class="player-ctrl-btn" title="Lagu Berikutnya">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor"><polygon points="5 4 15 12 5 20 5 4"></polygon><line x1="19" y1="5" x2="19" y2="19" stroke="currentColor" stroke-width="2"></line></svg>
                    </button>
                </div>
                <div class="player-timeline">
                    <span id="playerCurrentTime">00:00</span>
                    <div id="playerSeekBar" class="seek-bar-container">
                        <div id="playerSeekFill" class="seek-bar-fill"></div>
                    </div>
                    <span id="playerTotalTime">03:45</span>
                </div>
            </div>

            <!-- Right: Extra Actions & Volume -->
            <div class="player-extras">
                <button id="lyricsToggleBtn" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.25rem 0.65rem;">
                    Lirik
                </button>
                <div class="volume-container">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-muted);"><polygon points="11 5 6 9 2 9 2 15 6 15 11 19 11 5"></polygon><path d="M19.07 4.93a10 10 0 0 1 0 14.14M15.54 8.46a5 5 0 0 1 0 7.07"></path></svg>
                    <input type="range" id="playerVolume" class="volume-slider" min="0" max="100" value="80">
                </div>
            </div>
        </div>
    </div>

    <!-- Lyrics Drawer (Ngapak & Terjemahan) -->
    <div id="lyricsDrawer" class="lyrics-drawer">
        <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
            <div>
                <h4 id="lyricsTitle" style="font-size: 1rem; color: #fff;">Lirik Tembang</h4>
                <div style="font-size: 0.75rem; color: var(--gold-400);">Dialek Asli Tegal & Terjemahan</div>
            </div>
            <button id="closeLyricsBtn" style="background: transparent; border: none; color: var(--text-muted); font-size: 1.25rem; cursor: pointer;">✕</button>
        </div>
        <div id="lyricsContent" class="lyrics-content">
            Pilih lagu dhisit nggo ndeleng lirik lan maknane.
        </div>
    </div>

    <!-- Global Paywall & VIP Modal (For Audio/Movies) -->
    <div id="paywallModal" class="modal-backdrop">
        <div class="modal-box">
            <button class="modal-close">✕</button>
            <div style="text-align: center;">
                <div style="width: 60px; height: 60px; border-radius: 50%; background: rgba(245, 158, 11, 0.15); border: 2px solid var(--gold-500); color: var(--gold-400); display: flex; align-items: center; justify-content: center; font-size: 1.75rem; margin: 0 auto 1rem;">
                    🔒
                </div>
                <h3 style="font-size: 1.4rem; color: #fff; margin-bottom: 0.5rem;">Konten Eksklusif VIP Sebayu</h3>
                <p id="paywallMessage" style="color: var(--text-muted); font-size: 0.92rem; margin-bottom: 1.5rem;">
                    Konten kiye kusus kanggone Anggota Langganan VIP Dulur Wasgitel.
                </p>
                <div style="background: rgba(255, 255, 255, 0.04); border-radius: 12px; padding: 1rem; margin-bottom: 1.5rem; text-align: left; font-size: 0.85rem; color: #cbd5e1;">
                    <div style="font-weight: 700; color: var(--gold-400); margin-bottom: 0.35rem;">Keuntungan VIP:</div>
                    <div>✓ Bebas nonton kabeh film & arsip wayang cepak langka</div>
                    <div>✓ Audio kualitas dhuwur (Hi-Fi 320kbps) tanpa wates</div>
                    <div>✓ Diskon tuku tiket film premiere bioskop rakyat</div>
                </div>
                <div style="display: flex; gap: 0.75rem;">
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary" style="flex: 1;">
                        👑 Langganan Saiki (Mulai Rp 29.000)
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts (Root Relative) -->
    <script src="/js/audio-player.js"></script>
    @yield('scripts')
</body>
</html>
