<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperLearningAreaAverage
 */
class LearningAreaAverage extends Model
{
    use HasFactory;

    protected $table = 'learning_area_averages_view';

    public function learningArea(): BelongsTo
    {
        return $this->belongsTo(LearningArea::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }
}
