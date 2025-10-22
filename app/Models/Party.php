<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Party extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slogan', 
        'logo',
        'color',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    // Relationships
    public function candidates()
    {
        return $this->hasMany(ElectionCandidate::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Check if party can be deleted (no candidates)
    public function canBeDeleted()
    {
        return $this->candidates()->count() === 0;
    }

    // Get candidates for specific election
    public function candidatesForElection($electionId)
    {
        return $this->candidates()
            ->where('election_id', $electionId)
            ->with('position')
            ->get();
    }
}