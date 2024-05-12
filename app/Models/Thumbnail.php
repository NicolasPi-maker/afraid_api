<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Thumbnail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'filename',
        'alt',
        'extension',
        'teaser_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'extension',
    ];

    protected $appends = ['url'];

    public function getUrlAttribute(): string
    {
        $teaser = Teaser::find($this->teaser_id);
        return asset('storage/thumbnails/'.$teaser->title. '/'. $this->filename . '.' . $this->extension);
    }

    public function teaser(): BelongsTo
    {
        return $this->belongsTo(Teaser::class);
    }
}
