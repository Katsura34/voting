<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionWinner extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'position_id',
        'candidate_id',
        'vote_count'
    ];

    // Relationships
    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function position()
    {
        return $this->belongsTo(ElectionPosition::class, 'position_id');
    }

    public function candidate()
    {
        return $this->belongsTo(ElectionCandidate::class, 'candidate_id');
    }

    // Scopes
    public function scopeForElection($query, $electionId)
    {
        return $query->where('election_id', $electionId);
    }

    // Get percentage of votes
    public function getVotePercentage()
    {
        $totalVotes = $this->position->getTotalVotes();
        
        if ($totalVotes === 0) {
            return 0;
        }
        
        return round(($this->vote_count / $totalVotes) * 100, 2);
    }
}