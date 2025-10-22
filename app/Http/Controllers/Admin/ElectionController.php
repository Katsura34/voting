<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionWinner;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class ElectionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $elections = Election::latest()->get();
        
        return view('admin.elections.index', compact('elections'));
    }

    public function create()
    {
        return view('admin.elections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:elections',
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Convert dates to Carbon instances
        $validated['start_date'] = Carbon::parse($validated['start_date']);
        $validated['end_date'] = Carbon::parse($validated['end_date']);

        Election::create($validated);

        return redirect()->route('admin.elections.index')
                        ->with('success', 'Election created successfully.');
    }

    public function show(Election $election)
    {
        $election->load(['positions.candidates.party', 'votes']);
        $results = $election->getResults();
        
        return view('admin.elections.show', compact('election', 'results'));
    }

    public function edit(Election $election)
    {
        // Only allow editing draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Only draft elections can be edited.');
        }
        
        return view('admin.elections.edit', compact('election'));
    }

    public function update(Request $request, Election $election)
    {
        // Only allow updating draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Only draft elections can be updated.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('elections')->ignore($election)],
            'description' => 'required|string',
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
        ]);

        // Convert dates to Carbon instances
        $validated['start_date'] = Carbon::parse($validated['start_date']);
        $validated['end_date'] = Carbon::parse($validated['end_date']);

        $election->update($validated);

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Election updated successfully.');
    }

    public function activate(Election $election)
    {
        // Validation checks before activation
        if ($election->status !== 'draft') {
            return redirect()->back()->with('error', 'Election is not in draft status.');
        }

        // Check if election has positions
        if ($election->positions()->count() === 0) {
            return redirect()->back()->with('error', 'Election must have at least one position.');
        }

        // Check if all positions have at least 1 candidate
        foreach ($election->positions as $position) {
            if ($position->candidates()->count() === 0) {
                return redirect()->back()
                    ->with('error', "Position '{$position->name}' must have at least one candidate.");
            }
        }

        // Deactivate other active elections
        Election::where('status', 'active')->update(['status' => 'closed']);

        // Activate this election
        $election->update(['status' => 'active']);

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Election activated successfully!');
    }

    public function close(Election $election)
    {
        if ($election->status !== 'active') {
            return redirect()->back()->with('error', 'Only active elections can be closed.');
        }

        // Calculate and store winners
        $this->declareWinners($election);

        // Close the election
        $election->update(['status' => 'closed']);

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Election closed and winners declared!');
    }

    public function results(Election $election)
    {
        $results = $election->getResults();
        $winners = $election->winners()->with(['candidate.party', 'position'])->get();
        
        return view('admin.elections.results', compact('election', 'results', 'winners'));
    }

    private function declareWinners(Election $election)
    {
        // Clear existing winners
        $election->winners()->delete();

        foreach ($election->positions as $position) {
            $candidatesWithVotes = $position->getCandidateVoteCounts();
            
            if ($candidatesWithVotes->isNotEmpty()) {
                $winner = $candidatesWithVotes->first();
                
                ElectionWinner::create([
                    'election_id' => $election->id,
                    'position_id' => $position->id,
                    'candidate_id' => $winner->id,
                    'vote_count' => $winner->votes_count,
                ]);
            }
        }
    }
}