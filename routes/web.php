<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ContestantController;
use App\Http\Controllers\LiveStreamController;
use App\Http\Controllers\VoteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Home page
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Authentication routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes
Route::get('/forgot-password', function () {
    return view('auth.passwords.email');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.passwords.reset', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', [AuthController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');

// Socialite routes
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

// Livestream routes
Route::get('/live', [LiveStreamController::class, 'index'])->name('livestream.index');
Route::get('/live/results', [LiveStreamController::class, 'results'])->name('livestream.results');

// Redirection des anciennes routes candidates vers contestants
Route::get('/candidates', function () {
    return redirect('/contestants');
})->name('candidates.index');

Route::get('/candidates/{candidate}', function ($candidate) {
    return redirect("/contestants/{$candidate}");
})->name('candidates.show');

// Contestants routes
Route::get('/contestants', [ContestantController::class, 'index'])->name('contestants.index');
Route::get('/contestants/{contestant}', [ContestantController::class, 'show'])->name('contestants.show');

// Vote routes
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store')->middleware('auth');
Route::get('/votes/stats', [VoteController::class, 'getPublicStats'])->name('votes.stats');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Contestant dashboard
    Route::get('/dashboard', [ContestantController::class, 'dashboard'])->name('contestants.dashboard');
    Route::get('/dashboard/edit', [ContestantController::class, 'edit'])->name('contestants.edit');
    Route::put('/dashboard/update', [ContestantController::class, 'update'])->name('contestants.update');
    
    // Contestant videos
    Route::get('/dashboard/videos/create', [ContestantController::class, 'createVideo'])->name('contestants.videos.create');
    Route::post('/dashboard/videos', [ContestantController::class, 'storeVideo'])->name('contestants.videos.store');
    Route::get('/dashboard/videos/{video}/edit', [ContestantController::class, 'editVideo'])->name('contestants.videos.edit');
    Route::put('/dashboard/videos/{video}', [ContestantController::class, 'updateVideo'])->name('contestants.videos.update');
    Route::delete('/dashboard/videos/{video}', [ContestantController::class, 'destroyVideo'])->name('contestants.videos.destroy');
});

// Routes d'administration classiques (désactivées pour utiliser Filament)
// Ces routes sont commentées pour éviter les conflits avec Filament
/*
Route::middleware(['auth', 'admin'])->prefix('admin-legacy')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Candidates management
    Route::get('/candidates', [AdminController::class, 'candidates'])->name('candidates');
    Route::post('/candidates', [AdminController::class, 'storeCandidate'])->name('candidates.store');
    Route::put('/candidates/{candidate}', [AdminController::class, 'updateCandidate'])->name('candidates.update');
    Route::delete('/candidates/{candidate}', [AdminController::class, 'destroyCandidate'])->name('candidates.destroy');
    
    // Users management
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::put('/users/{user}/admin', [AdminController::class, 'updateUserAdmin'])->name('users.update-admin');
    
    // Videos management
    Route::get('/videos', [AdminController::class, 'videos'])->name('videos');
    Route::put('/videos/{video}/status', [AdminController::class, 'updateVideoStatus'])->name('videos.update-status');
    
    // Vote statistics
    Route::get('/votes', [AdminController::class, 'voteStats'])->name('votes');
    Route::get('/votes/stats', [VoteController::class, 'getStats'])->name('votes.stats');
    
    // Livestream management
    Route::get('/livestreams', [LiveStreamController::class, 'adminDashboard'])->name('livestream');
    Route::post('/livestreams', [LiveStreamController::class, 'store'])->name('livestream.store');
    Route::put('/livestreams/{livestream}', [LiveStreamController::class, 'update'])->name('livestream.update');
    Route::delete('/livestreams/{livestream}', [LiveStreamController::class, 'destroy'])->name('livestream.destroy');
});

*/

// Route spécifique pour rediriger /admin vers Filament
Route::get('/admin-old', function() {
    return redirect()->to(url('/admin'));
});
