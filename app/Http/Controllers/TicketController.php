<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;

class TicketController extends Controller
{
    public function myTickets()
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('warning', 'Mlebu akun dhisit nggo ndeleng tiket sampeyan.');
        }

        $activeTickets = Ticket::with('movie.category')
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->where('valid_until', '>', now())
            ->orderBy('created_at', 'desc')
            ->get();

        $expiredTickets = Ticket::with('movie.category')
            ->where('user_id', $user->id)
            ->where(function ($q) {
                $q->where('status', '!=', 'active')
                  ->orWhere('valid_until', '<=', now());
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('tickets.my-tickets', compact('activeTickets', 'expiredTickets'));
    }
}
