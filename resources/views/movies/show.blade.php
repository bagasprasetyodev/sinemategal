@extends('layouts.app')

@section('title', $movie->title . ' - Sinema Tegal')

@section('content')
<div class="container" style="padding-top: 1.5rem;">
    <!-- Breadcrumb -->
    <div style="font-size: 0.85rem; color: var(--text-dim); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.5rem;">
        <a href="{{ route('home') }}" style="color: var(--text-muted);">Beranda</a>
        <span>/</span>
        <a href="{{ route('movies.index') }}" style="color: var(--text-muted);">Sinema</a>
        <span>/</span>
        <span style="color: var(--gold-400);">{{ $movie->title }}</span>
    </div>

    <!-- Movie Detail Header Banner -->
    <div style="position: relative; border-radius: var(--radius-lg); overflow: hidden; background: #000; border: 1px solid var(--border-subtle); margin-bottom: 2.5rem; min-height: 480px; display: flex; align-items: flex-end;">
        <div style="position: absolute; inset: 0; background-image: url('{{ $movie->banner_url }}'); background-size: cover; background-position: center; filter: brightness(0.4);"></div>
        <div style="position: absolute; inset: 0; background: linear-gradient(180deg, transparent 0%, rgba(8, 12, 20, 0.95) 100%);"></div>

        <div style="position: relative; z-index: 2; padding: 3rem; display: flex; gap: 2.5rem; width: 100%; align-items: flex-end; flex-wrap: wrap;">
            <!-- Poster Card -->
            <div style="width: 240px; aspect-ratio: 2/3; border-radius: var(--radius-md); overflow: hidden; box-shadow: var(--shadow-lg); border: 2px solid var(--border-subtle); flex-shrink: 0;">
                <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>

            <!-- Details -->
            <div style="flex: 1; min-width: 300px;">
                <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; margin-bottom: 0.75rem;">
                    <span class="badge {{ $movie->access_badge['class'] }}">
                        {{ $movie->access_badge['label'] }}
                    </span>
                    <span class="hero-tag">★ {{ $movie->rating }} / 5.0</span>
                    <span class="hero-tag">⏱️ {{ $movie->duration_string }}</span>
                    <span class="hero-tag">📅 {{ $movie->release_year }}</span>
                    @if($movie->location_tag)
                        <span class="hero-tag" style="color: var(--cyan-400);">📍 {{ $movie->location_tag }}</span>
                    @endif
                </div>

                <h1 style="font-size: 2.8rem; font-weight: 900; color: #fff; line-height: 1.15; margin-bottom: 0.5rem;">
                    {{ $movie->title }}
                </h1>

                @if($movie->tagline)
                    <p style="font-size: 1.1rem; font-style: italic; color: var(--gold-400); margin-bottom: 1.25rem;">
                        "{{ $movie->tagline }}"
                    </p>
                @endif

                <!-- Call to action button -->
                <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-top: 1.5rem;">
                    @if($accessCheck['allowed'])
                        <a href="{{ route('movies.watch', $movie->slug) }}" class="btn btn-primary btn-lg" style="box-shadow: 0 0 25px var(--gold-glow);">
                            <span>▶️</span> Putar Film Saiki
                        </a>
                    @elseif($movie->access_type === 'ticket')
                        <button onclick="document.getElementById('ticketModal').classList.add('is-open')" class="btn btn-crimson btn-lg">
                            <span>🎟️</span> Tuku Tiket Premiere ({{ $movie->formatted_price }})
                        </button>
                    @else
                        <a href="{{ route('subscriptions.index') }}" class="btn btn-primary btn-lg">
                            <span>👑</span> Langganan VIP Sebayu nggo Nonton
                        </a>
                    @endif

                    <a href="{{ route('movies.watch', $movie->slug) }}" class="btn btn-secondary btn-lg">
                        <span>🎬</span> Cuplikan / Trailer
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Columns -->
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2.5rem; margin-bottom: 3.5rem;">
        <!-- Left: Synopsis & Info -->
        <div>
            <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 2rem;">
                <h3 style="font-size: 1.3rem; color: #fff; margin-bottom: 1rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.5rem;">
                    📖 Sinopsis & Latar Budaya
                </h3>
                <p style="font-size: 1.05rem; line-height: 1.8; color: #cbd5e1; margin-bottom: 1.5rem;">
                    {{ $movie->synopsis }}
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; background: rgba(0,0,0,0.25); padding: 1.25rem; border-radius: 12px; border: 1px solid var(--border-subtle);">
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Sutradara</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #fff;">{{ $movie->director }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Paraga (Pemeran)</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: #fff;">{{ $movie->cast ?? 'Seniman Teater Tegal' }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Kategori Seni</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: var(--gold-400);">{{ $movie->category->name }}</div>
                    </div>
                    <div>
                        <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Total Penonton</div>
                        <div style="font-size: 0.95rem; font-weight: 700; color: var(--cyan-400);">{{ number_format($movie->views_count) }} kali</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Access Status & Ticket Box -->
        <div>
            <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.75rem;">
                <h4 style="font-size: 1.1rem; color: #fff; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.5rem;">
                    <span>🛡️</span> Status Akses Panjenengan
                </h4>

                @if($accessCheck['allowed'])
                    <div style="background: rgba(16, 185, 129, 0.12); border: 1px solid rgba(16, 185, 129, 0.35); border-radius: 12px; padding: 1.25rem; text-align: center; margin-bottom: 1.25rem;">
                        <div style="font-size: 2rem; margin-bottom: 0.35rem;">🎉</div>
                        <div style="font-weight: 700; color: var(--emerald-500); font-size: 1rem; margin-bottom: 0.2rem;">
                            Akses Mbukak & Siap Ditonton!
                        </div>
                        <div style="font-size: 0.82rem; color: #cbd5e1;">
                            @if($movie->access_type === 'free')
                                Film kiye gratis disetel nggo kabeh pamiarsa.
                            @elseif($hasTicket)
                                Tiket premiere aktif wis kadhaptar ning akun sampeyan.
                            @else
                                Akun sampeyan duwe hak akses VIP aktif.
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('movies.watch', $movie->slug) }}" class="btn btn-primary" style="width: 100%;">
                        ▶ Putar Film Saiki
                    </a>
                @elseif($movie->access_type === 'ticket')
                    <div style="background: rgba(244, 63, 94, 0.12); border: 1px solid rgba(244, 63, 94, 0.35); border-radius: 12px; padding: 1.25rem; text-align: center; margin-bottom: 1.25rem;">
                        <div style="font-size: 2rem; margin-bottom: 0.35rem;">🎟️</div>
                        <div style="font-weight: 700; color: #fda4af; font-size: 1rem; margin-bottom: 0.2rem;">
                            Butuh Tiket Bioskop Rakyat
                        </div>
                        <div style="font-size: 0.82rem; color: #cbd5e1; margin-bottom: 0.75rem;">
                            Film premiere eksklusif. Tuku tiket sapisan, aktif 7 dina.
                        </div>
                        <div style="font-size: 1.6rem; font-weight: 900; color: #fff;">
                            {{ $movie->formatted_price }}
                        </div>
                    </div>

                    <button onclick="document.getElementById('ticketModal').classList.add('is-open')" class="btn btn-crimson" style="width: 100%;">
                        🎟️ Tuku Tiket Nonton Saiki
                    </button>
                @else
                    <div style="background: rgba(245, 158, 11, 0.12); border: 1px solid rgba(245, 158, 11, 0.35); border-radius: 12px; padding: 1.25rem; text-align: center; margin-bottom: 1.25rem;">
                        <div style="font-size: 2rem; margin-bottom: 0.35rem;">👑</div>
                        <div style="font-weight: 700; color: var(--gold-400); font-size: 1rem; margin-bottom: 0.2rem;">
                            Khusus Anggota VIP Sebayu
                        </div>
                        <div style="font-size: 0.82rem; color: #cbd5e1;">
                            Langganan VIP nggo mbukak film kiye sarta puluhan rekaman langka liyane.
                        </div>
                    </div>

                    <a href="{{ route('subscriptions.index') }}" class="btn btn-primary" style="width: 100%;">
                        👑 Deleng Paket Langganan VIP
                    </a>
                @endif
            </div>
        </div>
    </div>

    <!-- Related Movies Section -->
    @if($relatedMovies->count() > 0)
    <section class="section">
        <h3 class="section-title" style="margin-bottom: 1.5rem;">
            <span>🎞️</span> Film Serupa Khas Tegalan
        </h3>
        <div class="grid-movies">
            @foreach($relatedMovies as $rel)
                <div class="movie-card">
                    <div class="movie-poster-wrap">
                        <img src="{{ $rel->poster_url }}" alt="{{ $rel->title }}" class="movie-poster" loading="lazy">
                        <div class="movie-badge-top">
                            <span class="badge {{ $rel->access_badge['class'] }}">{{ $rel->access_badge['label'] }}</span>
                        </div>
                        <div class="movie-rating">★ {{ $rel->rating }}</div>
                        <div class="movie-card-overlay"></div>
                        <a href="{{ route('movies.show', $rel->slug) }}" class="movie-play-btn">▶</a>
                    </div>
                    <div class="movie-info">
                        <a href="{{ route('movies.show', $rel->slug) }}">
                            <h4 class="movie-title">{{ $rel->title }}</h4>
                        </a>
                        <div class="movie-meta">
                            <span>{{ $rel->duration_string }}</span>
                            <span>•</span>
                            <span>{{ $rel->release_year }}</span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    @endif
</div>

<!-- Modal Beli Tiket (Pay-Per-View Checkout) -->
@if($movie->access_type === 'ticket')
<div id="ticketModal" class="modal-backdrop">
    <div class="modal-box">
        <button class="modal-close">✕</button>
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 2.2rem; margin-bottom: 0.35rem;">🎟️</div>
            <h3 style="font-size: 1.35rem; color: #fff;">Tuku Tiket Premiere</h3>
            <p style="color: var(--text-muted); font-size: 0.88rem;">{{ $movie->title }}</p>
        </div>

        <form method="POST" action="{{ route('movies.buy-ticket', $movie->id) }}">
            @csrf
            
            <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.6rem; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Biaya Tiket Bioskop</span>
                    <span style="font-weight: 700; color: #fff;">{{ $movie->formatted_price }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.6rem; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Masa Berlaku Nonton</span>
                    <span style="color: var(--cyan-400); font-weight: 600;">7 Dina (Bebas Replay)</span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; border-top: 1px dashed var(--border-subtle); padding-top: 0.6rem;">
                    <span style="color: var(--text-muted);">Saldo Dompet Bahari Sampeyan</span>
                    <span style="font-weight: 700; color: var(--gold-400);">
                        Rp {{ number_format(Auth::user()->wallet_balance ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Pilih Cara Pembayaran:</label>
                <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer;">
                        <input type="radio" name="payment_method" value="dompet_bahari" checked>
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">💰 Dompet Bahari (Instan 1-Klik)</div>
                            <div style="font-size: 0.78rem; color: var(--text-dim);">Dipotong langsung saka saldo akun sampeyan</div>
                        </div>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer;">
                        <input type="radio" name="payment_method" value="qris">
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">📱 QRIS (GoPay, OVO, ShopeePay, Dana)</div>
                            <div style="font-size: 0.78rem; color: var(--text-dim);">Simulasi pembayaran QRIS instan</div>
                        </div>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer;">
                        <input type="radio" name="payment_method" value="va_bca">
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">🏦 Virtual Account Bank (BCA / BRI / Mandiri)</div>
                            <div style="font-size: 0.78rem; color: var(--text-dim);">Transfer bank otomatis</div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-crimson" style="width: 100%; padding: 0.85rem; font-size: 1rem;">
                🎟️ Konfirmasi Pembelian Tiket
            </button>
        </form>
    </div>
</div>
@endif

@endsection
