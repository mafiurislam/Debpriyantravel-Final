<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HeroSliderController;
use App\Http\Controllers\Admin\RouteHighlightController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\Admin\VideoReviewController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Admin\TourPackageController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\InquiryController as AdminInquiryController;

/*
|--------------------------------------------------------------------------
| Front-end Website Routes (Debpriyan Travels)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/services', [HomeController::class, 'services'])->name('services');
Route::get('/packages', [HomeController::class, 'packages'])->name('packages');
Route::get('/car-rental', [HomeController::class, 'carRental'])->name('car-rental');
Route::get('/bus-booking', [HomeController::class, 'busBooking'])->name('bus-booking');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Inquiry / Lead Booking Form Submission
Route::post('/inquiry/submit', [InquiryController::class, 'store'])->name('inquiry.store');

// Legacy .html redirects for backward compatibility
Route::get('/index.html', fn() => redirect('/', 301));
Route::get('/about.html', fn() => redirect('/about', 301));
Route::get('/services.html', fn() => redirect('/services', 301));
Route::get('/packages.html', fn() => redirect('/packages', 301));
Route::get('/car-rental.html', fn() => redirect('/car-rental', 301));
Route::get('/bus-booking.html', fn() => redirect('/bus-booking', 301));
Route::get('/contact.html', fn() => redirect('/contact', 301));

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Admin Protected Management Routes (Section-by-Section)
    |--------------------------------------------------------------------------
    */
    Route::middleware('auth')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        // Section 1: Hero Sliders
        Route::resource('sliders', HeroSliderController::class)->except(['show']);

        // Section 2: Route Highlights Banner
        Route::get('/route-highlight', [RouteHighlightController::class, 'edit'])->name('route-highlight.edit');
        Route::match(['post', 'put'], '/route-highlight', [RouteHighlightController::class, 'update'])->name('route-highlight.update');

        // Section 3: Core Services
        Route::resource('services', ServiceController::class)->except(['show']);

        // Section 4: Travel Categories / Specializations
        Route::resource('specializations', SpecializationController::class)->except(['show']);

        // Section 5: Video Reviews
        Route::resource('videos', VideoReviewController::class)->except(['show']);

        // Section 6: Fleet / Vehicles
        Route::resource('vehicles', VehicleController::class)->except(['show']);

        // Section 7: Tour Packages
        Route::resource('packages', TourPackageController::class)->except(['show']);

        // Section 8: Site Settings
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::match(['post', 'put'], '/settings', [SettingController::class, 'update'])->name('settings.update');

        // Section 9: Booking Inquiries
        Route::get('/inquiries', [AdminInquiryController::class, 'index'])->name('inquiries.index');
        Route::patch('/inquiries/{inquiry}/status', [AdminInquiryController::class, 'updateStatus'])->name('inquiries.status');
        Route::delete('/inquiries/{inquiry}', [AdminInquiryController::class, 'destroy'])->name('inquiries.destroy');
    });
});
