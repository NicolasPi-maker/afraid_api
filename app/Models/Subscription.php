<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'description',
        'duration',
        'start_date',
        'expiration_date',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'subscription_id', 'id');
    }
}
