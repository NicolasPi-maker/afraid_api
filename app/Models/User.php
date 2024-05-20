<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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
        'username',
        'password',
        'firstname',
        'lastname',
        'role_id',
        'subscription_id',
        'is_newsletter_subscriber',
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'subscription_id',
        'role_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    public function rewards(): BelongsToMany
    {
        return $this->belongsToMany(Reward::class);
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'user_id', 'id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'user_id', 'id');
    }

    public function teasers(): HasMany
    {
        return $this->hasMany(Teaser::class, 'user_id', 'id');
    }

}
