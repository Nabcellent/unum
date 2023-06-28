<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperExamDate
 */
class ExamDate extends Model
{
    use HasFactory;

    protected $casts = [
        'report_exam_date' => 'datetime:Y-m-d'
    ];
}
