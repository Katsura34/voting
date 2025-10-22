<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionPosition;
use App\Models\ElectionCandidate;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CandidateController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'role:admin']);
    // }

    /**
     * Global candidates index - shows all candidates across all elections
     */
    public function index(Election $election = null)
    {
        if ($election) {
            // Election-specific candidates (backward compatibility)
            $candidates = $election->candidates()
                ->with(['position', 'party'])
                ->orderBy('position_id')
                ->get()
                ->groupBy('position.name');
            
            return view('admin.candidates.index', compact('election', 'candidates'));
        } else {
            // Global candidates view
            $candidates = ElectionCandidate::with(['election', 'position', 'party'])
                ->join('elections', 'election_candidates.election_id', '=', 'elections.id')
                ->join('election_positions', 'election_candidates.position_id', '=', 'election_positions.id')
                ->orderBy('elections.name')
                ->orderBy('election_positions.name')
                ->select('election_candidates.*')
                ->get()
                ->groupBy('election.name');
            
            $elections = Election::with('positions')->orderBy('created_at', 'desc')->get();
            
            return view('admin.candidates.global-index', compact('candidates', 'elections'));
        }
    }

    /**
     * Global candidate creation form
     */
    public function globalCreate()
    {
        $elections = Election::where('status', 'draft')->with('positions')->get();
        $parties = Party::active()->get();
        
        return view('admin.candidates.global-create', compact('elections', 'parties'));
    }

    /**
     * Store a global candidate
     */
    public function globalStore(Request $request)
    {
        $validated = $request->validate([
            'election_id' => 'required|exists:elections,id',
            'position_id' => 'required|exists:election_positions,id',
            'party_id' => ['required', 'exists:parties,id'],
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'platform' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if election is in draft status
        $election = Election::findOrFail($validated['election_id']);
        if ($election->status !== 'draft') {
            return redirect()->back()
                           ->with('error', 'Candidates can only be added to draft elections.');
        }

        // Check if position belongs to the election
        $position = ElectionPosition::where('id', $validated['position_id'])
                                  ->where('election_id', $validated['election_id'])
                                  ->firstOrFail();

        // Check if position already has 2 candidates (max limit)
        if ($position->hasMaxCandidates()) {
            return redirect()->back()
                           ->with('error', 'Maximum of 2 candidates allowed per position.');
        }

        // Check for duplicate party in same position
        $existingCandidate = ElectionCandidate::where('election_id', $validated['election_id'])
                                            ->where('position_id', $validated['position_id'])
                                            ->where('party_id', $validated['party_id'])
                                            ->exists();
        
        if ($existingCandidate) {
            return redirect()->back()
                           ->with('error', 'A candidate from this party already exists for this position.');
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('candidate-photos', 'public');
        }

        ElectionCandidate::create($validated);

        return redirect()->route('admin.candidates.index')
                        ->with('success', 'Candidate added successfully.');
    }

    public function create(Election $election)
    {
        // Only allow adding candidates to draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Candidates can only be added to draft elections.');
        }

        $positions = $election->positions;
        $parties = Party::active()->get();
        
        return view('admin.candidates.create', compact('election', 'positions', 'parties'));
    }

    public function store(Request $request, Election $election)
    {
        // Only allow adding to draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Candidates can only be added to draft elections.');
        }

        $validated = $request->validate([
            'position_id' => 'required|exists:election_positions,id',
            'party_id' => ['required', 'exists:parties,id',
                          Rule::unique('election_candidates')
                              ->where('election_id', $election->id)
                              ->where('position_id', $request->position_id)],
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'platform' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if position already has 2 candidates (max limit)
        $position = ElectionPosition::findOrFail($validated['position_id']);
        if ($position->hasMaxCandidates()) {
            return redirect()->back()
                           ->with('error', 'Maximum of 2 candidates allowed per position.');
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('candidate-photos', 'public');
        }

        $validated['election_id'] = $election->id;
        ElectionCandidate::create($validated);

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Candidate added successfully.');
    }

    public function show(Election $election = null, ElectionCandidate $candidate = null)
    {
        if (!$candidate && $election) {
            // This handles the case where we're accessing via /admin/candidates/{id}
            $candidate = $election; // In this case, $election is actually the candidate
            $candidate->load(['position', 'party', 'votes', 'election']);
            return view('admin.candidates.show', compact('candidate'));
        }
        
        $candidate->load(['position', 'party', 'votes']);
        
        return view('admin.candidates.show', compact('election', 'candidate'));
    }

    public function edit(Election $election = null, ElectionCandidate $candidate = null)
    {
        if (!$candidate && $election) {
            // This handles the case where we're accessing via /admin/candidates/{id}/edit
            $candidate = $election; // In this case, $election is actually the candidate
            $candidate->load(['election', 'position', 'party']);
            
            // Only allow editing in draft elections
            if ($candidate->election->status !== 'draft') {
                return redirect()->route('admin.candidates.index')
                               ->with('error', 'Candidates can only be edited in draft elections.');
            }

            $elections = Election::where('status', 'draft')->with('positions')->get();
            $parties = Party::active()->get();
            
            return view('admin.candidates.global-edit', compact('candidate', 'elections', 'parties'));
        }
        
        // Only allow editing in draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Candidates can only be edited in draft elections.');
        }

        $positions = $election->positions;
        $parties = Party::active()->get();
        
        return view('admin.candidates.edit', compact('election', 'candidate', 'positions', 'parties'));
    }

    public function update(Request $request, Election $election = null, ElectionCandidate $candidate = null)
    {
        if (!$candidate && $election) {
            // This handles the case where we're accessing via /admin/candidates/{id}
            $candidate = $election; // In this case, $election is actually the candidate
            
            // Only allow updating in draft elections
            if ($candidate->election->status !== 'draft') {
                return redirect()->route('admin.candidates.index')
                               ->with('error', 'Candidates can only be updated in draft elections.');
            }

            $validated = $request->validate([
                'election_id' => 'required|exists:elections,id',
                'position_id' => 'required|exists:election_positions,id',
                'party_id' => 'required|exists:parties,id',
                'name' => 'required|string|max:255',
                'bio' => 'nullable|string',
                'platform' => 'nullable|string',
                'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Check if position belongs to the election
            $position = ElectionPosition::where('id', $validated['position_id'])
                                      ->where('election_id', $validated['election_id'])
                                      ->firstOrFail();

            // Check for duplicate party in same position (excluding current candidate)
            $existingCandidate = ElectionCandidate::where('election_id', $validated['election_id'])
                                                ->where('position_id', $validated['position_id'])
                                                ->where('party_id', $validated['party_id'])
                                                ->where('id', '!=', $candidate->id)
                                                ->exists();
            
            if ($existingCandidate) {
                return redirect()->back()
                               ->with('error', 'A candidate from this party already exists for this position.');
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo
                if ($candidate->photo) {
                    Storage::disk('public')->delete($candidate->photo);
                }
                
                $validated['photo'] = $request->file('photo')->store('candidate-photos', 'public');
            }

            $candidate->update($validated);

            return redirect()->route('admin.candidates.index')
                            ->with('success', 'Candidate updated successfully.');
        }
        
        // Only allow updating in draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Candidates can only be updated in draft elections.');
        }

        $validated = $request->validate([
            'position_id' => 'required|exists:election_positions,id',
            'party_id' => ['required', 'exists:parties,id',
                          Rule::unique('election_candidates')
                              ->where('election_id', $election->id)
                              ->where('position_id', $request->position_id)
                              ->ignore($candidate)],
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'platform' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle photo upload
        if ($request->hasFile('photo')) {
            // Delete old photo
            if ($candidate->photo) {
                Storage::disk('public')->delete($candidate->photo);
            }
            
            $validated['photo'] = $request->file('photo')->store('candidate-photos', 'public');
        }

        $candidate->update($validated);

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Candidate updated successfully.');
    }

    public function destroy(Election $election = null, ElectionCandidate $candidate = null)
    {
        if (!$candidate && $election) {
            // This handles the case where we're accessing via /admin/candidates/{id}
            $candidate = $election; // In this case, $election is actually the candidate
            
            // Only allow deleting from draft elections
            if ($candidate->election->status !== 'draft') {
                return redirect()->route('admin.candidates.index')
                               ->with('error', 'Candidates can only be deleted from draft elections.');
            }

            // Delete photo
            if ($candidate->photo) {
                Storage::disk('public')->delete($candidate->photo);
            }

            $candidate->delete();

            return redirect()->route('admin.candidates.index')
                            ->with('success', 'Candidate deleted successfully.');
        }
        
        // Only allow deleting from draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Candidates can only be deleted from draft elections.');
        }

        // Delete photo
        if ($candidate->photo) {
            Storage::disk('public')->delete($candidate->photo);
        }

        $candidate->delete();

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Candidate deleted successfully.');
    }
}