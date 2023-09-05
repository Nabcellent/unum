<?php

use App\Enums\Exam;
use Spatie\LaravelSettings\Migrations\SettingsBlueprint;
use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->inGroup('term', function(SettingsBlueprint $blueprint) {
            $blueprint->add('cat_days', 37);
            $blueprint->add('current', 2);
            $blueprint->add('current_exam', Exam::CAT_1);

            $blueprint->add('report_exam_date', now());
            $blueprint->add('next_term_date');
        });
    }
};
