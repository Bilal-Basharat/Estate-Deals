<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\ListingController;
use App\Http\Controllers\ListingImageController;
use App\Http\Controllers\ListingOfferAcceptController;
use App\Http\Controllers\ListingOfferController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\NotificiationMarkController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\RealtorListingController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\UserAccountController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
| Home page and the public listing catalogue. Browsable by anyone;
| the pages adapt their content when a user is authenticated.
*/

Route::get('/', [IndexController::class, 'index'])
    ->name('home');

Route::resource('listing', ListingController::class)
    ->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| Guest only — authentication
|--------------------------------------------------------------------------
| Login, registration, and the password reset flow. All of these are
| meaningless for an authenticated user, hence the guest middleware.
| (Login/registration were previously unguarded; guest adds the
| standard redirect-if-authenticated behavior.)
*/

Route::middleware('guest')->group(function () {
    // Login
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store']);

    // Registration
    Route::resource('user-account', UserAccountController::class)
        ->only(['create', 'store']);

    // Password reset
    Route::get('forgot-password', [ForgotPasswordController::class, 'create'])
        ->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'store'])
        ->name('password.email');
    Route::get('reset-password/{token}', [ResetPasswordController::class, 'create'])
        ->name('password.reset');
    Route::post('reset-password', [ResetPasswordController::class, 'store'])
        ->name('password.update');
});

Route::post('logout', [AuthController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Email verification
|--------------------------------------------------------------------------
| Realtor features require a verified address. Notice page, the signed
| verification link, and the throttled resend action.
*/

Route::middleware('auth')->group(function () {
    Route::get('email/verify', fn () => inertia('Auth/VerifyEmail'))
        ->name('verification.notice');

    Route::get('email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('listing.index')->with([
            'success' => true,
            'message' => 'Account verified successfully',
        ]);
    })->middleware('signed')->name('verification.verify');

    Route::post('email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('message', 'Verification link sent!');
    })->middleware('throttle:6,1')->name('verification.send');
});

/*
|--------------------------------------------------------------------------
| Authenticated — buyer actions & account
|--------------------------------------------------------------------------
| Making offers, notifications, and the user's transaction history.
*/

Route::middleware('auth')->group(function () {
    // Offers on listings
    Route::resource('listing.offer', ListingOfferController::class)
        ->only(['store']);

    // Notifications
    Route::resource('notification', NotificationController::class)
        ->only(['index']);
    Route::put('notification/{notification}/mark', NotificiationMarkController::class)
        ->name('notification.mark');

    // Transaction history
    Route::get('purchases', PurchaseController::class)->name('purchases');
    Route::get('sales', SaleController::class)->name('sales');
});

/*
|--------------------------------------------------------------------------
| Realtor — listing management
|--------------------------------------------------------------------------
| Requires a verified account. CRUD for the user's own listings
| (including soft-deleted ones), photo management, and accepting offers.
*/

Route::prefix('realtor')
    ->name('realtor.')
    ->middleware(['auth', 'verified'])
    ->group(function () {
        // Restore must be declared before the resource so the /restore
        // segment isn't captured by the resource's {listing} routes.
        Route::put('listing/{listing}/restore', [RealtorListingController::class, 'restore'])
            ->withTrashed()
            ->name('listing.restore');

        Route::resource('listing', RealtorListingController::class)
            ->only(['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'])
            ->withTrashed();

        Route::resource('listing.image', ListingImageController::class)
            ->only(['create', 'store', 'destroy']);

        Route::put('offer/{offer}/accept', ListingOfferAcceptController::class)
            ->name('offer.accept');
    });