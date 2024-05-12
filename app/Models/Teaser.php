<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Teaser extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'story_id',
        'prompt_id',
        'user_id',
    ];

    protected $hidden = [
        'story_id',
        'created_at',
        'updated_at',
    ];

    public function story(): BelongsTo
    {
        return $this->belongsTo(Story::class);
    }

    public function prompt(): BelongsTo
    {
        return $this->belongsTo(Prompt::class);
    }

    public function thumbnails(): HasMany
    {
        return $this->hasMany(Thumbnail::class);
    }
}
