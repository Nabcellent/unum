<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperSubStrand
 */
class SubStrand extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'strand_id',
        'name'
    ];

    public function indicators(): HasMany
    {
        return $this->hasMany(Indicator::class);
    }

    public function strand(): BelongsTo
    {
        return $this->belongsTo(Strand::class);
    }
}
