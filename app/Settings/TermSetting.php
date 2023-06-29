<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class TermSetting extends Settings
{
    public int $days;

    public static function group(): string
    {
        return 'term';
    }
}
