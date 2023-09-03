<?php

namespace App\Models;

use App\Enums\Level;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperGrade
 */
class Grade extends Model
{
    use HasFactory;

    protected $with = ['stream'];
    protected $appends = ['full_name'];
    protected $casts = [
        'level' => Level::class
    ];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('ancient', function (Builder $builder) {
            $builder->whereNot('name', 'Alumni')->orderBy('name');
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->name{$this->stream?->name}",
        );
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class);
    }

    /**
     * The subjects that belong to the grade.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    /**
     * The learning areas that belong to the grade.
     */
    public function learningAreas(): BelongsToMany
    {
        return $this->belongsToMany(LearningArea::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    /**
     * Scope a query to only include secondary grades.
     */
    public function scopePrimary(Builder $query): void
    {
        $query->where('level', 'primary');
    }

    /**
     * Scope a query to only include primary grades.
     */
    public function scopeSecondary(Builder $query): void
    {
        $query->where('level', 'secondary');
    }
}
