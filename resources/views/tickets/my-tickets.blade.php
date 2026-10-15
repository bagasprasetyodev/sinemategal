@extends('layouts.app')

@section('title', 'Tiket Kula - Tiket Bioskop Rakyat Sinema Tegal')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <span class="badge badge-ticket" style="margin-bottom: 0.5rem;">TIKET BIOSKOP RAKYAT</span>
            <h1 style="font-size: 2.2rem; font-weight: 900; color: var(--text-main); margin-bottom: 0.35rem;">
                🎟️ Tiket Nonton Kula
            </h1>
            <p style="color: var(--text-muted); font-size: 0.95rem;">
                Daftar tiket film premiere kang wis dituku. Tiket aktif bisa digunakake nonton sak wayah-wayah.
            </p>
        </div>
        <a href="{{ route('movies.index', ['access' => 'ticket']) }}" class="btn btn-crimson">
            + Tuku Tiket Film Anyar
        </a>
    </div>

    <!-- Active Tickets List -->
    <div style="margin-bottom: 3.5rem;">
        <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-main); margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
            <span>🟢</span> Tiket Aktif ({{ $activeTickets->count() }})
        </h2>

        @if($activeTickets->count() > 0)
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(360px, 1fr)); gap: 1.5rem;">
                @foreach($activeTickets as $ticket)
                    <div style="background: var(--card-bg); border: 1px dashed #FECDD3; border-radius: var(--radius-md); overflow: hidden; display: flex; box-shadow: var(--shadow-sm); position: relative;">
                        <!-- Left Poster -->
                        <div style="width: 120px; flex-shrink: 0; background: #000;">
                            <img src="{{ $ticket->movie->poster_url }}" alt="{{ $ticket->movie->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>

                        <!-- Right Info -->
                        <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                                    <span class="badge badge-ticket" style="font-size: 0.65rem;">TIKET AKTIF</span>
                                    <span style="font-family: monospace; font-size: 0.75rem; color: #9F1239; background: #FFF1F2; padding: 2px 8px; border-radius: 4px; font-weight: 700;">
                                        {{ $ticket->ticket_code }}
                                    </span>
                                </div>
                                <h3 style="font-size: 1.1rem; color: var(--text-main); font-weight: 800; margin-bottom: 0.35rem;">
                                    {{ $ticket->movie->title }}
                                </h3>
                                <div style="font-size: 0.78rem; color: var(--text-muted); margin-bottom: 0.5rem;">
                                    Kategori: {{ $ticket->movie->category->name }} • {{ $ticket->movie->duration_string }}
                                </div>
                                <div style="font-size: 0.8rem; color: var(--text-muted);">
                                    Masa aktif nganti: <strong style="color: var(--primary);">{{ $ticket->valid_until->format('d M Y, H:i') }}</strong>
                                </div>
                            </div>

                            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                                <a href="{{ route('movies.watch', $ticket->movie->slug) }}" class="btn btn-primary btn-sm" style="flex: 1;">
                                    ▶ Putar Film Saiki
                                </a>
                                <a href="{{ route('movies.show', $ticket->movie->slug) }}" class="btn btn-secondary btn-sm">
                                    Info
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: var(--radius-lg); padding: 3rem; text-align: center; box-shadow: var(--shadow-sm);">
                <div style="font-size: 2.5rem; margin-bottom: 0.75rem;">🎟️</div>
                <h3 style="font-size: 1.2rem; font-weight: 800; color: var(--text-main); margin-bottom: 0.35rem;">Durung Ana Tiket Aktif</h3>
                <p style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1.5rem;">
                    Sampeyan durung tau tuku tiket film bioskop rakyat. Monggo pilih film premiere kang disenengi!
                </p>
                <a href="{{ route('movies.index', ['access' => 'ticket']) }}" class="btn btn-crimson">
                    Jelajahi Film Tiket Premiere
                </a>
            </div>
        @endif
    </div>

    <!-- Expired Tickets List -->
    @if($expiredTickets->count() > 0)
    <div style="margin-bottom: 3.5rem;">
        <h2 style="font-size: 1.15rem; font-weight: 700; color: var(--text-muted); margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
            <span>⏳</span> Riwayat Tiket Kadaluwarsa
        </h2>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem;">
            @foreach($expiredTickets as $et)
                <div style="background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 8px; padding: 1rem; opacity: 0.75;">
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 0.25rem;">
                        <span style="font-family: monospace;">{{ $et->ticket_code }}</span>
                        <span>Kadaluwarsa: {{ $et->valid_until->format('d/m/Y') }}</span>
                    </div>
                    <div style="font-size: 0.95rem; font-weight: 700; color: var(--text-main);">{{ $et->movie->title }}</div>
                    <div style="margin-top: 0.5rem;">
                        <a href="{{ route('movies.show', $et->movie->slug) }}" class="btn btn-secondary btn-sm" style="font-size: 0.75rem;">
                            Tuku Tiket Anyar
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
