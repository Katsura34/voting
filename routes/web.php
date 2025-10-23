<?php

use App\Http\Controllers\Auth\AuthController;
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
        // Sample data for dashboard
        $data = [
            'totalElections' => 0,
            'activeElections' => 0,
            'totalCandidates' => 0,
            'totalVotes' => 0,
            'totalParties' => 0,
            'pendingElections' => 0,
            'completedElections' => 0,
            'participationRate' => 0,
            'recentElections' => collect(),
            'recentActivity' => []
        ];
        return view('admin.dashboard', $data);
    })->name('dashboard');
    
    // Analytics/Results & Analytics Routes
    Route::get('/analytics', function() {
        return view('admin.analytics');
    })->name('analytics');
    
    Route::get('/results/live', function() {
        return response()->view('admin.results.live', ['message' => 'Live vote counts coming soon']);
    })->name('results.live');
    
    Route::get('/results/history', function() {
        return response()->view('admin.results.history', ['message' => 'Election history coming soon']);
    })->name('results.history');
    
    Route::get('/results/winners', function() {
        return response()->view('admin.results.winners', ['message' => 'Winner reports coming soon']);
    })->name('results.winners');
    
    // Settings
    Route::get('/settings', function() {
        return view('admin.settings');
    })->name('settings');
    
    // Party Management Routes
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
    
    // Election Management Routes
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
    
    // Positions Management Routes (Top Level)
    Route::get('/positions', function() {
        $positions = collect();
        $elections = collect();
        return view('admin.positions.index', compact('positions', 'elections'));
    })->name('positions.index');
    
    Route::get('/positions/create', function() {
        $elections = collect();
        return view('admin.positions.create', compact('elections'));
    })->name('positions.create');
    
    Route::post('/positions', function() {
        return redirect()->route('admin.positions.index')->with('success', 'Position created successfully!');
    })->name('positions.store');
    
    Route::get('/positions/{position}', function($position) {
        $position = (object) ['id' => $position, 'name' => 'Sample Position'];
        return view('admin.positions.show', compact('position'));
    })->name('positions.show');
    
    Route::get('/positions/{position}/edit', function($position) {
        $position = (object) ['id' => $position, 'name' => 'Sample Position'];
        $elections = collect();
        return view('admin.positions.edit', compact('position', 'elections'));
    })->name('positions.edit');
    
    Route::put('/positions/{position}', function($position) {
        return redirect()->route('admin.positions.index')->with('success', 'Position updated successfully!');
    })->name('positions.update');
    
    Route::delete('/positions/{position}', function($position) {
        return redirect()->route('admin.positions.index')->with('success', 'Position deleted successfully!');
    })->name('positions.destroy');
    
    // Candidates Management Routes (Top Level)
    Route::get('/candidates', function() {
        $candidates = collect();
        $elections = collect();
        $positions = collect();
        $parties = collect();
        return view('admin.candidates.index', compact('candidates', 'elections', 'positions', 'parties'));
    })->name('candidates.index');
    
    Route::get('/candidates/create', function() {
        $elections = collect();
        $positions = collect();
        $parties = collect();
        return view('admin.candidates.create', compact('elections', 'positions', 'parties'));
    })->name('candidates.create');
    
    Route::post('/candidates', function() {
        return redirect()->route('admin.candidates.index')->with('success', 'Candidate created successfully!');
    })->name('candidates.store');
    
    Route::get('/candidates/{candidate}', function($candidate) {
        $candidate = (object) ['id' => $candidate, 'name' => 'Sample Candidate'];
        return view('admin.candidates.show', compact('candidate'));
    })->name('candidates.show');
    
    Route::get('/candidates/{candidate}/edit', function($candidate) {
        $candidate = (object) ['id' => $candidate, 'name' => 'Sample Candidate'];
        $elections = collect();
        $positions = collect();
        $parties = collect();
        return view('admin.candidates.edit', compact('candidate', 'elections', 'positions', 'parties'));
    })->name('candidates.edit');
    
    Route::put('/candidates/{candidate}', function($candidate) {
        return redirect()->route('admin.candidates.index')->with('success', 'Candidate updated successfully!');
    })->name('candidates.update');
    
    Route::delete('/candidates/{candidate}', function($candidate) {
        return redirect()->route('admin.candidates.index')->with('success', 'Candidate deleted successfully!');
    })->name('candidates.destroy');
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