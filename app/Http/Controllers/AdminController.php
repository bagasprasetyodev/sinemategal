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
        if (!$user || !in_array($user->role, ['admin', 'creator'])) {
            return redirect('/')->with('error', 'Akses mung kusus Admin & Kurator Budaya Tegal.');
        }

        $stats = [
            'total_movies' => Movie::count(),
            'total_tracks' => Track::count(),
            'total_users' => User::count(),
            'active_vips' => User::where('is_vip', true)->count(),
            'tickets_sold' => Ticket::count(),
            'ticket_revenue' => Ticket::sum('price_paid'),
            'sub_revenue' => Subscription::sum('price_paid'),
        ];

        $movies = Movie::with('category')->latest()->take(10)->get();
        $tracks = Track::with('category')->latest()->take(10)->get();
        $recentTickets = Ticket::with(['user', 'movie'])->latest()->take(8)->get();
        $recentSubscriptions = Subscription::with('user')->latest()->take(8)->get();
        $categories = Category::all();

        return view('admin.dashboard', compact(
            'stats',
            'movies',
            'tracks',
            'recentTickets',
            'recentSubscriptions',
            'categories'
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
            'featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['ticket_price'] = $validated['access_type'] === 'ticket' ? ($validated['ticket_price'] ?? 15000) : 0;
        $validated['featured'] = $request->has('featured');

        Movie::create($validated);

        return back()->with('success', "Film '{$validated['title']}' kasil ditambahna ning katalog Sinema Tegal!");
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
            'featured' => 'nullable|boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']) . '-' . rand(100, 999);
        $validated['featured'] = $request->has('featured');

        Track::create($validated);

        return back()->with('success', "Tembang '{$validated['title']}' kasil ditambahna ning Nada Tegalan!");
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
}
