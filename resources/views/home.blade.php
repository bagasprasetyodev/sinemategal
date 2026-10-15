@extends('layouts.app')

@section('title', 'Beranda - Sinema Tegal Streaming & Nada Tegalan')

@section('content')
<div class="container">
    <!-- Hero Banner (Clean Cinema Marquee) -->
    @if($featuredMovie)
    <section class="hero-banner" style="background-image: url('{{ $featuredMovie->banner_url }}');">
        <div class="hero-overlay"></div>
        <div class="hero-content">
            <div class="hero-meta-row">
                <span class="badge {{ $featuredMovie->access_badge['class'] }}">
                    {{ $featuredMovie->access_badge['label'] }}
                </span>
                <div class="hero-meta-list">
                    <span>{{ $featuredMovie->release_year }}</span>
                    <span class="meta-dot">•</span>
                    <span>{{ $featuredMovie->duration_string }}</span>
                    @if($featuredMovie->location_tag)
                        <span class="meta-dot">•</span>
                        <span>{{ $featuredMovie->location_tag }}</span>
                    @endif
                    <span class="meta-dot">•</span>
                    <span class="meta-rating">★ {{ $featuredMovie->rating }}</span>
                </div>
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
                        Putar Film Sekarang
                    </a>
                @elseif($featuredMovie->access_type === 'ticket')
                    <a href="{{ route('movies.show', $featuredMovie->slug) }}" class="btn btn-crimson btn-lg">
                        Beli Tiket Premiere ({{ $featuredMovie->formatted_price }})
                    </a>
                @else
                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary btn-lg">
                        Buka Akses VIP Sebayu
                    </a>
                @endif

                <a href="{{ route('movies.show', $featuredMovie->slug) }}" class="btn btn-secondary btn-lg">
                    Detail Film & Budaya
                </a>
            </div>
        </div>
    </section>
    @endif

    <!-- Highlight Stats Bar (Professional Metrics) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 3rem;">
        <div style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); padding: 1.15rem 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; color: var(--text-main); font-weight: 700;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="2.18" ry="2.18"></rect><line x1="7" y1="2" x2="7" y2="22"></line><line x1="17" y1="2" x2="17" y2="22"></line><line x1="2" y1="12" x2="22" y2="12"></line><line x1="2" y1="7" x2="7" y2="7"></line><line x1="2" y1="17" x2="7" y2="17"></line><line x1="17" y1="17" x2="22" y2="17"></line><line x1="17" y1="7" x2="22" y2="7"></line></svg>
            </div>
            <div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">{{ $trendingMovies->count() }}+ Judul</div>
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 500;">Sinema & Dokumenter</div>
            </div>
        </div>
        <div style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); padding: 1.15rem 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; color: var(--text-main); font-weight: 700;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>
            </div>
            <div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">{{ $featuredTracks->count() }}+ Lagu</div>
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 500;">Arsip Nada Pantura</div>
            </div>
        </div>
        <div style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); padding: 1.15rem 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; color: var(--text-main); font-weight: 700;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="4" width="20" height="16" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            </div>
            <div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Pay-Per-View</div>
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 500;">Tiket Bioskop Mandiri</div>
            </div>
        </div>
        <div style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-sm); padding: 1.15rem 1.25rem; display: flex; align-items: center; gap: 1rem; box-shadow: var(--shadow-xs);">
            <div style="width: 40px; height: 40px; border-radius: 8px; background: #f8fafc; border: 1px solid var(--border-subtle); display: flex; align-items: center; justify-content: center; color: var(--text-main); font-weight: 700;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
            </div>
            <div>
                <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-main);">Wasgitel VIP</div>
                <div style="font-size: 0.78rem; color: var(--text-muted); font-weight: 500;">Akses Arsip Lengkap</div>
            </div>
        </div>
    </div>

    <!-- Section 1: Film Bioskop Rakyat (Sistem Tiket Premiere) -->
    <section class="section">
        <div class="section-header">
            <div>
                <div style="font-size: 0.72rem; font-weight: 700; text-transform: uppercase; color: var(--rose-600); letter-spacing: 0.05em; margin-bottom: 0.2rem;">
                    SELEKSI PREMIERE
                </div>
                <h2 class="section-title">
                    Bioskop Rakyat & Tiket Premiere
                </h2>
                <p class="section-subtitle">Tonton karya premiere sineas Tegal dengan tiket mandiri (Pay-Per-View).</p>
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
                        <div style="position: absolute; top: 0.75rem; left: 0.75rem; width: 28px; height: 28px; background: rgba(15, 23, 42, 0.85); backdrop-filter: blur(4px); color: #fff; font-weight: 700; font-size: 0.85rem; border-radius: 6px; display: flex; align-items: center; justify-content: center; z-index: 3;">
                            {{ $loop->iteration }}
                        </div>
                        <div class="movie-badge-top" style="left: 3rem;">
                            <span class="badge badge-ticket">{{ $movie->formatted_price }}</span>
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
                            <span>{{ $movie->release_year }}</span>
                            <span>•</span>
                            <span>{{ $movie->duration_string }}</span>
                            <span>•</span>
                            <span>{{ $movie->category->name }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Section 2: Nada Tegalan (Clean Minimalist Audio Showcase) -->
    <section class="section" style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.75rem; box-shadow: var(--shadow-xs);">
        <div class="section-header" style="margin-bottom: 1.25rem;">
            <div>
                <div style="font-size: 0.72rem; text-transform: uppercase; color: var(--gold-600); font-weight: 700; letter-spacing: 0.05em; margin-bottom: 0.2rem;">
                    ARSIP AUDIO TRADISI
                </div>
                <h2 class="section-title">
                    Nada Tradisi & Tembang Pantura
                </h2>
                <p class="section-subtitle">Dengarkan gending Sintren, pop Ngapak, dan campursari pesisir langsung di browser.</p>
            </div>
            <a href="{{ route('music.index') }}" class="btn btn-secondary btn-sm">
                Buka Katalog Musik →
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
                        <div class="track-card-artist">
                            {{ $track->artist }}@if($track->genre) • <span style="color: var(--gold-600); font-weight: 600;">{{ $track->genre }}</span>@endif
                        </div>
                    </div>
                    <div class="track-card-right">
                        @if($track->access_type === 'vip')
                            <span class="badge badge-vip">VIP</span>
                        @else
                            <span class="badge badge-free">GRATIS</span>
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
                    Arsip VIP & Koleksi Wayang Ki Enthus
                </h2>
                <p class="section-subtitle">Dokumenter langka dan pementasan wayang pesisiran khusus anggota langganan VIP Wasgitel.</p>
            </div>
            <a href="{{ route('movies.index', ['access' => 'vip']) }}" class="view-all-link">
                Lihat Koleksi VIP →
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
                            <span>{{ $movie->release_year }}</span>
                            <span>•</span>
                            <span>{{ $movie->duration_string }}</span>
                            <span>•</span>
                            <span>{{ $movie->category->name }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Section 4: Film Komedi & Tontonan Bebas -->
    <section class="section">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    Koleksi Komedi & Akses Bebas
                </h2>
                <p class="section-subtitle">Dapat langsung diputar tanpa biaya untuk hiburan budaya khas Tegalan.</p>
            </div>
            <a href="{{ route('movies.index', ['access' => 'free']) }}" class="view-all-link">
                Semua Film Bebas →
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
                            <span>{{ $movie->release_year }}</span>
                            <span>•</span>
                            <span>{{ $movie->duration_string }}</span>
                            <span>•</span>
                            <span>{{ $movie->category->name }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <!-- Banner Ajakan Langganan VIP (Professional Minimalist Banner) -->
    <section style="background: #ffffff; border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 2.5rem; margin: 3rem 0; box-shadow: var(--shadow-xs);">
        <div style="max-width: 650px;">
            <span class="badge badge-vip" style="margin-bottom: 0.75rem;">KEANGGOTAAN VIP WASGITEL</span>
            <h2 style="font-size: 1.85rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.65rem; letter-spacing: -0.02em;">
                Dukung Pelestarian Sinema & Budaya Tegal
            </h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.6; margin-bottom: 1.5rem;">
                Mulai dari Rp 29.000/bulan untuk akses tanpa batas ke seluruh arsip wayang cepak langka, film dokumenter Ki Gede Sebayu, dan koleksi tembang Pantura kualitas tinggi.
            </p>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                <a href="{{ route('subscriptions.index') }}" class="btn btn-primary btn-lg">
                    Lihat Paket Berlangganan
                </a>
                <a href="{{ route('movies.index') }}" class="btn btn-secondary btn-lg">
                    Jelajahi Katalog Film
                </a>
            </div>
        </div>
    </section>
</div>
@endsection
