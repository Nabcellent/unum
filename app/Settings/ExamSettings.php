<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ExamSettings extends Settings
{
    public int $current;

    public static function group(): string
    {
        return 'exam';
    }
}
