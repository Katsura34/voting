<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'start_date',
        'end_date',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    // Relationships
    public function positions()
    {
        return $this->hasMany(ElectionPosition::class)->orderBy('order');
    }

    public function candidates()
    {
        return $this->hasMany(ElectionCandidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function winners()
    {
        return $this->hasMany(ElectionWinner::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCurrent($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    // Status checks
    public function isActive()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    public function isDraft()
    {
        return $this->status === 'draft';
    }

    public function isClosed()
    {
        return $this->status === 'closed' || $this->end_date < now();
    }

    // Vote counting
    public function getTotalVotes()
    {
        return $this->votes()->count();
    }

    public function getVoteCountByPosition($positionId)
    {
        return $this->votes()->where('position_id', $positionId)->count();
    }

    public function getResults()
    {
        return $this->positions()
            ->with(['candidates.votes', 'candidates.party'])
            ->get()
            ->map(function ($position) {
                $candidates = $position->candidates->map(function ($candidate) {
                    $candidate->vote_count = $candidate->votes->count();
                    return $candidate;
                })->sortByDesc('vote_count');
                
                $position->candidates_with_votes = $candidates;
                return $position;
            });
    }
}