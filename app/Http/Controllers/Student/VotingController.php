<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VotingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:student']);
    }

    public function index()
    {
        $activeElection = Election::current()->first();
        
        if (!$activeElection) {
            return redirect()->route('student.dashboard')
                           ->with('info', 'No active election at this time.');
        }

        // Check if user has already voted
        $user = Auth::user();
        if ($user->hasVotedFor($activeElection->id)) {
            return redirect()->route('student.dashboard')
                           ->with('info', 'You have already voted in this election.');
        }

        // Load election with positions and candidates
        $activeElection->load([
            'positions' => function($query) {
                $query->orderBy('order');
            },
            'positions.candidates.party'
        ]);

        return view('student.vote.index', compact('activeElection'));
    }

    public function store(Request $request)
    {
        $activeElection = Election::current()->first();
        
        if (!$activeElection) {
            return redirect()->route('student.dashboard')
                           ->with('error', 'No active election found.');
        }

        $user = Auth::user();
        
        // Check if user has already voted
        if ($user->hasVotedFor($activeElection->id)) {
            return redirect()->route('student.dashboard')
                           ->with('error', 'You have already voted in this election.');
        }

        // Validate votes - one vote per position
        $positions = $activeElection->positions;
        $rules = [];
        
        foreach ($positions as $position) {
            $rules["votes.{$position->id}"] = 'required|exists:election_candidates,id';
        }

        $validated = $request->validate($rules);

        // Store votes in a transaction
        DB::transaction(function () use ($validated, $activeElection, $user) {
            foreach ($validated['votes'] as $positionId => $candidateId) {
                Vote::create([
                    'user_id' => $user->id,
                    'election_id' => $activeElection->id,
                    'position_id' => $positionId,
                    'candidate_id' => $candidateId,
                ]);
            }
        });

        return redirect()->route('student.vote.confirmation')
                        ->with('success', 'Your votes have been successfully cast!');
    }

    public function confirmation()
    {
        $user = Auth::user();
        $activeElection = Election::current()->first();
        
        if (!$activeElection) {
            return redirect()->route('student.dashboard');
        }

        // Get user's votes for this election
        $userVotes = $user->getVotesForElection($activeElection->id);
        
        if ($userVotes->isEmpty()) {
            return redirect()->route('student.vote.index')
                           ->with('info', 'Please cast your votes first.');
        }

        return view('student.vote.confirmation', compact('userVotes', 'activeElection'));
    }
}