<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperExam
 */
class Exam extends Model
{
    use HasFactory;

    protected $casts = [
        'name' => \App\Enums\Exam::class
    ];

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function averageResults(): HasMany
    {
        return $this->hasMany(AverageResult::class);
    }
}
