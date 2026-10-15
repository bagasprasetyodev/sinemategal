@extends('layouts.app')

@section('title', 'Dompet Bahari - Saldo & Transaksi Sinema Tegal')

@section('content')
<div class="container" style="padding-top: 2rem;">
    <!-- Wallet Header & Balance Card -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 3rem; flex-wrap: wrap;">
        <!-- Left: Balance Card -->
        <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.25), rgba(6, 182, 212, 0.2)), #0f172a; border: 2px solid var(--gold-500); border-radius: var(--radius-lg); padding: 2.5rem; display: flex; flex-direction: column; justify-content: space-between; box-shadow: var(--shadow-lg), 0 0 30px var(--gold-glow);">
            <div>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="font-size: 1.5rem;">💰</span>
                        <span style="font-size: 1rem; font-weight: 800; color: #fff; letter-spacing: 0.05em; text-transform: uppercase;">
                            DOMPET BAHARI
                        </span>
                    </div>
                    <span class="brand-badge">Sistem Dompet Digital</span>
                </div>

                <div style="color: var(--text-dim); font-size: 0.85rem; margin-bottom: 0.35rem;">
                    Total Saldo Aktif Sampeyan:
                </div>
                <div style="font-size: 3rem; font-weight: 900; color: #fff; line-height: 1.1; margin-bottom: 0.5rem;">
                    <span style="font-size: 1.5rem; color: var(--gold-400); vertical-align: super;">Rp</span>
                    {{ number_format($user->wallet_balance, 0, ',', '.') }}
                </div>
                <div style="font-size: 0.82rem; color: #cbd5e1;">
                    Bisa digunakake nggo tuku tiket premiere lan mbayar langganan VIP Sebayu saknalika.
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: space-between; border-top: 1px solid rgba(255, 255, 255, 0.1); padding-top: 1.25rem; margin-top: 1.5rem;">
                <div style="font-size: 0.82rem; color: var(--text-dim);">
                    Akun: <strong style="color: #fff;">{{ $user->name }}</strong>
                </div>
                <div style="font-size: 0.82rem; color: var(--gold-400);">
                    {{ $user->hasVipAccess() ? '⭐ Anggota VIP' : 'Warga Reguler' }}
                </div>
            </div>
        </div>

        <!-- Right: Top-up Form -->
        <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem;">
            <h3 style="font-size: 1.25rem; color: #fff; margin-bottom: 0.35rem; display: flex; align-items: center; gap: 0.5rem;">
                <span>⚡</span> Isi Ulang / Top-Up Saldo
            </h3>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 1.25rem;">
                Pilih nominal cepet utawa ketik nominal sak karepe nggo simulasi top up instan.
            </p>

            <form method="POST" action="{{ route('wallet.topup') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label">Pilihan Nominal Cepet:</label>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; margin-bottom: 1rem;">
                        <button type="button" onclick="setAmount(25000)" class="btn btn-secondary btn-sm" style="font-weight: 700;">+ Rp 25.000</button>
                        <button type="button" onclick="setAmount(50000)" class="btn btn-secondary btn-sm" style="font-weight: 700;">+ Rp 50.000</button>
                        <button type="button" onclick="setAmount(100000)" class="btn btn-secondary btn-sm" style="font-weight: 700;">+ Rp 100.000</button>
                        <button type="button" onclick="setAmount(250000)" class="btn btn-secondary btn-sm" style="font-weight: 700;">+ Rp 250.000</button>
                    </div>

                    <label class="form-label">Nominal Top-up (Rp):</label>
                    <input type="number" id="topupAmount" name="amount" value="50000" min="10000" max="2000000" step="5000" class="form-control" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Metode Pembayaran Top-up:</label>
                    <select name="method" class="form-control">
                        <option value="QRIS">📱 QRIS (Gopay / OVO / Dana / ShopeePay)</option>
                        <option value="Virtual Account BCA">🏦 Virtual Account Bank BCA</option>
                        <option value="Virtual Account BRI">🏦 Virtual Account Bank BRI</option>
                        <option value="Virtual Account Mandiri">🏦 Virtual Account Bank Mandiri</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 0.85rem;">
                    💰 Tambah Saldo Dompet Bahari Saiki
                </button>
            </form>
        </div>
    </div>

    <!-- Transaction History Table -->
    <div style="background: var(--bg-surface); border: 1px solid var(--border-subtle); border-radius: var(--radius-lg); padding: 2rem; margin-bottom: 3.5rem;">
        <h3 style="font-size: 1.3rem; color: #fff; margin-bottom: 1.25rem; display: flex; align-items: center; gap: 0.5rem;">
            <span>📜</span> Riwayat Transaksi Dompet Bahari
        </h3>

        @if($transactions->count() > 0)
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem; text-align: left;">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--border-subtle); color: var(--text-dim); font-size: 0.78rem; text-transform: uppercase;">
                            <th style="padding: 0.75rem 1rem;">Tanggal & Wektu</th>
                            <th style="padding: 0.75rem 1rem;">Tipe</th>
                            <th style="padding: 0.75rem 1rem;">Katrangan (Deskripsi)</th>
                            <th style="padding: 0.75rem 1rem;">Referensi</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">Jumlah Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $trx)
                            <tr style="border-bottom: 1px solid rgba(255, 255, 255, 0.04); transition: background 0.15s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                                <td style="padding: 0.85rem 1rem; color: var(--text-muted); font-size: 0.85rem;">
                                    {{ $trx->created_at->format('d M Y, H:i') }}
                                </td>
                                <td style="padding: 0.85rem 1rem;">
                                    @if($trx->type === 'topup')
                                        <span class="badge badge-free">Top-Up</span>
                                    @elseif($trx->type === 'purchase_ticket')
                                        <span class="badge badge-ticket">Tiket</span>
                                    @else
                                        <span class="badge badge-vip">VIP</span>
                                    @endif
                                </td>
                                <td style="padding: 0.85rem 1rem; color: #fff; font-weight: 500;">
                                    {{ $trx->description }}
                                </td>
                                <td style="padding: 0.85rem 1rem; font-family: monospace; font-size: 0.8rem; color: var(--text-dim);">
                                    {{ $trx->reference_id ?? '-' }}
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: right; font-weight: 700; color: {{ $trx->type === 'topup' ? 'var(--emerald-500)' : 'var(--crimson-500)' }};">
                                    {{ $trx->formatted_amount }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1.5rem; display: flex; justify-content: center;">
                {{ $transactions->links() }}
            </div>
        @else
            <div style="text-align: center; padding: 2rem; color: var(--text-muted);">
                Durung ana cathetan transaksi.
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    function setAmount(val) {
        document.getElementById('topupAmount').value = val;
    }
</script>
@endsection
