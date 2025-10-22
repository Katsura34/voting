<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Party;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Get dashboard statistics
        $stats = [
            'total_students' => User::students()->count(),
            'total_parties' => Party::active()->count(),
            'total_elections' => Election::count(),
            'active_elections' => Election::active()->count(),
            'total_votes' => Vote::count(),
        ];

        // Get recent elections
        $recentElections = Election::latest()->take(5)->get();

        // Get active election data for charts
        $activeElection = Election::current()->first();
        $chartData = [];
        
        if ($activeElection) {
            $results = $activeElection->getResults();
            $chartData = $this->prepareChartData($results);
        }

        return view('admin.dashboard', compact('stats', 'recentElections', 'chartData', 'activeElection'));
    }

    public function analytics()
    {
        $elections = Election::with(['votes', 'positions.candidates'])->get();
        
        $analyticsData = [];
        foreach ($elections as $election) {
            $analyticsData[] = [
                'name' => $election->name,
                'total_votes' => $election->getTotalVotes(),
                'positions' => $election->positions->count(),
                'candidates' => $election->candidates->count(),
                'status' => $election->status,
            ];
        }

        return view('admin.analytics', compact('analyticsData'));
    }

    private function prepareChartData($results)
    {
        $chartData = [];
        
        foreach ($results as $position) {
            $positionData = [
                'position' => $position->name,
                'candidates' => []
            ];
            
            foreach ($position->candidates_with_votes as $candidate) {
                $positionData['candidates'][] = [
                    'name' => $candidate->name,
                    'party' => $candidate->party->name,
                    'votes' => $candidate->vote_count,
                    'color' => $candidate->party->color
                ];
            }
            
            $chartData[] = $positionData;
        }
        
        return $chartData;
    }
}