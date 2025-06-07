<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\RentalsController;
use App\Http\Controllers\AgentsController;
use App\Http\Controllers\ClientDashboardController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AgentDashboardController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\AgentRegistrationController;
use App\Http\Controllers\AdminAgentController;
use App\Http\Middleware\CheckUserRole;
use App\Http\Controllers\AgentModerationController;
use App\Http\Controllers\AdminAdsController;
use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\DB;




/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/



// ✅ Homepage (now mapped to home.index view)
Route::get('/', [HomeController::class, 'index'])->name('home');

// ✅ Breeze default route for dashboard (requires auth)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ✅ Profile routes (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ✅ Breeze auth routes
require __DIR__ . '/auth.php';


Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');

Route::get('/rentals', [RentalsController::class, 'index'])->name('rentals.index');

Route::get('/agents', [AgentsController::class, 'index'])->name('agents');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard/client', [ClientDashboardController::class, 'index'])->name('client.dashboard');

Route::middleware(['auth', CheckUserRole::class . ':admin'])->get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
Route::middleware(['auth', CheckUserRole::class . ':agent'])->get('/agent/dashboard', [AgentDashboardController::class, 'index'])->name('agent.dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/property/create', [PropertyController::class, 'create'])->name('property.create');
    Route::post('/property/store', [PropertyController::class, 'store'])->name('property.store');
});

Route::middleware('guest')->get('/register-agent', [AgentRegistrationController::class, 'showForm'])->name('register.agent');
Route::middleware('guest')->post('/register-agent', [AgentRegistrationController::class, 'submitForm']);

Route::post('/admin/agents/approve', [AgentModerationController::class, 'approve'])->name('admin.agent.approve');
Route::post('/admin/agents/reject', [AgentModerationController::class, 'reject'])->name('admin.agent.reject');

Route::post('/register-agent', [AgentRegistrationController::class, 'submitForm'])->name('register.agent');

Route::post('/admin/ads/mark-paid', [AdminAdsController::class, 'markAsPaid'])->name('admin.ads.markPaid');
Route::post('/admin/ads/update-status', [AdminAdsController::class, 'updateStatus'])->name('admin.ads.updateStatus');

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware(['auth', 'role:agent'])->group(function () {
    Route::get('/agent/post-property', [PropertyController::class, 'create'])->name('agent.post.property');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/property/edit/{id}', [PropertyController::class, 'edit'])->name('property.edit');
    Route::post('/property/update/{id}', [PropertyController::class, 'update'])->name('property.update');
    Route::get('/property/delete/{id}', [PropertyController::class, 'destroy'])->name('property.delete');
});

Route::middleware(['auth'])->post('/rate-agent', [App\Http\Controllers\AgentRatingController::class, 'rate'])->name('agent.rate');

Route::get('/property/{id}', [PropertyController::class, 'show'])->name('property.show');

Route::get('/property/search', [PropertyController::class, 'search'])->name('property.search');

Route::view('/privacy-policy', 'privacy-policy')->name('privacy');

Route::view('/terms-of-service', 'terms-of-service')->name('terms');

Route::get('/contact', [ContactController::class, 'show'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit');

