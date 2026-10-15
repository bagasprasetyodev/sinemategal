<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Movie;
use App\Models\Track;
use App\Models\Category;
use App\Models\Playlist;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $featuredMovie = Movie::with('category')->where('featured', true)->first()
            ?? Movie::with('category')->first();

        $trendingMovies = Movie::with('category')->orderBy('views_count', 'desc')->take(6)->get();
        $ticketMovies = Movie::with('category')->where('access_type', 'ticket')->take(4)->get();
        $vipMovies = Movie::with('category')->where('access_type', 'vip')->take(4)->get();
        $freeMovies = Movie::with('category')->where('access_type', 'free')->take(4)->get();

        $featuredTracks = Track::with('category')->orderBy('plays_count', 'desc')->take(6)->get();
        $categories = Category::withCount(['movies', 'tracks'])->get();
        $playlists = Playlist::with('tracks')->take(3)->get();
        $demoUsers = User::take(4)->get();

        return view('home', compact(
            'featuredMovie',
            'trendingMovies',
            'ticketMovies',
            'vipMovies',
            'freeMovies',
            'featuredTracks',
            'categories',
            'playlists',
            'demoUsers'
        ));
    }
}
