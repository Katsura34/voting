<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PartyController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\VotingController;
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

// Root redirect - send users to login if not authenticated
Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('student.dashboard');
    }
    return redirect()->route('login');
})->name('home');

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
    
    // Registration Routes
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Logout Route
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Dashboard redirect based on role
Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('student.dashboard');
})->middleware('auth')->name('dashboard');

// Admin Routes
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/analytics', [AdminDashboardController::class, 'analytics'])->name('analytics');
    
    // Party Management
    Route::resource('parties', PartyController::class);
    
    // Election Management
    Route::resource('elections', ElectionController::class);
    Route::post('elections/{election}/activate', [ElectionController::class, 'activate'])->name('elections.activate');
    Route::post('elections/{election}/close', [ElectionController::class, 'close'])->name('elections.close');
    Route::get('elections/{election}/results', [ElectionController::class, 'results'])->name('elections.results');
    
    // Position Management (nested under elections)
    Route::get('elections/{election}/positions', [PositionController::class, 'index'])->name('elections.positions.index');
    Route::get('elections/{election}/positions/create', [PositionController::class, 'create'])->name('elections.positions.create');
    Route::post('elections/{election}/positions', [PositionController::class, 'store'])->name('elections.positions.store');
    Route::get('elections/{election}/positions/{position}', [PositionController::class, 'show'])->name('elections.positions.show');
    Route::get('elections/{election}/positions/{position}/edit', [PositionController::class, 'edit'])->name('elections.positions.edit');
    Route::put('elections/{election}/positions/{position}', [PositionController::class, 'update'])->name('elections.positions.update');
    Route::delete('elections/{election}/positions/{position}', [PositionController::class, 'destroy'])->name('elections.positions.destroy');
    
    // Candidate Management (nested under elections)
    Route::get('elections/{election}/candidates', [CandidateController::class, 'index'])->name('elections.candidates.index');
    Route::get('elections/{election}/candidates/create', [CandidateController::class, 'create'])->name('elections.candidates.create');
    Route::post('elections/{election}/candidates', [CandidateController::class, 'store'])->name('elections.candidates.store');
    Route::get('elections/{election}/candidates/{candidate}', [CandidateController::class, 'show'])->name('elections.candidates.show');
    Route::get('elections/{election}/candidates/{candidate}/edit', [CandidateController::class, 'edit'])->name('elections.candidates.edit');
    Route::put('elections/{election}/candidates/{candidate}', [CandidateController::class, 'update'])->name('elections.candidates.update');
    Route::delete('elections/{election}/candidates/{candidate}', [CandidateController::class, 'destroy'])->name('elections.candidates.destroy');
});

// Student Routes
Route::middleware(['auth', 'role:student'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return redirect()->route('student.dashboard');
    });
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // Voting Routes
    Route::get('/vote', [VotingController::class, 'index'])->name('vote.index');
    Route::post('/vote', [VotingController::class, 'store'])->name('vote.store');
    Route::get('/vote/confirmation', [VotingController::class, 'confirmation'])->name('vote.confirmation');
});

// Public Routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    // Profile routes can be added here
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    // Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});