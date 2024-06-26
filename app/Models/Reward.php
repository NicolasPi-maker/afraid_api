<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'points',
        'image',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public function user(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
