<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\Movie;
use App\Models\Track;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Subscription;
use App\Models\Category;
use App\Models\WalletTransaction;

class AdminController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $isAdmin = $user && in_array($user->role, ['admin', 'creator']);

        $stats = [
            'total_movies' => Movie::count(),
            'total_tracks' => Track::count(),
            'total_users' => User::count(),
            'active_vips' => User::where('is_vip', true)->count(),
            'tickets_sold' => Ticket::count(),
            'ticket_revenue' => Ticket::sum('price_paid') ?? 0,
            'sub_revenue' => Subscription::sum('price_paid') ?? 0,
        ];

        $movies = Movie::with('category')->latest()->get();
        $tracks = Track::with('category')->latest()->get();
        $recentTickets = Ticket::with(['user', 'movie'])->latest()->take(15)->get();
        $recentSubscriptions = Subscription::with('user')->latest()->take(15)->get();
        $categories = Category::all();
        $users = User::latest()->get();

        return view('admin.dashboard', compact(
            'isAdmin',
            'stats',
            'movies',
            'tracks',
            'recentTickets',
            'recentSubscriptions',
            'categories',
            'users'
        ));
    }

    public function storeMovie(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tagline' => 'nullable|string|max:255',
            'synopsis' => 'required|string',
            'director' => 'required|string|max:255',
            'cast' => 'nullable|string|max:255',
            'release_year' => 'required|integer',
            'duration_minutes' => 'required|integer',
            'access_type' => 'required|in:free,vip,ticket',
            'ticket_price' => 'nullable|numeric|min:0',
            'video_url' => 'required|string',
            'poster_url' => 'required|string',
            'banner_url' => 'required|string',
            'location_tag' => 'nullable|string',
            'featured' => 'nullable',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['ticket_price'] = $validated['access_type'] === 'ticket' ? ($validated['ticket_price'] ?? 15000) : 0;
        $validated['featured'] = $request->has('featured');

        Movie::create($validated);

        return back()->with('success', "Film '{$validated['title']}' kasil ditambahna ning katalog Sinema Tegal!");
    }

    public function updateMovie(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'tagline' => 'nullable|string|max:255',
            'synopsis' => 'required|string',
            'director' => 'required|string|max:255',
            'cast' => 'nullable|string|max:255',
            'release_year' => 'required|integer',
            'duration_minutes' => 'required|integer',
            'access_type' => 'required|in:free,vip,ticket',
            'ticket_price' => 'nullable|numeric|min:0',
            'video_url' => 'required|string',
            'poster_url' => 'required|string',
            'banner_url' => 'required|string',
            'location_tag' => 'nullable|string',
            'featured' => 'nullable',
        ]);

        $validated['ticket_price'] = $validated['access_type'] === 'ticket' ? ($validated['ticket_price'] ?? 15000) : 0;
        $validated['featured'] = $request->has('featured');

        $movie->update($validated);

        return back()->with('success', "Data film '{$movie->title}' kasil dianyari!");
    }

    public function storeTrack(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'release_year' => 'required|integer',
            'duration_seconds' => 'required|integer',
            'access_type' => 'required|in:free,vip',
            'audio_url' => 'required|string',
            'cover_url' => 'required|string',
            'lyrics' => 'nullable|string',
            'description' => 'nullable|string',
            'featured' => 'nullable',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['featured'] = $request->has('featured');

        Track::create($validated);

        return back()->with('success', "Tembang '{$validated['title']}' kasil ditambahna ning Nada Tegalan!");
    }

    public function updateTrack(Request $request, $id)
    {
        $track = Track::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'artist' => 'required|string|max:255',
            'album' => 'nullable|string|max:255',
            'genre' => 'nullable|string|max:255',
            'release_year' => 'required|integer',
            'duration_seconds' => 'required|integer',
            'access_type' => 'required|in:free,vip',
            'audio_url' => 'required|string',
            'cover_url' => 'required|string',
            'lyrics' => 'nullable|string',
            'description' => 'nullable|string',
            'featured' => 'nullable',
        ]);

        $validated['featured'] = $request->has('featured');
        $track->update($validated);

        return back()->with('success', "Data tembang '{$track->title}' kasil dianyari!");
    }

    public function destroyMovie($id)
    {
        $movie = Movie::findOrFail($id);
        $title = $movie->title;
        $movie->delete();

        return back()->with('success', "Film '{$title}' kasil dibusak.");
    }

    public function destroyTrack($id)
    {
        $track = Track::findOrFail($id);
        $title = $track->title;
        $track->delete();

        return back()->with('success', "Tembang '{$title}' kasil dibusak.");
    }

    public function toggleUserVip($id)
    {
        $targetUser = User::findOrFail($id);
        $targetUser->is_vip = !$targetUser->is_vip;
        if ($targetUser->is_vip) {
            $targetUser->vip_expires_at = now()->addDays(30);
        }
        $targetUser->save();

        $statusText = $targetUser->is_vip ? 'VIP Aktif (30 dina)' : 'VIP Dinonaktifkan';
        return back()->with('success', "Status pengguna {$targetUser->name} saiki: {$statusText}.");
    }

    public function addUserBalance(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $amount = (int) $request->input('amount', 50000);
        
        $targetUser->balance += $amount;
        $targetUser->save();

        WalletTransaction::create([
            'user_id' => $targetUser->id,
            'amount' => $amount,
            'type' => 'topup',
            'description' => 'Top-up manual dening Kurator Admin Sinema Tegal',
            'reference_id' => 'ADM-' . strtoupper(Str::random(8)),
            'status' => 'completed',
        ]);

        return back()->with('success', "Kasil nambahi saldo Rp " . number_format($amount, 0, ',', '.') . " maring {$targetUser->name}.");
    }

    public function updateUserRole(Request $request, $id)
    {
        $targetUser = User::findOrFail($id);
        $role = $request->input('role', 'user');

        if (!in_array($role, ['user', 'creator', 'admin'])) {
            $role = 'user';
        }

        $targetUser->role = $role;
        $targetUser->save();

        return back()->with('success', "Peran {$targetUser->name} kasil diubah dadi {$role}.");
    }
}
