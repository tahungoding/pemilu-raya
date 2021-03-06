<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

class Voter extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'election_id',
        'nim',
        'name',
        'token',
        'voted',
        'email',
        'email_sent',
    ];

    public function election()
    {
        return $this->belongsTo(Election::class);
    }

    public function storedByUser()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function voting()
    {
        return $this->hasOne(Voting::class);
    }
}
