<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin IdeHelperIndicator
 */
class Indicator extends Model
{
    use HasFactory;

    public function subStrand(): BelongsTo
    {
        return $this->belongsTo(SubStrand::class);
    }
}
