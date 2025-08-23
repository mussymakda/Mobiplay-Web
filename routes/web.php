<?php
use App\Http\Controllers\DriverController;
use App\Http\Controllers\EditProfileController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CampaignController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\PaymentController;
use Illuminate\Http\Request;
use App\Http\Controllers\LocalizationController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;





// Command Routes (consider restricting access)
Route::get('command:clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('optimize:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return "config, cache, and view cleared successfully";
});

Route::get('command:config', function () {
    Artisan::call('config:cache');
    return "config cache successfully";
});

Route::get('command:key', function () {
    Artisan::call('key:generate');
    return "Key generated successfully";
});

Route::get('command:migrate', function () {
    Artisan::call('migrate');
    return "Database migration completed";
});

Route::get('command:migrate_refresh', function () {
    Artisan::call('migrate:refresh');
    return "Database migration refreshed";
});

Route::get('/', function () {
    return view('landing.index'); // Show landing page instead of redirecting to login
})->name('landing');

Route::get('/stripe/balance', [PaymentController::class, 'getBalance'])->name('stripe.balance');


Route::get('/driver', function () {
    return view('landing.driver'); // Show landing page instead of redirecting to login
})->name('driver');
// Move login redirection to a separate route
Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    }
    return app(AuthController::class)->index();
})->name('login');

// POST route for login submission
Route::post('/login', [AuthController::class, 'login']);

// Authentication Routes

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Route
Route::get('/dashboard', function () {
    $offers = \App\Models\Offer::where('is_active', true)
        ->where('valid_from', '<=', now())
        ->where('valid_until', '>=', now())
        ->orderBy('created_at', 'desc')
        ->get();
    
    return view('dashboard', compact('offers'));
})->middleware('auth')->name('dashboard');

// Route::get('/campaign-wizard', function () {
//     return view('campaign-wizard');
// })->middleware('auth')->name('campaign-wizard');

Route::get('/campaign-wizard', [CampaignController::class, 'showCampaignWizard'])->middleware('auth')->name('campaign-wizard');
Route::post('/campaign-wizard', [CampaignController::class, 'store'])->middleware('auth')->name('campaign.store');
Route::post('/campaign/create', [CampaignController::class, 'create'])->middleware('auth')->name('campaign.create');

// Signup Routes
Route::get('/signup', [AuthController::class, 'showSignupForm'])->name('signup');
Route::post('/api/signup', [AuthController::class, 'signup']);
Route::post('/complete-signup', [AuthController::class, 'completeSignup']); // Ensure this method exists
Route::post('/api/verify-otp', [AuthController::class, 'verifyOtp']);
Route::post('/get-new-code', [AuthController::class, 'getNewCode']);

// Additional Routes for Signup Steps
Route::get('/signup/organization-info', [RegistrationController::class, 'showOrganizationInfoForm'])->name('signup.organization.form');
Route::post('/signup/organization-info', [RegistrationController::class, 'handleOrganizationInfo'])->name('signup.organization.handle');

Route::get('/signup/phone-verification', [RegistrationController::class, 'showPhoneVerificationForm'])->name('signup.phone.form');
Route::post('/signup/phone-verification', [RegistrationController::class, 'handlePhoneVerification'])->name('signup.phone.handle');

Route::get('/signup/location', [RegistrationController::class, 'showLocationForm'])->name('signup.location.form');
Route::post('/signup/location', [RegistrationController::class, 'handleLocation'])->name('signup.location.handle');

Route::get('/signup/account', [RegistrationController::class, 'showAccountForm'])->name('signup.account.form');
Route::post('/signup/account', [RegistrationController::class, 'handleAccount'])->name('signup.account.handle');

Route::get('/make-payment', [PaymentController::class, 'showMakePaymentForm'])->middleware('auth')->name('payment.make');
Route::post('/stripe', [PaymentController::class, 'session'])->name('payment.process');

//Stripe
// Route for creating metered payment (for subscriptions)
// Route::post('/create-metered-payment', [PaymentController::class, 'createMeteredSubscription']);

// // Route for creating one-time payment
// Route::post('/create-onetime-session', [PaymentController::class, 'createOneTimePayment']);
Route::post('stripe/test-webhook', function (Request $request) {
    Log::info('Test webhook hit', [
        'method' => $request->method(),
        'headers' => $request->headers->all(),
        'body' => $request->all()
    ]);
    return response()->json(['status' => 'received']);
});

Route::get('/payment/success', [PaymentController::class, 'handlePaymentSuccess'])
    ->name('payment.success');

Route::get('/payment/success-page', [PaymentController::class, 'showPaymentSuccessPage'])
    ->name('payment.success.page');

Route::post('stripe/webhook', [PaymentController::class, 'handleStripeWebhook'])
    ->name('stripe.webhook')
    ->middleware('api');

Route::get('/payment', [PaymentController::class, 'showMakePaymentForm'])->name('payment.form');
Route::post('/create-onetime-payment', [PaymentController::class, 'createOneTimePayment']);
Route::post('/create-metered-payment', [PaymentController::class, 'createMeteredSubscription']);
Route::post('/stripe/connect', [PaymentController::class, 'connectStripe']);

Route::get('/payment/cancel', [PaymentController::class, 'handlePaymentCancel'])->name('payment.cancel');
// Profile Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');

    // Edit Profile Routes
    Route::get('/edit-profile', [EditProfileController::class, 'show'])->name('profile.edit');
    Route::post('/edit-profile', [EditProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/upload-photo', [EditProfileController::class, 'uploadPhoto'])->name('profile.upload-photo');

});
Route::get('/payments', [PaymentController::class, 'index'])->middleware('auth');

Route::post('/create-metered-payment', [PaymentController::class, 'createMeteredPayment']);

Route::get('/switch-lang/{locale}', [LocalizationController::class, 'switch'])
    ->name('switchLang'); 

    
Route::get('/settings', [SettingsController::class, 'show'])->middleware('auth')->name('settings');

Route::post('/webhook/stripe', [PaymentController::class, 'handleStripeWebhook']);

Route::get('contact', function () {
    return view('contact');
})->middleware('auth')->name('contact');
Route::get('terms', function () {
    return view('terms');
})->middleware('auth')->name('terms');
Route::get('privacy', function () {
    return view('privacy');
})->middleware('auth')->name('privacy');

Route::get('faq', function () {
    return view('faq');
})->middleware('auth')->name('faq');

Route::get('password', function () {
    return view('password');
})->middleware('auth')->name('password');

Route::get('analytics', function () {
    return view('analytics');
})->middleware('auth')->name('analytics');

Route::get('camplain-list', [CampaignController::class, 'showCampaignList'])->middleware('auth')->name('camplain-list');

Route::get('about-us', function () {
    return view('about-us');
})->middleware('auth')->name('about-us');
