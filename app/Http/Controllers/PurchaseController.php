<?php

namespace App\Http\Controllers;

use App\Models\Offer;

class PurchaseController extends Controller
{
    public function __invoke()
    {
        return inertia('User/Purchases', [
            'purchases' => Offer::byMe()
                ->whereNotNull('accepted_at')
                ->with(['listing' => fn ($query) => $query->withTrashed()->with('images')])
                ->orderByDesc('accepted_at')
                ->paginate(10),
        ]);
    }
}
