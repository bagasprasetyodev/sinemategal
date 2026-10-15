@extends('layouts.app')

@section('title', 'Beranda - Sinema Tegal Streaming & Nada Tegalan')

@section('content')
<div class="container">
    <!-- Hero Banner (Netflix Style) -->
    @if($featuredMovie)
    <section class="hero-banner" style="background-image: url('{{ $featuredMovie->banner_url }}');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-badges">
                <span class="badge {{ $featuredMovie->access_badge['class'] }}">
                    {{ $featuredMovie->access_badge['label'] }}
                </span>
                <span class="hero-tag">🌟 Rating {{ $featuredMovie->rating }}</span>
                <span class="hero-tag">⏱️ {{ $featuredMovie->duration_string }}</span>
                <span class="hero-tag">📍 {{ $featuredMovie->location_tag ?? 'Tegal' }}</span>
                <span class="hero-tag">📅 {{ $featuredMovie->release_year }}</span>
            </div>

            <h1 class="hero-title">{{ $featuredMovie->title }}</h1>
            
            @if($featuredMovie->tagline)
                <p class="hero-tagline">"{{ $featuredMovie->tagline }}"</p>
            @endif

            <p class="hero-synopsis">{{ $featuredMovie->synopsis }}</p>

            <div class="hero-actions">
                @php
                    $user = Auth::user();
                    $accessCheck = $user ? $user->canAccessMovie($featuredMovie) : ['allowed' => ($featuredMovie->access_type === 'free')];
                @endphp

                @if($accessCheck['allowed'])
                    <a href="{{ route('movies.watch', $featuredMovie->slug) }}" class="btn btn-primary btn-lg">
                        <span>▶️</span> Putar Film Saiki
                    </a>
                @elseif($featuredMovie->access_type === 'ticket')
                    <a href="{{ route('movies.show', $featuredMovie->slug) }}" class="btn btn-crimson btn-lg">
                        <span>🎟️</span> Tuku Tiket Premiere ({{ $featuredMovie->formatted_price }})
                    </a>
                @else
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary btn-lg">
                        <span>👑</span> Buka Akses VIP Sebayu
                    </a>
                @endif

                <a href="{{ route('movies.show', $featuredMovie->slug) }}" class="btn btn-secondary btn-lg">
                    <span>ℹ️</span> Sinopsis & Informasi
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Highlight Stats Bar (Budaya Tegal) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 3.5rem;">
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="font-size: 1.75rem; background: var(--gold-50); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--gold-600); border: 1px solid var(--gold-100);">🎬</div>
            <div>
                <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">{{ $trendingMovies->count() }}+ Sinema</div>
                <div style="font-size: 0.8rem; color: var(--text-dim);">Film & Dokumenter Tegalan</div>
            </div>
        </div>
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="font-size: 1.75rem; background: var(--cyan-50); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--cyan-600); border: 1px solid var(--cyan-100);">🎵</div>
            <div>
                <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">{{ $featuredTracks->count() }}+ Tembang</div>
                <div style="font-size: 0.8rem; color: var(--text-dim);">Musik & Pop Pantura</div>
            </div>
        </div>
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="font-size: 1.75rem; background: var(--rose-50); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--rose-600); border: 1px solid var(--rose-100);">🎟️</div>
            <div>
                <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">Bioskop Rakyat</div>
                <div style="font-size: 0.8rem; color: var(--text-dim);">Sistem Tiket Premiere</div>
            </div>
        </div>
        <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="font-size: 1.75rem; background: var(--green-50); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: var(--green-600); border: 1px solid var(--green-100);">☕</div>
            <div>
                <div style="font-size: 1.35rem; font-weight: 800; color: var(--text-main);">Wasgitel VIP</div>
                <div style="font-size: 0.8rem; color: var(--text-dim);">Akses Tanpa Batas</div>
            </div>
        </div>
    </div>

    <!-- Showcase Marquee Banner (Ala CGV Arthouse Bioskop) -->
    <section style="margin-bottom: 3.5rem; border-radius: var(--radius-lg); overflow: hidden; border: 1px solid var(--border-subtle); box-shadow: var(--shadow-lg);">
        <img src="/images/banner-arthouse.jpg" alt="Sinema Tegal Arthouse Marquee" style="width: 100%; height: auto; display: block; border-radius: var(--radius-lg);">
    </section>

    <!-- Section 1: Film Bioskop Rakyat (Sistem Tiket Pay-Per-View ala Explore Movies) -->
    <section class="section">
        <div class="section-header">
            <div>
                <div style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: var(--crimson-500); letter-spacing: 0.05em; margin-bottom: 0.2rem;">
                    SELEKSI PREMIERE TERPOPULER
                </div>
                <h2 class="section-title">
                    <span>🎟️</span> Bioskop Rakyat & Tiket Premiere
                </h2>
                <p class="section-subtitle">Tonton karya premiere sineas Tegal nganggo sistem tiket mandiri (Pay-Per-View).</p>
            </div>
            <a href="{{ route('movies.index', ['access' => 'ticket']) }}" class="view-all-link">
                Daftar Tiket Film →
            </a>
        </div>

        <div class="grid-movies">
            @foreach($ticketMovies as $index => $movie)
                <div class="movie-card">
                    <div class="movie-poster-wrap">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster" loading="lazy">
                        <!-- CGV-style Rank Badge (1, 2, 3...) -->
                        <div style="position: absolute; top: 0.75rem; left: 0.75rem; width: 32px; height: 32px; background: #e11d48; color: #fff; font-weight: 900; font-size: 1.1rem; border-radius: 8px; display: flex; align-items: center; justify-content: center; z-index: 3; box-shadow: 0 4px 10px rgba(225, 29, 72, 0.5);">
                            {{ $loop->iteration }}
                        </div>
                        <div class="movie-badge-top" style="left: 3.2rem;">
                            <span class="badge badge-ticket">TIKET {{ $movie->formatted_price }}</span>
                        </div>
                        <div class="movie-rating">
                            ★ {{ $movie->rating }}
                        </div>
                        <div class="movie-card-overlay"></div>
                        <a href="{{ route('movies.show', $movie->slug) }}" class="movie-play-btn" title="Nonton Film">
                            ▶
                        </a>
                    </div>
                    <div class="movie-info">
                        <a href="{{ route('movies.show', $movie->slug) }}">
                            <h3 class="movie-title">{{ $movie->title }}</h3>
                        </a>
                        <div class="movie-meta">
                            <span>{{ $movie->category->name }}</span>
                            <span>•</span>
                            <span>{{ $movie->duration_string }}</span>
                        </div>
                        @if($movie->location_tag)
                            <div class="movie-location">
                                <span>📍</span> {{ $movie->location_tag }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Section 2: Nada Tegalan (Spotify / YouTube Music Player Section) -->
    <section class="section" style="background: linear-gradient(135deg, rgba(15, 23, 42, 0.6), rgba(8, 12, 20, 0.8)); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
        <div class="section-header">
            <div>
                <div style="font-size: 0.75rem; text-transform: uppercase; color: var(--gold-400); font-weight: 800; letter-spacing: 0.1em; margin-bottom: 0.2rem;">
                    SPOTIFY ALA TEGALAN
                </div>
                <h2 class="section-title">
                    <span>🎵</span> Nada Tegalan & Tembang Populer
                </h2>
                <p class="section-subtitle">Rungokna lagu-lagu khas Tegal, pop Ngapak, campursari, lan gending mistik Sintren langsung ning browser!</p>
            </div>
            <a href="{{ route('music.index') }}" class="btn btn-outline-gold btn-sm">
                Buka Player Musik Lengkap →
            </a>
        </div>

        <div class="grid-tracks">
            @foreach($featuredTracks as $track)
                <div class="track-card" data-track-id="{{ $track->id }}" onclick="window.playTrackById({{ $track->id }})">
                    <div class="track-cover-wrap">
                        <img src="{{ $track->cover_url }}" alt="{{ $track->title }}" class="track-cover">
                        <div class="track-play-hover">▶</div>
                    </div>
                    <div class="track-card-info">
                        <div class="track-card-title">{{ $track->title }}</div>
                        <div class="track-card-artist">{{ $track->artist }}</div>
                        <div style="font-size: 0.75rem; color: var(--gold-400); margin-top: 2px;">
                            {{ $track->genre ?? 'Tembang Ngapak' }}
                        </div>
                    </div>
                    <div class="track-card-right">
                        @if($track->access_type === 'vip')
                            <span class="badge badge-vip" style="font-size: 0.65rem;">VIP</span>
                        @else
                            <span class="badge badge-free" style="font-size: 0.65rem;">GRATIS</span>
                        @endif
                        <span class="track-card-duration">{{ $track->duration_string }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Section 3: Film Langganan VIP Sebayu & Koleksi Wayang Cepak -->
    <section class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <span>👑</span> Arsip VIP & Koleksi Wayang Ki Enthus
                </h2>
                <p class="section-subtitle">Dokumenter langka lan pementasan wayang pesisiran kusus anggota langganan VIP Wasgitel.</p>
            </div>
            <a href="{{ route('movies.index', ['access' => 'vip']) }}" class="view-all-link">
                Deleng Kabeh VIP →
            </a>
        </div>

        <div class="grid-movies">
            @foreach($vipMovies as $movie)
                <div class="movie-card">
                    <div class="movie-poster-wrap">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster" loading="lazy">
                        <div class="movie-badge-top">
                            <span class="badge badge-vip">VIP SEBAYU</span>
                        </div>
                        <div class="movie-rating">★ {{ $movie->rating }}</div>
                        <div class="movie-card-overlay"></div>
                        <a href="{{ route('movies.show', $movie->slug) }}" class="movie-play-btn" title="Nonton Film">
                            ▶
                        </a>
                    </div>
                    <div class="movie-info">
                        <a href="{{ route('movies.show', $movie->slug) }}">
                            <h3 class="movie-title">{{ $movie->title }}</h3>
                        </a>
                        <div class="movie-meta">
                            <span>{{ $movie->category->name }}</span>
                            <span>•</span>
                            <span>{{ $movie->duration_string }}</span>
                        </div>
                        @if($movie->location_tag)
                            <div class="movie-location">
                                <span>📍</span> {{ $movie->location_tag }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Section 4: Film Komedi & Tontonan Gratis -->
    <section class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <span>🍿</span> Film Komedi Ngapak & Tontonan Gratis
                </h2>
                <p class="section-subtitle">Bisa langsung disetel tanpa biaya, seger lan ngguyu nganti kemekelen!</p>
            </div>
            <a href="{{ route('movies.index', ['access' => 'free']) }}" class="view-all-link">
                Koleksi Gratis →
            </a>
        </div>

        <div class="grid-movies">
            @foreach($freeMovies as $movie)
                <div class="movie-card">
                    <div class="movie-poster-wrap">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster" loading="lazy">
                        <div class="movie-badge-top">
                            <span class="badge badge-free">GRATIS</span>
                        </div>
                        <div class="movie-rating">★ {{ $movie->rating }}</div>
                        <div class="movie-card-overlay"></div>
                        <a href="{{ route('movies.watch', $movie->slug) }}" class="movie-play-btn" title="Nonton Langsung">
                            ▶
                        </a>
                    </div>
                    <div class="movie-info">
                        <a href="{{ route('movies.show', $movie->slug) }}">
                            <h3 class="movie-title">{{ $movie->title }}</h3>
                        </a>
                        <div class="movie-meta">
                            <span>{{ $movie->category->name }}</span>
                            <span>•</span>
                            <span>{{ $movie->duration_string }}</span>
                        </div>
                        @if($movie->location_tag)
                            <div class="movie-location">
                                <span>📍</span> {{ $movie->location_tag }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Banner Ajakan Langganan VIP (Halobun Minimalist Style) -->
    <section style="background: linear-gradient(135deg, var(--green-50), var(--gold-50)); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 3rem; margin: 3rem 0; position: relative; overflow: hidden; box-shadow: var(--shadow-sm);">
        <div style="max-width: 650px;">
            <span class="badge badge-vip" style="margin-bottom: 1rem;">👑 DADI ANGGOTA VIP DULUR SEBAYU</span>
            <h2 style="font-size: 2.2rem; color: var(--text-main); margin-bottom: 0.8rem;">
                Dukung Sineas & Budaya Asli Tegal Laka-Laka!
            </h2>
            <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.6; margin-bottom: 1.75rem;">
                Kanthi langganan VIP mulai saka Rp 29.000/wulan, sampeyan oleh akses bebas nonton kabeh film eksklusif, dokumenter sejarah peninggalan Ki Gede Sebayu, rekaman wayang langka, lan lagu-lagu tanpa iklan.
            </p>
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary btn-lg">
                    <span>👑</span> Pilih Paket VIP Saiki
                </a>
                <a href="{{ route('movies.index') }}" class="btn btn-secondary btn-lg">
                    Jelajahi Katalog Film
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
