<?php

use App\Http\Controllers\SuperAdminDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminCampaignsController;
use App\Http\Controllers\AdminGiftsListController;
use App\Http\Controllers\AdminCustomersController;
use App\Http\Controllers\AdminChildUsersController;
use App\Http\Controllers\UserDashboardController;
use App\Http\Controllers\CampaignsController;
use App\Http\Controllers\GiftsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\RedeemController;
use App\Http\Controllers\ScratchLinksController;
use App\Http\Controllers\GiftsListController;
use App\Http\Controllers\BranchesController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminProfileController;
use App\Http\Controllers\GeneralOptionsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\SuperAdminSubUsersController;
use App\Http\Controllers\ScratchPackageController;
use App\Http\Controllers\ScratchPurchaseController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\LogoFaviconController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Shortener\ShortenerController;
use App\Http\Controllers\Shortener\GlScratchWebController;

// Public routes
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/register/create-order', [RegisterController::class, 'createOrder'])->name('register.create-order');
    Route::post('/register/verify-payment', [RegisterController::class, 'verifyPayment'])->name('register.verify-payment');
});

Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

    Route::get('/logout', [LoginController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Protected admin routes (only for superadmin role)
Route::middleware(['auth', 'superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [SuperAdminDashboardController::class, 'create'])->name('dashboard');
    Route::get('/dashboard/chart-data', [SuperAdminDashboardController::class, 'chartData'])->name('dashboard.chart-data');

    // Admin child users (role_id 1 — manage role_id 3 users)
    Route::get('/child-users',              [AdminChildUsersController::class, 'index'])->name('child-users.index');
    Route::get('/child-users/data',         [AdminChildUsersController::class, 'getData'])->name('child-users.data');
    Route::get('/purchase-scratch-credits', [AdminChildUsersController::class, 'purchaseCredits'])->name('purchase-scratch-credits');
    Route::post('/child-users',             [AdminChildUsersController::class, 'store'])->name('child-users.store');
    Route::get('/child-users/{id}/edit',    [AdminChildUsersController::class, 'edit'])->name('child-users.edit');
    Route::put('/child-users/{id}',         [AdminChildUsersController::class, 'update'])->name('child-users.update');
    Route::delete('/child-users/{id}',      [AdminChildUsersController::class, 'destroy'])->name('child-users.destroy');

    // Admin purchase credits for child users
    Route::post('/purchase/create-order',   [ScratchPurchaseController::class, 'adminCreateOrder'])->name('child-users.purchase.create-order');
    Route::post('/purchase/verify-payment', [ScratchPurchaseController::class, 'adminVerifyPayment'])->name('child-users.purchase.verify-payment');


    // Admin profile routes (accessible by both superadmin and admin)
    Route::get('/profile',          [AdminProfileController::class, 'index'])->name('profile');
    Route::post('/profile',         [AdminProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password',[AdminProfileController::class, 'changePassword'])->name('profile.password');

    // Admin campaigns (role_id 1 — child users' campaigns)
    Route::get('/campaigns', [AdminCampaignsController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/data', [AdminCampaignsController::class, 'getData'])->name('campaigns.data');

    // Admin gifts list (role_id 1 — child users' gifts)
    Route::get('/gifts-list', [AdminGiftsListController::class, 'index'])->name('gifts-list.index');
    Route::get('/gifts-list/data', [AdminGiftsListController::class, 'getData'])->name('gifts-list.data');

    // Admin customers list (role_id 1 — child users' customers)
    Route::get('/customers', [AdminCustomersController::class, 'index'])->name('customers.index');
    Route::get('/customers/data', [AdminCustomersController::class, 'getData'])->name('customers.data');
    Route::get('/customers/export', [AdminCustomersController::class, 'export'])->name('customers.export');

    // Users routes (superadmin only — role_id 0)
    Route::middleware(['superadminonly'])->group(function () {
        Route::get('/users', [UsersController::class, 'index'])->name('users.index');
        Route::get('/users/data', [UsersController::class, 'getUsersData'])->name('users.data');
        Route::post('/users', [UsersController::class, 'store'])->name('users.store');
        Route::get('/users/{id}', [UsersController::class, 'show'])->name('users.show');
        Route::get('/users/{id}/edit', [UsersController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UsersController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users.destroy');
        Route::post('/users/{id}/subscription', [UsersController::class, 'addSubscription'])->name('users.addSubscription');
        Route::post('/users/{id}/scratch', [UsersController::class, 'addScratch'])->name('users.addScratch');
        Route::get('/users/{id}/scratch-history', [UsersController::class, 'getScratchHistory'])->name('users.scratchHistory');
        
        Route::get('/sub-users/data', [SuperAdminSubUsersController::class, 'getChildUsersData'])->name('sub-users.data');
        Route::get('/sub-users/{id}', [SuperAdminSubUsersController::class, 'index'])->name('sub-users.index');
        Route::post('/multi-users/subscription', [SuperAdminSubUsersController::class, 'addSubscription'])->name('sub-users.addSubscription');
        Route::post('/multi-users/scratch', [SuperAdminSubUsersController::class, 'addScratch'])->name('sub-users.addScratch');

        // Scratch Rate (Packages)
        Route::get('/scratch-rate',         [ScratchPackageController::class, 'index'])->name('scratch-rate.index');
        Route::get('/scratch-rate/data',    [ScratchPackageController::class, 'getData'])->name('scratch-rate.data');
        Route::post('/scratch-rate',        [ScratchPackageController::class, 'store'])->name('scratch-rate.store');
        Route::get('/scratch-rate/{id}/edit', [ScratchPackageController::class, 'edit'])->name('scratch-rate.edit');
        Route::put('/scratch-rate/{id}',    [ScratchPackageController::class, 'update'])->name('scratch-rate.update');
        Route::delete('/scratch-rate/{id}', [ScratchPackageController::class, 'destroy'])->name('scratch-rate.destroy');

        // Payments routes
        Route::get('/payments',        [PaymentController::class, 'index'])->name('payments.index');
        Route::get('/payments/data',   [PaymentController::class, 'getData'])->name('payments.data');
        Route::get('/payments/total',  [PaymentController::class, 'getTotal'])->name('payments.total');
        Route::get('/payments/export', [PaymentController::class, 'export'])->name('payments.export');
    });
});

// Protected user routes (only for user or child role)
Route::middleware(['auth', 'userrole'])->prefix('user')->name('user.')->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');

    // Scratch Purchase routes
    Route::post('/purchase/create-order',   [ScratchPurchaseController::class, 'createOrder'])->name('purchase.create-order');
    Route::post('/purchase/verify-payment', [ScratchPurchaseController::class, 'verifyPayment'])->name('purchase.verify-payment');

    // Campaigns routes
    Route::get('/campaigns', [CampaignsController::class, 'index'])->name('campaigns.index');
    Route::get('/campaigns/data', [CampaignsController::class, 'getCampaignsData'])->name('campaigns.data');
    Route::post('/campaigns', [CampaignsController::class, 'store'])->name('campaigns.store');
    Route::get('/campaigns/{id}/edit', [CampaignsController::class, 'edit'])->name('campaigns.edit');
    Route::put('/campaigns/{id}', [CampaignsController::class, 'update'])->name('campaigns.update');
    Route::delete('/campaigns/{id}', [CampaignsController::class, 'destroy'])->name('campaigns.destroy');

    // Gifts routes (nested under campaigns)
    Route::get('/campaigns/{campaign_id}/gifts',          [GiftsController::class, 'show'])->name('campaigns.gifts.show');
    Route::get('/campaigns/{campaign_id}/gifts/data',     [GiftsController::class, 'getGiftsData'])->name('campaigns.gifts.data');
    Route::post('/campaigns/{campaign_id}/gifts',         [GiftsController::class, 'store'])->name('campaigns.gifts.store');
    Route::get('/campaigns/{campaign_id}/gifts/{id}/edit',[GiftsController::class, 'edit'])->name('campaigns.gifts.edit');
    Route::put('/campaigns/{campaign_id}/gifts/{id}',     [GiftsController::class, 'update'])->name('campaigns.gifts.update');
    Route::delete('/campaigns/{campaign_id}/gifts/{id}',  [GiftsController::class, 'destroy'])->name('campaigns.gifts.destroy');

    // Scratch Links routes
    Route::get('/scratch-links',               [ScratchLinksController::class, 'index'])->name('scratch-links.index');
    Route::get('/scratch-links/data',          [ScratchLinksController::class, 'getData'])->name('scratch-links.data');
    Route::get('/scratch-links/link-sections', [ScratchLinksController::class, 'getLinkSections'])->name('scratch-links.link-sections');
    Route::get('/scratch-links/qr-pdf',        [ScratchLinksController::class, 'downloadQrPdf'])->name('scratch-links.qr-pdf');
    Route::post('/scratch-links',              [ScratchLinksController::class, 'store'])->name('scratch-links.store');
    Route::post('/scratch-links/multiple',     [ScratchLinksController::class, 'storeMultiple'])->name('scratch-links.store-multiple');
    Route::get('/scratch-links/{id}/edit',     [ScratchLinksController::class, 'edit'])->name('scratch-links.edit');
    Route::put('/scratch-links/{id}',          [ScratchLinksController::class, 'update'])->name('scratch-links.update');
    Route::post('/scratch-links/{id}/toggle',  [ScratchLinksController::class, 'toggleStatus'])->name('scratch-links.toggle-status');
    Route::delete('/scratch-links/{id}',       [ScratchLinksController::class, 'destroy'])->name('scratch-links.destroy');

    // Gifts List routes
    Route::get('/gifts-list',              [GiftsListController::class, 'index'])->name('gifts-list.index');
    Route::get('/gifts-list/data',         [GiftsListController::class, 'getData'])->name('gifts-list.data');
    Route::get('/gifts-list/{id}/edit',    [GiftsListController::class, 'edit'])->name('gifts-list.edit');
    Route::put('/gifts-list/{id}',         [GiftsListController::class, 'update'])->name('gifts-list.update');
    Route::post('/gifts-list/{id}/toggle', [GiftsListController::class, 'toggleStatus'])->name('gifts-list.toggle-status');
    Route::delete('/gifts-list/{id}',      [GiftsListController::class, 'destroy'])->name('gifts-list.destroy');

    // Settings – Profile routes
    Route::get('/settings/profile',          [ProfileController::class, 'index'])->name('settings.profile');
    Route::post('/settings/profile',         [ProfileController::class, 'update'])->name('settings.profile.update');
    Route::post('/settings/profile/password',[ProfileController::class, 'changePassword'])->name('settings.profile.password');

    // Settings – General Options routes
    Route::get('/settings/general',                [GeneralOptionsController::class, 'index'])->name('settings.general');
    Route::post('/settings/general/toggle-otp',    [GeneralOptionsController::class, 'toggleOtp'])->name('settings.general.toggle-otp');
    Route::post('/settings/general/crm-token',     [GeneralOptionsController::class, 'updateCrmToken'])->name('settings.general.crm-token');
    Route::post('/settings/general/remove-crm',    [GeneralOptionsController::class, 'removeCrmToken'])->name('settings.general.remove-crm');
    Route::post('/settings/general/toggle-crm',    [GeneralOptionsController::class, 'toggleCrm'])->name('settings.general.toggle-crm');

    // Settings – Purchase Credits
    Route::get('/settings/purchase-credits', [ScratchPurchaseController::class, 'index'])->name('settings.purchase-credits');

    // Settings – Logo & Favicon routes
    Route::get('/settings/logo-favicon',          [LogoFaviconController::class, 'index'])->name('settings.logo-favicon');
    Route::get('/settings/logo-favicon/data',      [LogoFaviconController::class, 'getData'])->name('settings.logo-favicon.data');
    Route::post('/settings/logo-favicon',          [LogoFaviconController::class, 'store'])->name('settings.logo-favicon.store');
    Route::get('/settings/logo-favicon/{id}/edit', [LogoFaviconController::class, 'edit'])->name('settings.logo-favicon.edit');
    Route::put('/settings/logo-favicon/{id}',     [LogoFaviconController::class, 'update'])->name('settings.logo-favicon.update');
    Route::delete('/settings/logo-favicon/{id}',  [LogoFaviconController::class, 'destroy'])->name('settings.logo-favicon.destroy');

    // Settings – Branches routes
    Route::get('/settings/branches',              [BranchesController::class, 'index'])->name('settings.branches');
    Route::get('/settings/branches/data',         [BranchesController::class, 'getData'])->name('settings.branches.data');
    Route::post('/settings/branches',             [BranchesController::class, 'store'])->name('settings.branches.store');
    Route::post('/settings/branches/import',      [BranchesController::class, 'import'])->name('settings.branches.import');
    Route::put('/settings/branches/{id}',         [BranchesController::class, 'update'])->name('settings.branches.update');
    Route::post('/settings/branches/{id}/toggle', [BranchesController::class, 'toggleStatus'])->name('settings.branches.toggle');
    Route::delete('/settings/branches/{id}',      [BranchesController::class, 'destroy'])->name('settings.branches.destroy');

    // Redeem routes
    Route::get('/redeem',           [RedeemController::class, 'index'])->name('redeem.index');
    Route::get('/redeem/search',    [RedeemController::class, 'search'])->name('redeem.search');
    Route::post('/redeem/now',      [RedeemController::class, 'redeemNow'])->name('redeem.now');

    // Customers routes
    Route::get('/customers',        [CustomersController::class, 'index'])->name('customers.index');
    Route::get('/customers/data',   [CustomersController::class, 'getCustomersData'])->name('customers.data');
    Route::get('/customers/export', [CustomersController::class, 'export'])->name('customers.export');
});


// Scrtach web routes -------------------------------------------------------------------------------------------->

  Route::domain(env('SHORT_LINK_DOMAIN'))->group(function () {
    Route::get('{id}/{code}', 'App\Http\Controllers\Shortener\GlScratchWebController@index')->name('shorter-link');

    Route::get('scratch-form', 'App\Http\Controllers\Shortener\GlScratchWebController@form');
    Route::get('scratch/terms', 'App\Http\Controllers\Shortener\GlScratchWebController@terms')->name('shorter-link.terms');
    Route::get('scratch/thank-you', 'App\Http\Controllers\Shortener\GlScratchWebController@thankyou')->name('shorter-link.thank-you');
	    
	Route::post('scr/gl-verify-mobile', 'App\Http\Controllers\Shortener\GlScratchWebController@verifyMobile')->name('/scr/gl-verify-mobile');
    Route::post('scr/gl-verify-otp', 'App\Http\Controllers\Shortener\GlScratchWebController@verifyOtp')->name('gl-verify-otp');
    Route::post('scr/scratch-web-customer', 'App\Http\Controllers\Shortener\GlScratchWebController@scratchCustomer')->name('scratch-web-user');
    Route::post('scr/gl-scratched/{id}/{web_api?}', 'App\Http\Controllers\Shortener\GlScratchWebController@glScratched')->name('scratch-scratched');
    Route::get('w/{code}', 'App\Http\Controllers\Shortener\WhatsappLinkController@index')->name('shorter-wap-link');
    Route::get('wa/{code}', 'App\Http\Controllers\Shortener\GlScratchWebController@gotoApiScratch')->name('go-to-api-scratch');

	Route::get('sc/get-branch-autocomplete/{user_id}', 'App\Http\Controllers\Shortener\GlScratchWebController@getBranchAutocomplete')->name('get-branch-autocomplete');

 });



