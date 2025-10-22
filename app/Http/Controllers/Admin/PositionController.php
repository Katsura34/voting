<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionPosition;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index(Election $election)
    {
        $positions = $election->positions()->get();
        
        return view('admin.positions.index', compact('election', 'positions'));
    }

    public function create(Election $election)
    {
        // Only allow adding positions to draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Positions can only be added to draft elections.');
        }
        
        return view('admin.positions.create', compact('election'));
    }

    public function store(Request $request, Election $election)
    {
        // Only allow adding to draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Positions can only be added to draft elections.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 
                      Rule::unique('election_positions')->where('election_id', $election->id)],
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        // Set default order if not provided
        if (!isset($validated['order'])) {
            $validated['order'] = $election->positions()->max('order') + 1;
        }

        $election->positions()->create($validated);

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Position added successfully.');
    }

    public function show(Election $election, ElectionPosition $position)
    {
        $position->load(['candidates.party']);
        
        return view('admin.positions.show', compact('election', 'position'));
    }

    public function edit(Election $election, ElectionPosition $position)
    {
        // Only allow editing positions in draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Positions can only be edited in draft elections.');
        }
        
        return view('admin.positions.edit', compact('election', 'position'));
    }

    public function update(Request $request, Election $election, ElectionPosition $position)
    {
        // Only allow updating in draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Positions can only be updated in draft elections.');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 
                      Rule::unique('election_positions')
                          ->where('election_id', $election->id)
                          ->ignore($position)],
            'description' => 'nullable|string',
            'order' => 'nullable|integer|min:0',
        ]);

        $position->update($validated);

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Position updated successfully.');
    }

    public function destroy(Election $election, ElectionPosition $position)
    {
        // Only allow deleting from draft elections
        if ($election->status !== 'draft') {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Positions can only be deleted from draft elections.');
        }

        // Check if position has candidates
        if ($position->candidates()->count() > 0) {
            return redirect()->route('admin.elections.show', $election)
                           ->with('error', 'Cannot delete position with existing candidates.');
        }

        $position->delete();

        return redirect()->route('admin.elections.show', $election)
                        ->with('success', 'Position deleted successfully.');
    }
}