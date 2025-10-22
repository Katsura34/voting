<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name', 
        'usn',
        'password',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'usn_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    // Scopes
    public function scopeStudents($query)
    {
        return $query->where('role', 'student');
    }

    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    // Check if user has voted for a specific election
    public function hasVotedFor($electionId, $positionId = null)
    {
        $query = $this->votes()->where('election_id', $electionId);
        
        if ($positionId) {
            $query->where('position_id', $positionId);
        }
        
        return $query->exists();
    }

    // Get user's votes for an election
    public function getVotesForElection($electionId)
    {
        return $this->votes()
            ->where('election_id', $electionId)
            ->with(['position', 'candidate'])
            ->get();
    }
}