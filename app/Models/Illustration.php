<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Illustration extends Model
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
        'chapter_id',
        'url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
    */

    protected $hidden = [
        'extension',
        'created_at',
        'updated_at',
    ];

    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }
}
