<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PartyController;
use App\Http\Controllers\Admin\ElectionController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\CandidateController;
use App\Http\Controllers\Admin\SettingsController;
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
    
    // Registration Routes (if needed)
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
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    Route::get('/dashboard', function() {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::get('/analytics', function() {
        return view('admin.analytics');
    })->name('analytics');
    
    // Settings
    Route::get('/settings', function() {
        return view('admin.settings');
    })->name('settings');
    
    // Party Management
    Route::get('/parties', function() {
        $parties = collect(); // Empty collection for now
        return view('admin.parties.index', compact('parties'));
    })->name('parties.index');
    
    Route::get('/parties/create', function() {
        return view('admin.parties.create');
    })->name('parties.create');
    
    Route::post('/parties', function() {
        return redirect()->route('admin.parties.index')->with('success', 'Party created successfully!');
    })->name('parties.store');
    
    Route::get('/parties/{party}', function($party) {
        $party = (object) ['id' => $party, 'name' => 'Sample Party', 'description' => 'Sample Description'];
        return view('admin.parties.show', compact('party'));
    })->name('parties.show');
    
    Route::get('/parties/{party}/edit', function($party) {
        $party = (object) ['id' => $party, 'name' => 'Sample Party', 'description' => 'Sample Description'];
        return view('admin.parties.edit', compact('party'));
    })->name('parties.edit');
    
    Route::put('/parties/{party}', function($party) {
        return redirect()->route('admin.parties.index')->with('success', 'Party updated successfully!');
    })->name('parties.update');
    
    Route::delete('/parties/{party}', function($party) {
        return redirect()->route('admin.parties.index')->with('success', 'Party deleted successfully!');
    })->name('parties.destroy');
    
    // Election Management
    Route::get('/elections', function() {
        $elections = collect(); // Empty collection for now
        return view('admin.elections.index', compact('elections'));
    })->name('elections.index');
    
    Route::get('/elections/create', function() {
        return view('admin.elections.create');
    })->name('elections.create');
    
    Route::post('/elections', function() {
        return redirect()->route('admin.elections.index')->with('success', 'Election created successfully!');
    })->name('elections.store');
    
    Route::get('/elections/{election}', function($election) {
        $election = (object) ['id' => $election, 'name' => 'Sample Election', 'description' => 'Sample Description', 'status' => 'pending'];
        return view('admin.elections.show', compact('election'));
    })->name('elections.show');
    
    Route::get('/elections/{election}/edit', function($election) {
        $election = (object) ['id' => $election, 'name' => 'Sample Election', 'description' => 'Sample Description', 'status' => 'pending'];
        return view('admin.elections.edit', compact('election'));
    })->name('elections.edit');
    
    Route::put('/elections/{election}', function($election) {
        return redirect()->route('admin.elections.index')->with('success', 'Election updated successfully!');
    })->name('elections.update');
    
    Route::delete('/elections/{election}', function($election) {
        return redirect()->route('admin.elections.index')->with('success', 'Election deleted successfully!');
    })->name('elections.destroy');
    
    Route::post('/elections/{election}/activate', function($election) {
        return redirect()->route('admin.elections.show', $election)->with('success', 'Election activated successfully!');
    })->name('elections.activate');
    
    Route::post('/elections/{election}/close', function($election) {
        return redirect()->route('admin.elections.show', $election)->with('success', 'Election closed successfully!');
    })->name('elections.close');
    
    Route::get('/elections/{election}/results', function($election) {
        return view('admin.elections.results', ['election' => (object) ['id' => $election, 'name' => 'Sample Election']]);
    })->name('elections.results');
    
    // Global Candidates Management (for navbar link)
    Route::get('/candidates', function() {
        $candidates = collect(); // Empty collection for now
        return view('admin.candidates.index', compact('candidates'));
    })->name('candidates.index');
    
    // Position Management (properly nested under elections)
    Route::prefix('elections/{election}')->name('elections.')->group(function () {
        Route::get('/positions', function($election) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            $positions = collect();
            return view('admin.positions.index', compact('election', 'positions'));
        })->name('positions.index');
        
        Route::get('/positions/create', function($election) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            return view('admin.positions.create', compact('election'));
        })->name('positions.create');
        
        Route::post('/positions', function($election) {
            return redirect()->route('admin.elections.positions.index', $election)->with('success', 'Position created successfully!');
        })->name('positions.store');
        
        Route::get('/positions/{position}', function($election, $position) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            $position = (object) ['id' => $position, 'name' => 'Sample Position'];
            return view('admin.positions.show', compact('election', 'position'));
        })->name('positions.show');
        
        Route::get('/positions/{position}/edit', function($election, $position) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            $position = (object) ['id' => $position, 'name' => 'Sample Position'];
            return view('admin.positions.edit', compact('election', 'position'));
        })->name('positions.edit');
        
        Route::put('/positions/{position}', function($election, $position) {
            return redirect()->route('admin.elections.positions.index', $election)->with('success', 'Position updated successfully!');
        })->name('positions.update');
        
        Route::delete('/positions/{position}', function($election, $position) {
            return redirect()->route('admin.elections.positions.index', $election)->with('success', 'Position deleted successfully!');
        })->name('positions.destroy');
        
        // Candidate Management (properly nested under elections)
        Route::get('/candidates', function($election) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            $candidates = collect();
            $positions = collect();
            $parties = collect();
            return view('admin.candidates.index', compact('election', 'candidates', 'positions', 'parties'));
        })->name('candidates.index');
        
        Route::get('/candidates/create', function($election) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            $positions = collect();
            $parties = collect();
            return view('admin.candidates.create', compact('election', 'positions', 'parties'));
        })->name('candidates.create');
        
        Route::post('/candidates', function($election) {
            return redirect()->route('admin.elections.candidates.index', $election)->with('success', 'Candidate added successfully!');
        })->name('candidates.store');
        
        Route::get('/candidates/{candidate}', function($election, $candidate) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            $candidate = (object) ['id' => $candidate, 'name' => 'Sample Candidate'];
            return view('admin.candidates.show', compact('election', 'candidate'));
        })->name('candidates.show');
        
        Route::get('/candidates/{candidate}/edit', function($election, $candidate) {
            $election = (object) ['id' => $election, 'name' => 'Sample Election'];
            $candidate = (object) ['id' => $candidate, 'name' => 'Sample Candidate'];
            $positions = collect();
            $parties = collect();
            return view('admin.candidates.edit', compact('election', 'candidate', 'positions', 'parties'));
        })->name('candidates.edit');
        
        Route::put('/candidates/{candidate}', function($election, $candidate) {
            return redirect()->route('admin.elections.candidates.index', $election)->with('success', 'Candidate updated successfully!');
        })->name('candidates.update');
        
        Route::delete('/candidates/{candidate}', function($election, $candidate) {
            return redirect()->route('admin.elections.candidates.index', $election)->with('success', 'Candidate deleted successfully!');
        })->name('candidates.destroy');
    });
});

// Student Routes
Route::middleware(['auth'])->prefix('student')->name('student.')->group(function () {
    // Dashboard
    Route::get('/', function () {
        return redirect()->route('student.dashboard');
    });
    Route::get('/dashboard', function() {
        return view('student.dashboard');
    })->name('dashboard');
    
    // Voting Routes
    Route::get('/vote', function() {
        return view('student.vote');
    })->name('vote.index');
    
    Route::post('/vote', function() {
        return redirect()->route('student.vote.confirmation');
    })->name('vote.store');
    
    Route::get('/vote/confirmation', function() {
        return view('student.vote-confirmation');
    })->name('vote.confirmation');
});

// Public Routes (accessible to all authenticated users)
Route::middleware('auth')->group(function () {
    // Profile routes can be added here
    // Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    // Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});