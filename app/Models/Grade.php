<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin IdeHelperGrade
 */
class Grade extends Model
{
    use HasFactory;

    protected $with = ['stream'];
    protected $appends = ['full_name'];

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::addGlobalScope('ancient', function (Builder $builder) {
            $builder->whereNot('name', 'Alumni')->orderBy('name');
        });
    }

    protected function fullName(): Attribute
    {
        return Attribute::make(
            get: fn () => "$this->name{$this->stream?->name}",
        );
    }

    public function stream(): BelongsTo
    {
        return $this->belongsTo(Stream::class);
    }

    /**
     * The subjects that belong to the grade.
     */
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class);
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class);
    }

    public static function classAverage($subject, $table, $darasa)
    {
        $catz = exam();
        $conn = connect_to_db();
        $students_table = students_table();
        if ($subject == 'average') $subj = $subject;
        else $subj = $subject."_mean";
        $daro = "Grade 7A";
        $stream = substr($daro, 0, -1);
        $current_year_accumulated_results_table = current_year_accumulated_results_table();

        if ($table == $current_year_accumulated_results_table)
            $query = "SELECT avg(`$subj`) as ave  FROM `$table` JOIN `$students_table` USING (`student_id`) WHERE `class` like '".$stream."%'and char_length(`$subj`)>0";
        else
            $query = "SELECT avg(`$subj`) as ave  FROM `$table` JOIN `$students_table` USING (`student_id`) WHERE `class` like '".$stream."%'and char_length(`$subj`)>0 and `exam` = '$catz'";

        $resultz = $conn->query($query);

        if ($resultz->num_rows > 0) {
            $rowz = $resultz->fetch_assoc();

            $cow = $rowz['ave'];
        } else {
            $cow = null;
        }

        if ($table == $current_year_accumulated_results_table)
            $queryz = "SELECT avg(`$subj`) as ave  FROM `$table` JOIN `$students_table` USING (`student_id`) WHERE `class` like '".$daro."'and char_length(`$subj`)>0";
        else
            $queryz = "SELECT avg(`$subj`) as ave  FROM `$table` JOIN `$students_table` USING (`student_id`) WHERE `class` like '".$daro."'and char_length(`$subj`)>0 and `exam` like '$catz'";

        $resultzi = $conn->query($queryz);

        if ($resultzi->num_rows > 0) {
            $rowzi = $resultzi->fetch_assoc();
            $exo = $rowzi['ave'];
        } else
            $exo = null;

        if ($darasa == 'all') $accum = $cow;
        else $accum = $exo;

        return ($accum);
    }
}
