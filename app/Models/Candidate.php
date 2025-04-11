<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'candidates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'photo',
        'is_finalist',
        'user_id'
    ];

    /**
     * Get the user that owns the candidate profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the votes for the candidate.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    /**
     * Get the videos for the candidate.
     */
    public function videos()
    {
        return $this->hasMany(Video::class, 'candidate_id');
    }
    
    /**
     * Map the 'is_published' attribute to 'status' in the related videos
     */
    public function getPublishedVideosAttribute()
    {
        return $this->videos()->where('status', 'published')->orderBy('publish_date', 'desc')->get();
    }
}
