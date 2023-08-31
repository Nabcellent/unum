<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperSubStrand
 */
class SubStrand extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'strand_id',
        'name',
        "indicator",
        "highly_competent",
        "competent",
        "approaching_competence",
        "needs_improvement",
    ];

    public function strand(): BelongsTo
    {
        return $this->belongsTo(Strand::class);
    }
}
