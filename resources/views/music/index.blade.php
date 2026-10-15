@extends('layouts.app')

@section('title', 'Nada Tegalan - Streaming Musik & Lagu Daerah Khas Tegal')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Music Header Banner (Halobun Minimalist Style) -->
    <div style="background: linear-gradient(135deg, var(--green-50), var(--cyan-50)); border: 1px solid var(--border-subtle); border-radius: var(--radius-xl); padding: 3rem 2.5rem; margin-bottom: 2.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 2rem; box-shadow: var(--shadow-sm);">
        <div style="max-width: 650px;">
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                <span class="brand-badge" style="background: var(--green-100); color: var(--green-700); border-color: rgba(22, 163, 74, 0.4);">
                    NADA TEGALAN
                </span>
                <span style="font-size: 0.85rem; color: var(--text-dim);">Spotify & YouTube Music Pesisiran</span>
            </div>
            <h1 style="font-size: 2.6rem; font-weight: 900; color: var(--text-main); line-height: 1.15; margin-bottom: 0.75rem;">
                Tembang & Gending <span class="text-gold">Tegal Laka-Laka</span>
            </h1>
            <p style="color: var(--text-muted); font-size: 1rem; line-height: 1.6; margin-bottom: 1.5rem;">
                Rungokna lagu-lagu legendaris alm. Ki Enthus Susmono, Imam Joend, campursari ngapak, lan tembang mistis Sintren. Player ngisor layar bakal tetep muter sanajan sampeyan mbukak kaca liyane!
            </p>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap;">
                @if($tracks->first())
                    <button onclick="window.playTrackById({{ $tracks->first()->id }})" class="btn btn-primary btn-lg">
                        <span>▶️</span> Putar Lagu Populer
                    </button>
                @endif
                <a href="{{ route('subscriptions.index') }}" class="btn btn-secondary btn-lg">
                    <span>👑</span> Upgrade VIP Audio Hi-Fi
                </a>
            </div>
        </div>

        <div style="width: 200px; height: 200px; border-radius: 20px; overflow: hidden; box-shadow: var(--shadow-md); border: 2px solid var(--border-subtle); flex-shrink: 0;">
            <img src="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=500" alt="Music Cover" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    <!-- Playlists Section (Spotify Style Carousels) -->
    <div style="margin-bottom: 3.5rem;">
        <div class="section-header">
            <div>
                <h2 class="section-title">
                    <span>📻</span> Playlist Pilihan Dulur Wasgitel
                </h2>
                <p class="section-subtitle">Daftar putar kurasi kusus nggo kanca ngeteh poci utawa nyantai sore.</p>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
            @foreach($playlists as $pl)
                <div style="background: var(--bg-card); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.25rem; display: flex; flex-direction: column; gap: 1rem; box-shadow: var(--shadow-xs); transition: transform 0.2s;" onmouseover="this.style.transform='translateY(-4px)'" onmouseout="this.style.transform='translateY(0)'">
                    <div style="position: relative; width: 100%; aspect-ratio: 16/9; border-radius: 10px; overflow: hidden;">
                        <img src="{{ $pl->cover_url }}" alt="{{ $pl->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        <button onclick="window.playPlaylist({{ $pl->id }})" class="movie-play-btn" style="opacity: 1;" title="Putar Playlist">
                            ▶
                        </button>
                    </div>
                    <div>
                        <h3 style="font-size: 1.1rem; color: var(--text-main); margin-bottom: 0.35rem;">{{ $pl->title }}</h3>
                        <p style="font-size: 0.82rem; color: var(--text-dim); line-height: 1.5;">{{ $pl->description }}</p>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto; border-top: 1px solid var(--border-subtle); padding-top: 0.75rem; font-size: 0.8rem; color: var(--text-muted);">
                        <span>{{ $pl->tracks->count() }} Tembang</span>
                        <button onclick="window.playPlaylist({{ $pl->id }})" class="btn btn-secondary btn-sm" style="font-size: 0.75rem;">
                            ▶ Putar Kabeh
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- All Tracks List (Spotify Tracklist Table View) -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 3.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
            <div>
                <h2 style="font-size: 1.4rem; color: #fff; margin-bottom: 0.2rem;">
                    Daftar Tembang Tegalan
                </h2>
                <p style="font-size: 0.85rem; color: var(--text-muted);">
                    Klik judhul lagu utawa tombol putar nggo langsung nyetel audio.
                </p>
            </div>

            <form method="GET" action="{{ route('music.index') }}" style="display: flex; gap: 0.5rem;">
                <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Golek tembang utawa artis..." style="width: 240px; padding: 0.5rem 0.85rem; font-size: 0.88rem;">
                <button type="submit" class="btn btn-primary btn-sm">Golek</button>
                @if(request('q'))
                    <a href="{{ route('music.index') }}" class="btn btn-secondary btn-sm">Reset</a>
                @endif
            </form>
        </div>

        <!-- Track Table -->
        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
            @foreach($tracks as $index => $track)
                <div class="track-card" data-track-id="{{ $track->id }}">
                    <div style="font-family: monospace; font-size: 0.85rem; color: var(--text-dim); width: 24px; text-align: center;">
                        {{ $index + 1 }}
                    </div>

                    <div class="track-cover-wrap" onclick="window.playTrackById({{ $track->id }})">
                        <img src="{{ $track->cover_url }}" alt="{{ $track->title }}" class="track-cover">
                        <div class="track-play-hover">▶</div>
                    </div>

                    <div class="track-card-info" onclick="window.playTrackById({{ $track->id }})">
                        <div class="track-card-title">{{ $track->title }}</div>
                        <div class="track-card-artist">
                            {{ $track->artist }} • <span style="color: var(--text-dim);">{{ $track->album ?? 'Single Tegalan' }}</span>
                        </div>
                    </div>

                    <div style="font-size: 0.82rem; color: var(--gold-400); width: 140px; display: none; @media(min-width: 768px){display:block;}">
                        {{ $track->genre ?? 'Pop Ngapak' }}
                    </div>

                    <div class="track-card-right">
                        <div style="display: flex; align-items: center; gap: 0.75rem;">
                            @if($track->access_type === 'vip')
                                <span class="badge badge-vip">VIP</span>
                            @else
                                <span class="badge badge-free">GRATIS</span>
                            @endif

                            <a href="{{ route('music.show', $track->slug) }}" class="btn btn-secondary btn-sm" style="font-size: 0.72rem; padding: 0.2rem 0.5rem;" title="Lirik & Terjemahan">
                                📜 Lirik
                            </a>

                            <button onclick="window.playTrackById({{ $track->id }})" class="btn btn-primary btn-sm" style="padding: 0.35rem 0.75rem; border-radius: 50%;">
                                ▶
                            </button>
                        </div>
                        <span class="track-card-duration">{{ $track->duration_string }}</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
