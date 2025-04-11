<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'url',
        'thumbnail',
        'contestant_id',
        'duration',
        'publish_date',
        'status',
        'is_featured'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'publish_date' => 'datetime',
        'is_featured' => 'boolean',
    ];
    
    /**
     * Map the 'published_at' attribute to 'publish_date' in the database
     */
    public function getPublishedAtAttribute()
    {
        return $this->publish_date;
    }
    
    /**
     * Set the 'publish_date' attribute when 'published_at' is set
     */
    public function setPublishedAtAttribute($value)
    {
        $this->attributes['publish_date'] = $value;
    }
    
    /**
     * Map the 'is_published' attribute to 'status' in the database
     */
    public function getIsPublishedAttribute()
    {
        return $this->status === 'published';
    }
    
    /**
     * Set the 'status' attribute when 'is_published' is set
     */
    public function setIsPublishedAttribute($value)
    {
        $this->attributes['status'] = $value ? 'published' : 'draft';
    }

    /**
     * Get the contestant that owns the video.
     */
    public function contestant()
    {
        return $this->belongsTo(Contestant::class, 'contestant_id');
    }
    
    /**
     * Alias for contestant() to maintain backward compatibility
     */
    public function candidate()
    {
        return $this->contestant();
    }
}
