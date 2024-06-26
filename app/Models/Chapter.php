<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapter extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'story_id',
        'order',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function paragraphs(): HasMany
    {
        return $this->hasMany(Paragraphe::class, 'chapter_id', 'id')->orderBy('order');
    }

    public function illustration(): HasMany
    {
        return $this->hasMany(Illustration::class, 'chapter_id', 'id');
    }
}
