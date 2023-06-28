<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperStream
 */
class Stream extends Model
{
    use HasFactory;

    public function grades(): HasMany
    {
        return $this->hasMany(Grade::class);
    }
}
