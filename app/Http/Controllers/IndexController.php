<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    function index(){
        $user = Auth::user();

        return inertia('Index/Index', [
            // Latest active listings for the "fresh on the market" section.
            'latestListings' => Listing::mostRecent()
                ->withoutSold()
                ->with('images')
                ->take(6)
                ->get(),

            // Popular city shortcuts for the hero search.
            'cities' => Listing::withoutSold()
                ->select('city')
                ->groupBy('city')
                ->orderByRaw('COUNT(*) DESC')
                ->take(6)
                ->pluck('city'),

            // Personal snapshot - null for guests, so the page can branch.
            'snapshot' => $user ? [
                'activeListings' => $user->listings()->whereNull('sold_at')->count(),
                'offersReceived' => Offer::whereNull('accepted_at')
                    ->whereNull('rejected_at')
                    ->whereHas('listing', fn ($query) => $query->where('user_id', $user->id))
                    ->count(),
                'offersMade' => Offer::byMe()
                    ->whereNull('accepted_at')
                    ->whereNull('rejected_at')
                    ->count(),
            ] : null,
        ]);
    }   

    function show(){
        return inertia('Index/Show'); 
    }
}
