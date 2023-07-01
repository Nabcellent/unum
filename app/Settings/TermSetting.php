<?php

namespace App\Settings;

use App\Enums\Exam;
use Carbon\Carbon;
use Spatie\LaravelSettings\Settings;

class TermSetting extends Settings
{
    public int $days;
    public int $current;
    public Exam $current_exam;
    public ?Carbon $report_exam_date;
    public ?Carbon $next_term_date;

    public static function group(): string
    {
        return 'term';
    }
}
