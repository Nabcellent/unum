<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSubject
 */
class Subject extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'short_name'
    ];

    /**
     * The grades that belong to the subject.
     */
    public function grades(): BelongsToMany
    {
        return $this->belongsToMany(Grade::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }
}
