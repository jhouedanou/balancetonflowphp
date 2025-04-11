<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'bio',
        'profile_photo',
        'social_media_links',
        'is_finalist',
        'status',
        'user_id',
    ];
    
    /**
     * Map the 'description' attribute to 'bio' in the database
     */
    public function getDescriptionAttribute()
    {
        return $this->bio;
    }
    
    /**
     * Set the 'bio' attribute when 'description' is set
     */
    public function setDescriptionAttribute($value)
    {
        $this->attributes['bio'] = $value;
    }
    
    /**
     * Map the 'photo' attribute to 'profile_photo' in the database
     */
    public function getPhotoAttribute()
    {
        return $this->profile_photo;
    }
    
    /**
     * Set the 'profile_photo' attribute when 'photo' is set
     */
    public function setPhotoAttribute($value)
    {
        $this->attributes['profile_photo'] = $value;
    }

    /**
     * Get the videos associated with the contestant.
     */
    public function videos()
    {
        return $this->hasMany(Video::class, 'candidate_id');
    }

    /**
     * Get the votes associated with the contestant.
     */
    public function votes()
    {
        return $this->hasMany(Vote::class, 'candidate_id');
    }

    /**
     * Get the user associated with the contestant.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
