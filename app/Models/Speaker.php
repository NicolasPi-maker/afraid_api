<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Speaker extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'api_id',
        'gender',
        'use_case',
        'age_description',
        'language',
    ];

    public function speeches(): HasMany
    {
        return $this->hasMany(Speech::class, 'speaker_id', 'id');
    }
}
