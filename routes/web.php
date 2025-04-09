<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\SocialiteController;
use App\Http\Controllers\CandidateController;
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
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/logout', function () {
    Auth::logout();
    return redirect()->to(url('/', [], false));
})->name('logout');

// Socialite routes
Route::get('/auth/{provider}/redirect', [SocialiteController::class, 'redirect'])->name('socialite.redirect');
Route::get('/auth/{provider}/callback', [SocialiteController::class, 'callback'])->name('socialite.callback');

// Livestream routes
Route::get('/live', [LiveStreamController::class, 'index'])->name('livestream.index');
Route::get('/live/results', [LiveStreamController::class, 'results'])->name('livestream.results');

// Candidates routes
Route::get('/candidates', [CandidateController::class, 'index'])->name('candidates.index');
Route::get('/candidates/{candidate}', [CandidateController::class, 'show'])->name('candidates.show');

// Vote routes
Route::post('/vote', [VoteController::class, 'store'])->name('vote.store')->middleware('auth');

// Authenticated routes
Route::middleware(['auth'])->group(function () {
    // Candidate dashboard
    Route::get('/dashboard', [CandidateController::class, 'dashboard'])->name('candidates.dashboard');
    Route::get('/dashboard/edit', [CandidateController::class, 'edit'])->name('candidates.edit');
    Route::put('/dashboard/update', [CandidateController::class, 'update'])->name('candidates.update');
    
    // Candidate videos
    Route::get('/dashboard/videos/create', [CandidateController::class, 'createVideo'])->name('candidates.videos.create');
    Route::post('/dashboard/videos', [CandidateController::class, 'storeVideo'])->name('candidates.videos.store');
    Route::get('/dashboard/videos/{video}/edit', [CandidateController::class, 'editVideo'])->name('candidates.videos.edit');
    Route::put('/dashboard/videos/{video}', [CandidateController::class, 'updateVideo'])->name('candidates.videos.update');
    Route::delete('/dashboard/videos/{video}', [CandidateController::class, 'destroyVideo'])->name('candidates.videos.destroy');
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
