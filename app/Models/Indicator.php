<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Indicator extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "sub_strand_id",
        "name",
        "highly_competent",
        "competent",
        "approaching_competence",
        "needs_improvement",
    ];

    public function subStrand(): BelongsTo
    {
        return $this->belongsTo(SubStrand::class);
    }
}
