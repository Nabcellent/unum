<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperLearningArea
 */
class LearningArea extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name'
    ];

    /**
     * The grades that belong to the subject.
     */
    public function grades(): BelongsToMany
    {
        return $this->belongsToMany(Grade::class);
    }

    public function strands(): HasMany
    {
        return $this->hasMany(Strand::class);
    }
}
