<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function __invoke()
    {
        return inertia('User/Sales', [
            'sales' => Auth::user()
                ->listings()
                ->withTrashed()
                ->whereNotNull('sold_at')
                ->with(['images', 'acceptedOffer.bidder'])
                ->orderByDesc('sold_at')
                ->paginate(10),
        ]);
    }
}
