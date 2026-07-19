<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Offer;

class RealtorListingController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Listing::class, 'listing');
    }
    public function index(Request $request)
    {
        $filters = [
            'deleted' => $request->boolean('deleted'),
            ...$request->only(['by', 'order'])
        ];

        $user = Auth::user();

        return inertia('Realtor/Index', [
            'filters' => $filters,
            'stats' => [
                'activeListings' => $user->listings()->whereNull('sold_at')->count(),
                'soldListings' => $user->listings()->whereNotNull('sold_at')->count(),
                'soldValue' => (int) $user->listings()->whereNotNull('sold_at')->sum('price'),
                'pendingOffers' => Offer::whereNull('accepted_at')
                    ->whereNull('rejected_at')
                    ->whereHas('listing', fn($query) => $query->where('user_id', $user->id))
                    ->count(),
            ],
            'listings' => $user->listings()
                ->filter($filters)
                ->with('images')
                ->withCount('images')
                ->withCount('offers')
                ->withCount(['offers as pending_offers_count' => fn($query) => $query
                    ->whereNull('accepted_at')
                    ->whereNull('rejected_at')])
                ->paginate(10)
                ->withQueryString(),
        ]);

        // return inertia('Realtor/Index', [
        //     // 'filters' =>  
        //     'listings' => Auth::user()
        //         ->listings()
        //         ->filter($filters)
        //         ->withCount('images')
        //         ->withCount('offers')
        //         ->paginate(10)
        //         ->withQueryString(),
        // ]);
    }

    public function show(Listing $listing)
    {
        try {
            $this->authorize('viewAsRealtor', $listing);

            $listing->load(['images', 'offers', 'offers.bidder']);

            return inertia("Realtor/Show", [
                'listing' => $listing,
                'offer' => $listing->load('offers', 'offers.bidder')
            ]);
        } catch (Exception $ex) {
            return redirect()->back()->with([
                'success' => false,
                'error' => 'only the authorized user can view this listing'
            ]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Realtor/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'beds' => 'required|integer|min:0|max:20',
            'baths' => 'required|integer|min:0|max:20',
            'area' => 'required|integer|min:15|max:1500',
            'city' => 'required',
            'code' => 'required',
            'street' => 'required',
            'street_nr' => 'required|min:1|max:1000',
            'price' => 'required|integer|min:50000|max:1000000000',
        ]);

        $request->user()->listings()->create($validated);
        // Listing::create($validated);

        return redirect()->route('realtor.listing.index')->with([
            'success' => true,
            'message' => 'New Listing created'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Listing $listing)
    {
        return inertia('Realtor/Edit', [
            'listing' => $listing
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Listing $listing)
    {
        // $listing = Listing::find($id);

        $update = $listing->update(
            $request->validate([
                'beds' => 'required|integer|min:0|max:20',
                'baths' => 'required|integer|min:0|max:20',
                'area' => 'required|integer|min:15|max:1500',
                'city' => 'required',
                'code' => 'required',
                'street' => 'required',
                'street_nr' => 'required|min:1|max:1000',
                'price' => 'required|integer|min:50000|max:1000000000',
            ])
        );

        return redirect()->route('realtor.listing.index')->with([
            'success' => true,
            'message' => 'Listing updated successfully'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Listing $listing)
    {
        $listing->delete();

        redirect()->route('realtor.listing.index')->with([
            'success' => true,
            'message' => 'Listing deleted'
        ]);
    }
    public function restore(Listing $listing)
    {
        $listing->restore();

        redirect()->route('realtor.listing.index')->with([
            'success' => true,
            'message' => 'Listing restored successfully'
        ]);
    }
}
