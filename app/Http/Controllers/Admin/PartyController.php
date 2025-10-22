<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Party;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class PartyController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $parties = Party::latest()->get();
        $canCreateMore = Party::count() < 2; // Max 2 parties
        
        return view('admin.parties.index', compact('parties', 'canCreateMore'));
    }

    public function create()
    {
        // Check if we already have 2 parties
        if (Party::count() >= 2) {
            return redirect()->route('admin.parties.index')
                           ->with('error', 'Maximum of 2 parties allowed.');
        }
        
        return view('admin.parties.create');
    }

    public function store(Request $request)
    {
        // Check max parties limit
        if (Party::count() >= 2) {
            return redirect()->route('admin.parties.index')
                           ->with('error', 'Maximum of 2 parties allowed.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:parties',
            'slogan' => 'required|string|max:255',
            'color' => 'required|string|max:7', // Hex color
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('party-logos', 'public');
        }

        Party::create($validated);

        return redirect()->route('admin.parties.index')
                        ->with('success', 'Party created successfully.');
    }

    public function show(Party $party)
    {
        return view('admin.parties.show', compact('party'));
    }

    public function edit(Party $party)
    {
        return view('admin.parties.edit', compact('party'));
    }

    public function update(Request $request, Party $party)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('parties')->ignore($party)],
            'slogan' => 'required|string|max:255',
            'color' => 'required|string|max:7',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle logo upload
        if ($request->hasFile('logo')) {
            // Delete old logo
            if ($party->logo) {
                Storage::disk('public')->delete($party->logo);
            }
            
            $validated['logo'] = $request->file('logo')->store('party-logos', 'public');
        }

        $party->update($validated);

        return redirect()->route('admin.parties.index')
                        ->with('success', 'Party updated successfully.');
    }

    public function destroy(Party $party)
    {
        // Check if party has candidates
        if (!$party->canBeDeleted()) {
            return redirect()->route('admin.parties.index')
                           ->with('error', 'Cannot delete party with existing candidates.');
        }

        // Delete logo
        if ($party->logo) {
            Storage::disk('public')->delete($party->logo);
        }

        $party->delete();

        return redirect()->route('admin.parties.index')
                        ->with('success', 'Party deleted successfully.');
    }
}