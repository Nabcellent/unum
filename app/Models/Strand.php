<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperStrand
 */
class Strand extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'learning_area_id',
        'name',
    ];

    public function learningArea(): BelongsTo
    {
        return $this->belongsTo(LearningArea::class);
    }

    public function subStrands(): HasMany
    {
        return $this->hasMany(SubStrand::class);
    }
}
