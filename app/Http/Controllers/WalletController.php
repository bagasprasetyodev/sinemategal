<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\WalletTransaction;

class WalletController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $transactions = $user->walletTransactions()->paginate(15);

        return view('wallet.index', compact('user', 'transactions'));
    }

    public function topup(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:10000|max:2000000',
            'method' => 'required|string',
        ]);

        $amount = (float) $validated['amount'];
        $method = $validated['method'];

        $user->wallet_balance += $amount;
        $user->save();

        WalletTransaction::create([
            'user_id' => $user->id,
            'type' => 'topup',
            'amount' => $amount,
            'reference_id' => 'TOPUP-' . strtoupper(uniqid()),
            'description' => "Top-up Saldo via {$method}",
        ]);

        return redirect()->route('wallet.index')->with('success', 'Top-up saldo Dompet Bahari kasil! Tambah Rp ' . number_format($amount, 0, ',', '.') . '.');
    }
}
