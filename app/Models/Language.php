<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Language extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function speeches(): HasMany
    {
        return $this->hasMany(Speech::class, 'language_id', 'id');
    }

    public function stories(): HasMany
    {
        return $this->hasMany(Story::class, 'language_id', 'id');
    }
}
