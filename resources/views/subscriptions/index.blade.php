@extends('layouts.app')

@section('title', 'Langganan VIP Sebayu - Sinema Tegal')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Pricing Header -->
    <div style="text-align: center; max-width: 720px; margin: 0 auto 3rem;">
        <span class="badge badge-vip" style="margin-bottom: 0.75rem; font-size: 0.82rem; padding: 0.35rem 0.85rem;">
            👑 LANGGANAN DULUR WASGITEL
        </span>
        <h1 style="font-size: 2.8rem; font-weight: 900; color: var(--text-main); margin-bottom: 0.75rem;">
            Pilih Paket VIP & Dukung Sineas Tegal
        </h1>
        <p style="color: var(--text-muted); font-size: 1.05rem; line-height: 1.6;">
            Akses bebas kabeh arsip wayang pesisiran Ki Enthus, film dokumenter sejarah, lan alunan tembang Tegalan kualitas dhuwur tanpa iklan.
        </p>
    </div>

    <!-- Active Subscription Banner if user is active VIP -->
    @if(Auth::check() && Auth::user()->hasVipAccess())
    <div style="background: linear-gradient(135deg, var(--gold-50), var(--green-50)); border: 1px solid var(--gold-500); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 3rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.5rem; box-shadow: var(--shadow-sm);">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--gold-700); font-weight: 800; font-size: 0.9rem; margin-bottom: 0.35rem;">
                <span>👑</span> STATUS LANGGANAN AKTIF
            </div>
            <div style="font-size: 1.6rem; font-weight: 900; color: var(--text-main); margin-bottom: 0.25rem;">
                {{ Auth::user()->vip_plan ?? 'Paket VIP Aktif' }}
            </div>
            <div style="color: var(--text-muted); font-size: 0.9rem;">
                Aktif nganti: <strong style="color: var(--green-700);">{{ Auth::user()->vip_expires_at ? Auth::user()->vip_expires_at->format('d F Y, H:i') : 'Permanen' }}</strong>
            </div>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('movies.index', ['access' => 'vip']) }}" class="btn btn-primary">
                🎬 Nonton Film VIP
            </a>
            <a href="{{ route('music.index') }}" class="btn btn-secondary">
                🎵 Putar Nada Tegalan
            </a>
        </div>
    </div>
    @endif

    <!-- Pricing Cards Grid -->
    <div class="pricing-grid">
        @foreach($plans as $plan)
            <div class="pricing-card {{ $plan['popular'] ? 'is-popular' : '' }}">
                @if($plan['popular'])
                    <div class="pricing-badge">{{ $plan['badge'] }}</div>
                @endif

                <h3 style="font-size: 1.5rem; color: #fff; margin-bottom: 0.25rem;">
                    {{ $plan['name'] }}
                </h3>
                <div style="font-size: 0.85rem; color: var(--gold-400); font-style: italic;">
                    {{ $plan['tagline'] }}
                </div>

                <div class="pricing-price">
                    <span style="font-size: 1.3rem; vertical-align: super; color: var(--text-muted); font-weight: 600;">Rp</span>
                    {{ number_format($plan['price'], 0, ',', '.') }}
                    <span style="font-size: 0.9rem; font-weight: 400; color: var(--text-dim);">/ {{ $plan['duration_days'] }} dina</span>
                </div>

                <ul class="pricing-features">
                    @foreach($plan['features'] as $feat)
                        <li class="pricing-feature-item">
                            <span style="color: var(--emerald-500); font-weight: bold;">✓</span>
                            <span>{{ $feat }}</span>
                        </li>
                    @endforeach
                </ul>

                <button onclick="openSubscribeModal('{{ $plan['code'] }}', '{{ $plan['name'] }}', {{ $plan['price'] }})" 
                        class="btn {{ $plan['popular'] ? 'btn-primary' : 'btn-secondary' }}" 
                        style="width: 100%; padding: 0.85rem; margin-top: auto;">
                    {{ Auth::check() && Auth::user()->hasVipAccess() ? 'Perpanjang Langganan' : 'Pilih Paket Kiye' }}
                </button>
            </div>
        @endforeach
    </div>

    <!-- FAQ / Pitakonan Umum -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2.5rem; margin: 3rem 0;">
        <h3 style="font-size: 1.3rem; color: #fff; margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-subtle); padding-bottom: 0.75rem;">
            ❓ Pitakonan Kerep Ditakokna (FAQ)
        </h3>

        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            <div>
                <h4 style="font-size: 1rem; color: var(--gold-400); margin-bottom: 0.4rem;">
                    Kepriye carane mbayar langganan VIP?
                </h4>
                <p style="font-size: 0.88rem; color: #cbd5e1; line-height: 1.6;">
                    Sampeyan bisa mbayar instan nganggo saldo <strong>Dompet Bahari</strong> (bisa top up langsung), utawa nganggo simulasi QRIS lan Transfer Bank Virtual Account.
                </p>
            </div>
            <div>
                <h4 style="font-size: 1rem; color: var(--gold-400); margin-bottom: 0.4rem;">
                    Apa bedane film VIP karo Tiket Premiere?
                </h4>
                <p style="font-size: 0.88rem; color: #cbd5e1; line-height: 1.6;">
                    Film VIP bebas ditonton kabeh dening anggota langganan VIP. Dene Tiket Premiere yaiku film bioskop anyar kang mbutuhake tuku tiket satuan (Pay-Per-View). Anggota VIP oleh diskon mirah tuku tiket!
                </p>
            </div>
            <div>
                <h4 style="font-size: 1rem; color: var(--gold-400); margin-bottom: 0.4rem;">
                    Bisa disetel ning HP Android utawa Laptop?
                </h4>
                <p style="font-size: 0.88rem; color: #cbd5e1; line-height: 1.6;">
                    Bisa banget! Sinema Tegal wis dirancang responsif, enteng, lan ramah ning layar smartphone, tablet, utawa layar monitor gedhe.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Modal Checkout Langganan VIP -->
<div id="subModal" class="modal-backdrop">
    <div class="modal-box">
        <button class="modal-close" onclick="document.getElementById('subModal').classList.remove('is-open')">✕</button>
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <div style="font-size: 2.2rem; margin-bottom: 0.35rem;">👑</div>
            <h3 style="font-size: 1.35rem; color: #fff;">Konfirmasi Langganan VIP</h3>
            <p id="subPlanTitle" style="color: var(--gold-400); font-weight: 700; font-size: 1.1rem;"></p>
        </div>

        <form method="POST" action="{{ route('subscriptions.subscribe') }}">
            @csrf
            <input type="hidden" name="plan_code" id="subPlanCode">

            <div style="background: rgba(255, 255, 255, 0.04); border: 1px solid var(--border-subtle); border-radius: 12px; padding: 1.25rem; margin-bottom: 1.5rem;">
                <div style="display: flex; justify-content: space-between; margin-bottom: 0.6rem; font-size: 0.9rem;">
                    <span style="color: var(--text-muted);">Biaya Paket</span>
                    <span id="subPlanPrice" style="font-weight: 800; color: #fff; font-size: 1.1rem;"></span>
                </div>
                <div style="display: flex; justify-content: space-between; font-size: 0.9rem; border-top: 1px dashed var(--border-subtle); padding-top: 0.6rem;">
                    <span style="color: var(--text-muted);">Saldo Dompet Bahari Sampeyan</span>
                    <span style="font-weight: 700; color: var(--gold-400);">
                        Rp {{ number_format(Auth::user()->wallet_balance ?? 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Metode Pembayaran:</label>
                <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer;">
                        <input type="radio" name="payment_method" value="dompet_bahari" checked>
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">💰 Dompet Bahari (Instan 1-Klik)</div>
                            <div style="font-size: 0.78rem; color: var(--text-dim);">Langsung potong saka saldo akun sampeyan</div>
                        </div>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; background: rgba(0,0,0,0.3); border: 1px solid var(--border-subtle); padding: 0.75rem; border-radius: 8px; cursor: pointer;">
                        <input type="radio" name="payment_method" value="qris">
                        <div>
                            <div style="font-weight: 700; color: #fff; font-size: 0.9rem;">📱 QRIS Instan</div>
                            <div style="font-size: 0.78rem; color: var(--text-dim);">Simulasi konfirmasi otomatis</div>
                        </div>
                    </label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem; font-size: 1rem;">
                👑 Aktifkan VIP Saiki
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openSubscribeModal(code, name, price) {
        @if(!Auth::check())
            window.location.href = "{{ route('login') }}";
            return;
        @endif
        document.getElementById('subPlanCode').value = code;
        document.getElementById('subPlanTitle').textContent = name;
        document.getElementById('subPlanPrice').textContent = 'Rp ' + Number(price).toLocaleString('id-ID');
        document.getElementById('subModal').classList.add('is-open');
    }
</script>
@endsection
