<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;

    protected $fillable = [
        'period',
        'total_voters',
        'voted_voters',
        'unvoted_voters',
        'total_candidates',
        'election_winner',
        'chairman',
        'vice_chairman',
        'chairman_photo',
        'vice_chairman_photo',
        'archived',
    ];

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function voters()
    {
        return $this->hasMany(Voter::class);
    }

    public function votings()
    {
        return $this->hasMany(Voting::class);
    }
}