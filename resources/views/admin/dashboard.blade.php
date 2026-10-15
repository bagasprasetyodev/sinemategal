@extends('layouts.app')

@section('title', 'Kurator Hub & Dashboard Admin - Sinema Tegal')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Admin Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.35rem;">
                <span class="badge badge-vip">KURATOR & ADMIN BUDAYA</span>
                <span style="font-size: 0.85rem; color: var(--text-dim);">Pusat Manajemen Sinema & Tembang Tegalan</span>
            </div>
            <h1 style="font-size: 2.4rem; font-weight: 900; color: #fff;">
                ⚙️ Dashboard Kurator Sinema Tegal
            </h1>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="#tambahFilm" class="btn btn-primary btn-sm">+ Tambah Film Anyar</a>
            <a href="#tambahLagu" class="btn btn-cyan btn-sm">+ Tambah Tembang Anyar</a>
        </div>
    </div>

    <!-- Stats Cards Grid -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 3rem;">
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem;">
            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Total Film & Sinema</div>
            <div style="font-size: 2rem; font-weight: 900; color: #fff; margin: 0.25rem 0;">{{ $stats['total_movies'] }}</div>
            <div style="font-size: 0.78rem; color: var(--gold-400);">Dokumenter & Cerita</div>
        </div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem;">
            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Total Tembang Tegalan</div>
            <div style="font-size: 2rem; font-weight: 900; color: #fff; margin: 0.25rem 0;">{{ $stats['total_tracks'] }}</div>
            <div style="font-size: 0.78rem; color: var(--cyan-400);">Koleksi Nada Pantura</div>
        </div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem;">
            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Anggota VIP Aktif</div>
            <div style="font-size: 2rem; font-weight: 900; color: var(--gold-400); margin: 0.25rem 0;">{{ $stats['active_vips'] }}</div>
            <div style="font-size: 0.78rem; color: var(--text-dim);">Pelanggan Wasgitel</div>
        </div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem;">
            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Tiket Bioskop Pay-Per-View</div>
            <div style="font-size: 2rem; font-weight: 900; color: #fda4af; margin: 0.25rem 0;">{{ $stats['tickets_sold'] }}</div>
            <div style="font-size: 0.78rem; color: var(--crimson-500);">Tiket Premiere Pay-Per-View</div>
        </div>
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-md); padding: 1.5rem;">
            <div style="font-size: 0.8rem; color: var(--text-dim); text-transform: uppercase;">Total Omset Tiket & VIP</div>
            <div style="font-size: 1.6rem; font-weight: 900; color: var(--emerald-500); margin: 0.25rem 0;">
                Rp {{ number_format($stats['ticket_revenue'] + $stats['sub_revenue'], 0, ',', '.') }}
            </div>
            <div style="font-size: 0.78rem; color: var(--text-dim);">Dukungan Sineas Lokal</div>
        </div>
    </div>

    <!-- Forms Grid: Add Movie & Add Track -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3.5rem;">
        <!-- Form Tambah Film -->
        <div id="tambahFilm" style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
            <h3 style="font-size: 1.25rem; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>🎬</span> Unggah / Tambah Film Anyar
            </h3>

            <form method="POST" action="{{ route('admin.movies.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul Film:</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Sang Sintren saka Pesisir Muarareja" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Kategori Budaya:</label>
                        <select name="category_id" class="form-control" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Tipe Akses Nonton:</label>
                        <select name="access_type" class="form-control" required>
                            <option value="free">🟢 Gratis (Free)</option>
                            <option value="vip">👑 VIP Sebayu</option>
                            <option value="ticket">🎟️ Tiket Premiere (Pay-Per-View)</option>
                        </select>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Harga Tiket (Yen Tipe Tiket):</label>
                        <input type="number" name="ticket_price" value="15000" step="1000" class="form-control">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Durasi (Menit):</label>
                        <input type="number" name="duration_minutes" value="90" class="form-control" required>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Sutradara:</label>
                        <input type="text" name="director" value="Komunitas Sineas Tegal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Tahun Rilis:</label>
                        <input type="number" name="release_year" value="2026" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Lokasi Ikonik Tegal:</label>
                    <input type="text" name="location_tag" class="form-control" placeholder="Contoh: Guci, Muarareja, Danawarih">
                </div>

                <div class="form-group">
                    <label class="form-label">URL Video Stream (MP4/HLS):</label>
                    <input type="url" name="video_url" value="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4" class="form-control" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">URL Poster (Portrait):</label>
                        <input type="url" name="poster_url" value="https://images.unsplash.com/photo-1518709268805-4e9042af9f23?w=600" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">URL Banner (Landscape):</label>
                        <input type="url" name="banner_url" value="https://images.unsplash.com/photo-1509198397868-475647b2a1e5?w=1400" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">Sinopsis Cerita & Nilai Budaya:</label>
                    <textarea name="synopsis" rows="3" class="form-control" placeholder="Kisah film lan pesan moral basa Tegal..." required></textarea>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    🎬 Simpen & Tayangna Film
                </button>
            </form>
        </div>

        <!-- Form Tambah Tembang / Track -->
        <div id="tambahLagu" style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
            <h3 style="font-size: 1.25rem; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>🎵</span> Unggah / Tambah Tembang Anyar
            </h3>

            <form method="POST" action="{{ route('admin.tracks.store') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Judul Tembang:</label>
                    <input type="text" name="title" class="form-control" placeholder="Contoh: Sate Tegal Batibul Asri" required>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Penyanyi / Artis:</label>
                        <input type="text" name="artist" class="form-control" placeholder="Contoh: Imam Joend Pantura" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Genre:</label>
                        <input type="text" name="genre" value="Pop Ngapak Pantura" class="form-control">
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label class="form-label">Durasi (Detik):</label>
                        <input type="number" name="duration_seconds" value="210" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Akses Tembang:</label>
                        <select name="access_type" class="form-control" required>
                            <option value="free">🟢 Gratis</option>
                            <option value="vip">👑 VIP Khusus</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label">URL Audio Stream (MP3):</label>
                    <input type="url" name="audio_url" value="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-1.mp3" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">URL Cover Album (Persegi):</label>
                    <input type="url" name="cover_url" value="https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?w=500" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Lirik Dialek Tegal & Terjemahan:</label>
                    <textarea name="lyrics" rows="4" class="form-control" placeholder="Tulis lirik basa ngapak lan tegese..."></textarea>
                </div>

                <button type="submit" class="btn btn-cyan" style="width: 100%;">
                    🎵 Simpen & Rilis Tembang
                </button>
            </form>
        </div>
    </div>

    <!-- Manage Existing Films & Tracks Table -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3.5rem;">
        <!-- Manage Movies List -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.75rem;">
            <h3 style="font-size: 1.15rem; color: #fff; margin-bottom: 1rem;">🎬 Koleksi Film Aktif</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
                @foreach($movies as $m)
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; background: rgba(0,0,0,0.25); padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-subtle);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                            <img src="{{ $m->poster_url }}" alt="{{ $m->title }}" style="width: 40px; height: 50px; border-radius: 4px; object-fit: cover;">
                            <div style="min-width: 0;">
                                <div style="font-size: 0.9rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $m->title }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-dim);">{{ $m->access_type }} • {{ $m->duration_string }}</div>
                            </div>
                        </div>
                        <form action="{{ route('admin.movies.destroy', $m->id) }}" method="POST" onsubmit="return confirm('Hapus film kiye?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: transparent; border: none; color: var(--crimson-500); cursor: pointer; font-size: 1.1rem;" title="Hapus">
                                🗑️
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Manage Tracks List -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 1.75rem;">
            <h3 style="font-size: 1.15rem; color: #fff; margin-bottom: 1rem;">🎵 Koleksi Tembang Aktif</h3>
            <div style="display: flex; flex-direction: column; gap: 0.75rem; max-height: 400px; overflow-y: auto;">
                @foreach($tracks as $t)
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem; background: rgba(0,0,0,0.25); padding: 0.75rem; border-radius: 8px; border: 1px solid var(--border-subtle);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; min-width: 0;">
                            <img src="{{ $t->cover_url }}" alt="{{ $t->title }}" style="width: 44px; height: 44px; border-radius: 6px; object-fit: cover;">
                            <div style="min-width: 0;">
                                <div style="font-size: 0.9rem; font-weight: 700; color: #fff; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $t->title }}</div>
                                <div style="font-size: 0.75rem; color: var(--text-dim);">{{ $t->artist }} • {{ $t->duration_string }}</div>
                            </div>
                        </div>
                        <form action="{{ route('admin.tracks.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus tembang kiye?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="background: transparent; border: none; color: var(--crimson-500); cursor: pointer; font-size: 1.1rem;" title="Hapus">
                                🗑️
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
