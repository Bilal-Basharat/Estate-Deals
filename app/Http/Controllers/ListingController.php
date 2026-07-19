<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Offer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->only([
            'city',
            'priceFrom',
            'priceTo',
            'beds',
            'baths',
            'areaFrom',
            'areaTo',
            'by',
            'order',
        ]);

        $query = Listing::query()
            ->filter($filters)
            ->withoutSold()
            ->with('images');

        // Only apply the default "newest first" when the user hasn't chosen a sort,
        // otherwise mostRecent()'s orderBy runs first and overrides the chosen sort.
        if (empty($filters['by'])) {
            $query->mostRecent();
        }

        return inertia('Listing/Index', [
            'filters' => $filters,
            'cities' => Listing::withoutSold()
                ->select('city')
                ->distinct()
                ->orderBy('city')
                ->pluck('city'),
            'listings' => $query->paginate(12)->withQueryString(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Listing $listing)
    {
        $listing->load('images');

        $offer = !Auth::user() ? null : $listing->offers()->byMe()->first();

        return inertia('Listing/Show', [
            'listing' => $listing,
            'offerMade' => $offer
        ]);
    }
}
