<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionPosition extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'name',
        'description',
        'order'
    ];

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function candidates()
    {
        return $this->hasMany(ElectionCandidate::class, 'position_id');
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'position_id');
    }

    public function winner()
    {
        return $this->hasOne(ElectionWinner::class, 'position_id');
    }

    // Check if position has maximum candidates (2)
    public function hasMaxCandidates()
    {
        return $this->candidates()->count() >= 2;
    }

    // Get vote counts for all candidates
    public function getCandidateVoteCounts()
    {
        return $this->candidates()
            ->withCount('votes')
            ->with('party')
            ->get()
            ->sortByDesc('votes_count');
    }

    // Get total votes for this position
    public function getTotalVotes()
    {
        return $this->votes()->count();
    }
}