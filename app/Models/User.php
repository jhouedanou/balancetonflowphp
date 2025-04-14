<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'provider',
        'provider_id',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the votes associated with the user.
     */
    /**
     * Check if user has admin role
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_has_roles.model_id', $this->id)
            ->where('model_has_roles.model_type', 'App\\Models\\User')
            ->where('roles.name', 'admin')
            ->exists();
    }
    
    /**
     * Magic method to provide backward compatibility with is_admin property
     */
    public function __get($key)
    {
        if ($key === 'is_admin') {
            return $this->isAdmin();
        }
        
        return parent::__get($key);
    }

    /**
     * Get the votes associated with the user.
     */
    /**
     * Determine if the user can access the Filament panel.
     *
     * @param Panel $panel
     * @return bool
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->isAdmin();
    }
    
    /**
     * Vérifie si l'utilisateur est associé à un contestant
     * Méthode sécurisée qui ne provoque pas d'erreur SQL
     */
    public function hasContestantRole(): bool
    {
        // Vérifier si l'utilisateur a un contestant associé
        return $this->contestant()->exists();
    }
    
    /**
     * Relation avec le contestant associé à cet utilisateur
     */
    public function contestant()
    {
        return $this->hasOne(Contestant::class);
    }
    
    /**
     * Alias pour contestant() pour maintenir la compatibilité
     */
    public function candidate()
    {
        return $this->contestant();
    }
    
    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
