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

    <!-- Demo Persona Bar (Memudahkan Pengujian Fitur Tiket, VIP, & Admin) -->
    <div class="demo-bar">
        <div class="container" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.5rem;">
                <span style="color: var(--gold-400); font-weight: 700;">☕ Uji Profil Akun (1-Klik):</span>
                <span style="color: var(--text-dim); font-size: 0.72rem;">Ganti peran nggo nyoba fitur gratis, VIP, tiket bioskop, utawa kurator admin:</span>
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
                        @if($du->role === 'admin') 👑 Admin (@if($currentUser && $currentUser->id === $du->id)Aktif @else Ki Patih @endif)
                        @elseif($du->is_vip) ⭐ VIP (Kang Slamet)
                        @elseif($du->role === 'creator') 🎬 Kreator (Mas Bowo)
                        @else 🎟️ Reguler + 1 Tiket (Yu Minah)
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Navigation Header (Glassmorphic) -->
    <header class="site-navbar">
        <div class="container nav-container">
            <!-- Brand Logo -->
            <a href="{{ route('home') }}" class="nav-brand">
                <div class="brand-icon">🎭</div>
                <div>
                    SINEMA<span class="text-gold">TEGAL</span>
                    <span class="brand-badge">Laka-Laka</span>
                </div>
            </a>

            <!-- Navigation Links -->
            <nav>
                <ul class="nav-links">
                    <li>
                        <a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}">
                            <span>Beranda</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('movies.index') }}" class="nav-link {{ request()->routeIs('movies.*') ? 'active' : '' }}">
                            <span>🎬 Sinema & Film</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('music.index') }}" class="nav-link {{ request()->routeIs('music.*') ? 'active' : '' }}">
                            <span>🎵 Nada Tegalan</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('subscriptions.index') }}" class="nav-link {{ request()->routeIs('subscriptions.*') ? 'active' : '' }}">
                            <span>👑 Langganan VIP</span>
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('tickets.mine') }}" class="nav-link {{ request()->routeIs('tickets.*') ? 'active' : '' }}">
                            <span>🎟️ Tiket Kula ({{ Auth::user()->activeTickets()->count() }})</span>
                        </a>
                    </li>
                    @if(in_array(Auth::user()->role, ['admin', 'creator']))
                    <li>
                        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                            <span style="color: var(--gold-400);">⚙️ Kurator Hub</span>
                        </a>
                    </li>
                    @endif
                    @endauth
                </ul>
            </nav>

            <!-- Actions / Auth -->
            <div class="nav-actions">
                @auth
                    <!-- Dompet Bahari Pill -->
                    <a href="{{ route('wallet.index') }}" class="user-wallet-badge" title="Saldo Dompet Bahari nggo tuku tiket/VIP">
                        <span>💰</span>
                        <span>Rp {{ number_format(Auth::user()->wallet_balance, 0, ',', '.') }}</span>
                    </a>

                    <!-- User Profile Dropdown Pill -->
                    <div class="user-menu-pill">
                        <img src="{{ Auth::user()->avatar ?? 'https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?w=100' }}" alt="Avatar" class="user-avatar">
                        <span style="font-weight: 600;">{{ Str::limit(Auth::user()->name, 14) }}</span>
                        @if(Auth::user()->hasVipAccess())
                            <span class="badge badge-vip" style="padding: 2px 5px; font-size: 0.65rem;">VIP</span>
                        @endif
                        <form action="{{ route('logout') }}" method="POST" style="margin-left: 0.4rem;">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm" style="padding: 0.2rem 0.5rem; font-size: 0.75rem;" title="Metu akun">
                                🚪
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-secondary btn-sm">Mlebu</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar Akun</a>
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
        @if(session('info'))
            <div class="flash-message" style="background: rgba(6, 182, 212, 0.15); border: 1px solid rgba(6, 182, 212, 0.4); color: var(--cyan-400);">
                <span>ℹ️</span>
                <span>{{ session('info') }}</span>
            </div>
        @endif
    </div>

    <!-- Main Content -->
    <main style="flex-grow: 1;">
        @yield('content')
    </main>

    <!-- Cultural Footer -->
    <footer style="background: #05080e; border-top: 1px solid var(--border-subtle); padding: 3.5rem 0 2rem; margin-top: 4rem;">
        <div class="container">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 2.5rem; margin-bottom: 2.5rem;">
                <div>
                    <div style="display: flex; align-items: center; gap: 0.6rem; font-size: 1.3rem; font-weight: 800; margin-bottom: 0.8rem;">
                        <span style="color: var(--gold-400);">🎭</span> SINEMA TEGAL
                    </div>
                    <p style="color: var(--text-dim); font-size: 0.88rem; line-height: 1.6;">
                        Wadah streaming film, sandiwara pesisiran, tari mistik Sintren, arsip wayang cepak alm. Ki Enthus Susmono, sarta alunan tembang Tegalan laka-laka. Nguri-uri kabudayan Tegal ben tetep moncer!
                    </p>
                </div>
                <div>
                    <h4 style="font-size: 1rem; color: #fff; margin-bottom: 1rem;">Kategori Budaya</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; color: var(--text-muted);">
                        <li><a href="{{ route('movies.index', ['category' => 'tradisi-mistik-sintren']) }}">✨ Kesenian Tari Sintren</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'wayang-cepak-ki-enthus']) }}">👺 Wayang Cepak Ki Enthus</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'komedi-drama-ngapak']) }}">😂 Film Komedi Ngapak</a></li>
                        <li><a href="{{ route('movies.index', ['category' => 'sejarah-budaya-bahari']) }}">🏛️ Babad Ki Gede Sebayu & Pabrik Gula</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 1rem; color: #fff; margin-bottom: 1rem;">Layanan & Akses</h4>
                    <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem; font-size: 0.88rem; color: var(--text-muted);">
                        <li><a href="{{ route('subscriptions.index') }}">👑 Paket Langganan VIP Wasgitel</a></li>
                        <li><a href="{{ route('movies.index', ['access' => 'ticket']) }}">🎟️ Bioskop Tiket Premiere</a></li>
                        <li><a href="{{ route('music.index') }}">🎵 Nada Tegalan (Player Lagu)</a></li>
                        <li><a href="{{ route('wallet.index') }}">💰 Dompet Bahari & Saldo</a></li>
                    </ul>
                </div>
                <div>
                    <h4 style="font-size: 1rem; color: #fff; margin-bottom: 1rem;">Filosofi Tegal</h4>
                    <div style="background: rgba(245, 158, 11, 0.08); border: 1px solid rgba(245, 158, 11, 0.25); border-radius: 12px; padding: 1rem;">
                        <div style="font-size: 0.85rem; font-style: italic; color: var(--gold-400); margin-bottom: 0.35rem;">
                            "Wasgitel: Wangi, Sepet, Legi, tur Kentel."
                        </div>
                        <div style="font-size: 0.78rem; color: var(--text-dim);">
                            Ciri khas wong Tegal: ceplas-ceplos nanging tulus, guyub rukun ngguyoni urip sinambi ngeteh poci lan mangan kupat glabed.
                        </div>
                    </div>
                </div>
            </div>
            <div style="text-align: center; border-top: 1px solid rgba(255, 255, 255, 0.05); padding-top: 1.5rem; font-size: 0.8rem; color: var(--text-dim);">
                © 2026 Sinema Tegal - Platform Streaming Budaya & Seni Pesisir Pantura. Berbasis Laravel & Vanilla CSS.
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
                    <button id="playerPrevBtn" class="player-ctrl-btn" title="Lagu Sakdurunge">⏮️</button>
                    <button id="playerPlayBtn" class="player-play-btn" title="Play/Pause">
                        <span id="playerPlayIcon">&#9654;</span>
                    </button>
                    <button id="playerNextBtn" class="player-ctrl-btn" title="Lagu Sakbanjure">⏭️</button>
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
                <button id="lyricsToggleBtn" class="btn btn-secondary btn-sm" style="font-size: 0.75rem; padding: 0.35rem 0.65rem;">
                    📜 Lirik Ngapak
                </button>
                <div class="volume-container">
                    <span style="font-size: 0.9rem;">🔊</span>
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
