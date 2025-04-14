<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveStream extends Model
{
    use HasFactory;
    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'live_streams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'embed_url',
        'thumbnail',
        'start_time',
        'end_time',
        'is_active',
        'phase', // 'semi-final' or 'final'
        'literature_file', // Fichier de littérature uploadé
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the contestants participating in this live stream.
     */
    public function contestants()
    {
        return $this->belongsToMany(Contestant::class, 'live_stream_contestants', 'live_stream_id', 'contestant_id');
    }
    
    /**
     * Alias for contestants() to maintain backward compatibility
     */
    public function candidates()
    {
        return $this->contestants();
    }
}
