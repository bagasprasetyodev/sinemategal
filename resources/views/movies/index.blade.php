@extends('layouts.app')

@section('title', 'Katalog Sinema & Film Tegalan')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Catalog Header -->
    <div style="margin-bottom: 2rem;">
        <h1 style="font-size: 2.4rem; color: #fff; margin-bottom: 0.5rem;">
            🎬 Katalog Sinema & Film Tegalan
        </h1>
        <p style="color: var(--text-muted); font-size: 1rem;">
            Koleksi film cerita, dokumenter tradisi pesisir, seni Sintren, teater wayang cepak, sarta drama komedi basa Tegal.
        </p>
    </div>

    <!-- Search & Filter Bar -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.5rem; margin-bottom: 2.5rem;">
        <form method="GET" action="{{ route('movies.index') }}" style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" style="flex: 1; min-width: 260px;" placeholder="Golek judul film, sutradara, paraga (pemeran), utawa lokasi (Guci, Muarareja)...">
                
                <select name="access" class="form-control" style="width: auto; min-width: 180px;">
                    <option value="">Kabeh Tipe Akses</option>
                    <option value="free" {{ request('access') === 'free' ? 'selected' : '' }}>🟢 Gratis Ditonton</option>
                    <option value="vip" {{ request('access') === 'vip' ? 'selected' : '' }}>👑 VIP Sebayu</option>
                    <option value="ticket" {{ request('access') === 'ticket' ? 'selected' : '' }}>🎟️ Tiket Premiere</option>
                </select>

                <button type="submit" class="btn btn-primary">
                    🔍 Saring Film
                </button>
                @if(request()->anyFilled(['q', 'category', 'access']))
                    <a href="{{ route('movies.index') }}" class="btn btn-secondary">
                        Reset
                    </a>
                @endif
            </div>

            <!-- Category Pills -->
            <div style="display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; padding-top: 0.5rem; border-top: 1px solid var(--border-subtle);">
                <span style="font-size: 0.8rem; color: var(--text-dim); font-weight: 600;">Kategori:</span>
                <a href="{{ route('movies.index', request()->except('category')) }}" 
                   class="demo-pill {{ !request('category') ? 'is-active' : '' }}">
                   Kabeh (Semua)
                </a>
                @foreach($categories as $cat)
                    <a href="{{ route('movies.index', array_merge(request()->query(), ['category' => $cat->slug])) }}" 
                       class="demo-pill {{ request('category') === $cat->slug ? 'is-active' : '' }}">
                       {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </form>
    </div>

    <!-- Movies Grid -->
    @if($movies->count() > 0)
        <div class="grid-movies">
            @foreach($movies as $movie)
                <div class="movie-card">
                    <div class="movie-poster-wrap">
                        <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="movie-poster" loading="lazy">
                        <div class="movie-badge-top">
                            <span class="badge {{ $movie->access_badge['class'] }}">
                                {{ $movie->access_badge['label'] }}
                            </span>
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

        <div style="margin-top: 2.5rem; display: flex; justify-content: center;">
            {{ $movies->links() }}
        </div>
    @else
        <div style="text-align: center; padding: 4rem 1.5rem; background: var(--bg-surface); border-radius: var(--radius-lg); border: 1px solid var(--border-subtle);">
            <div style="font-size: 3rem; margin-bottom: 1rem;">🎬</div>
            <h3 style="font-size: 1.3rem; color: #fff; margin-bottom: 0.5rem;">Ora Ana Film Kang Cocog</h3>
            <p style="color: var(--text-muted); font-size: 0.95rem; margin-bottom: 1.5rem;">
                Coba ganti tembung kunci pencarian utawa atur maneh saringan kategori.
            </p>
            <a href="{{ route('movies.index') }}" class="btn btn-primary">
                Deleng Kabeh Film
            </a>
        </div>
    @endif
</div>
@endsection
