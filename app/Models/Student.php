<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin IdeHelperStudent
 */
class Student extends Model
{
    use HasFactory;

    protected $appends = ['name', 'full_name'];
    protected $with = ['user:id,first_name,middle_name,last_name'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('by_class_no', function (Builder $builder) {
            $builder->orderBy('class_no');
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->user->first_name} {$this->user->middle_name} {$this->user->last_name}",
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn() => "{$this->user->first_name} {$this->user->last_name}",
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }

    public function cumulativeResults(): HasMany
    {
        return $this->hasMany(CumulativeResult::class);
    }

    public function cumulativeResult(): HasOne
    {
        return $this->hasOne(CumulativeResult::class);
    }
}
