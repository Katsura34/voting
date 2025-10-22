<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:student']);
    // }

    public function index()
    {
        $user = Auth::user();
        
        // Get current active election
        $activeElection = Election::current()->first();
        
        // Get user's voting history
        $votingHistory = $user->votes()
            ->with(['election', 'position', 'candidate.party'])
            ->latest()
            ->take(5)
            ->get();

        // Check if user has voted in active election
        $hasVotedInActive = false;
        $userVotes = collect();
        
        if ($activeElection) {
            $hasVotedInActive = $user->hasVotedFor($activeElection->id);
            $userVotes = $user->getVotesForElection($activeElection->id);
        }

        // Get upcoming elections
        $upcomingElections = Election::where('status', 'draft')
            ->where('start_date', '>', now())
            ->orderBy('start_date')
            ->take(3)
            ->get();

        return view('student.dashboard', compact(
            'activeElection', 
            'hasVotedInActive', 
            'userVotes', 
            'votingHistory', 
            'upcomingElections'
        ));
    }
}