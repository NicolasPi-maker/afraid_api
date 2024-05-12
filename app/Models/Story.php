<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Story extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'creation_date',
        'last_update',
        'description',
        'user_id',
        'intensity_id',
        'background_sound_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'user_id',
        'intensity_id',
        'background_sound_id',
        'created_at',
        'updated_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function intensity(): BelongsTo
    {
        return $this->belongsTo(Intensity::class);
    }

    public function backgroundSound(): BelongsTo
    {
        return $this->belongsTo(BackgroundSound::class);
    }

    public function category(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'story_category', 'story_id', 'category_id');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class, 'story_id', 'id');
    }

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class, 'story_id', 'id')->orderBy('order');
    }

    public function speeches(): HasMany
    {
        return $this->hasMany(Speech::class, 'story_id', 'id');
    }

    public function teasers(): HasMany
    {
        return $this->hasMany(Teaser::class, 'story_id', 'id');
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(Language::class, 'story_language', 'story_id', 'language_id');
    }
}
