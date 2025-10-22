<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ElectionCandidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'election_id',
        'position_id', 
        'party_id',
        'name',
        'bio',
        'photo',
        'platform'
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

    public function party()
    {
        return $this->belongsTo(Party::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    public function winner()
    {
        return $this->hasOne(ElectionWinner::class, 'candidate_id');
    }

    // Get vote count
    public function getVoteCount()
    {
        return $this->votes()->count();
    }

    // Check if candidate is winner
    public function isWinner()
    {
        return $this->winner()->exists();
    }

    // Get percentage of votes
    public function getVotePercentage()
    {
        $totalVotes = $this->position->getTotalVotes();
        
        if ($totalVotes === 0) {
            return 0;
        }
        
        return round(($this->getVoteCount() / $totalVotes) * 100, 2);
    }
}