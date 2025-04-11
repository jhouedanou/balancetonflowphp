<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'contestant_id', // Keep for backward compatibility
        'candidate_id',  // Add this new field
        'event_id',
        'ip_address',
        'vote_type'
    ];

    /**
     * Get the user that owns the vote.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the contestant that received the vote.
     */
    public function contestant()
    {
        return $this->belongsTo(Contestant::class, 'contestant_id');
    }
    
    /**
     * Get the candidate that received the vote.
     */
    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }
}
