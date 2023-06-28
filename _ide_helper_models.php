<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * App\Models\Attendance
 *
 * @property int $id
 * @property int $student_id
 * @property int $days_attended
 * @property int $total_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance query()
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereDaysAttended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereTotalDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Attendance whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAttendance {}
}

namespace App\Models{
/**
 * App\Models\AverageResult
 *
 * @property int $id
 * @property int $student_id
 * @property int $exam_id
 * @property string|null $average
 * @property int|null $quarter
 * @property string|null $sports_grade
 * @property string|null $conduct
 * @property int|null $passes
 * @property int|null $rank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Exam $exam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereConduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult wherePasses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereSportsGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AverageResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperAverageResult {}
}

namespace App\Models{
/**
 * App\Models\Exam
 *
 * @property int $id
 * @property \App\Enums\Exam $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AverageResult> $averageResults
 * @property-read int|null $average_results_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 * @method static \Illuminate\Database\Eloquent\Builder|Exam newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam query()
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Exam whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperExam {}
}

namespace App\Models{
/**
 * App\Models\ExamDate
 *
 * @property int $id
 * @property string $class
 * @property string $entry_completed
 * @property string $quarters_completed
 * @property string $reports_completed
 * @property string $report_exam_date
 * @property string|null $report_next_term
 * @property int $cat_days
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate query()
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereCatDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereClass($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereEntryCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereQuartersCompleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereReportExamDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereReportNextTerm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ExamDate whereReportsCompleted($value)
 * @mixin \Eloquent
 */
	class IdeHelperExamDate {}
}

namespace App\Models{
/**
 * App\Models\Grade
 *
 * @property int $id
 * @property int|null $stream_id
 * @property string $name
 * @property-read \App\Models\Stream|null $stream
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $subjects
 * @property-read int|null $subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder|Grade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereStreamId($value)
 * @mixin \Eloquent
 */
	class IdeHelperGrade {}
}

namespace App\Models{
/**
 * App\Models\Guardian
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Guardian newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guardian newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Guardian query()
 * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Guardian whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperGuardian {}
}

namespace App\Models{
/**
 * App\Models\Result
 *
 * @property int $id
 * @property int $student_id
 * @property int $exam_id
 * @property int $subject_id
 * @property int|null $course_work_mark
 * @property int|null $exam_mark
 * @property int|null $average
 * @property int|null $quarter
 * @property int|null $rank
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AverageResult $averageResult
 * @property-read \App\Models\Exam $exam
 * @property-read \App\Models\Student $student
 * @property-read \App\Models\Subject $subject
 * @method static \Illuminate\Database\Eloquent\Builder|Result newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Result query()
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereCourseWorkMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereExamMark($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereSubjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Result whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperResult {}
}

namespace App\Models{
/**
 * App\Models\Stream
 *
 * @property int $id
 * @property string $name
 * @property string $full_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grade> $grades
 * @property-read int|null $grades_count
 * @method static \Illuminate\Database\Eloquent\Builder|Stream newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stream newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Stream query()
 * @method static \Illuminate\Database\Eloquent\Builder|Stream whereFullName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stream whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Stream whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperStream {}
}

namespace App\Models{
/**
 * App\Models\Student
 *
 * @property int $id
 * @property int $user_id
 * @property int $grade_id
 * @property int|null $tutor_id
 * @property int $team_id
 * @property string $admission_no
 * @property int $class_no
 * @property string $dob
 * @property string $citizenship
 * @property string $religion
 * @property string|null $denomination
 * @property string|null $previous_school
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\AverageResult|null $averageResult
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\AverageResult> $averageResults
 * @property-read int|null $average_results_count
 * @property-read \App\Models\Grade $grade
 * @property-read \App\Models\Result|null $result
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Student newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Student query()
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereAdmissionNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCitizenship($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereClassNo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDenomination($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereGradeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student wherePreviousSchool($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereReligion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereTeamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereTutorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Student whereUserId($value)
 * @mixin \Eloquent
 */
	class IdeHelperStudent {}
}

namespace App\Models{
/**
 * App\Models\Subject
 *
 * @property int $id
 * @property string $name
 * @property string $short_name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grade> $grades
 * @property-read int|null $grades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 * @method static \Illuminate\Database\Eloquent\Builder|Subject newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subject newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Subject query()
 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Subject whereShortName($value)
 * @mixin \Eloquent
 */
	class IdeHelperSubject {}
}

namespace App\Models{
/**
 * App\Models\Team
 *
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|Team newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Team query()
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Team whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperTeam {}
}

namespace App\Models{
/**
 * App\Models\User
 *
 * @property int $id
 * @property string $first_name
 * @property string|null $middle_name
 * @property string $last_name
 * @property string|null $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property mixed $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|User query()
 * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereMiddleName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperUser {}
}

