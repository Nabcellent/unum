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
 * App\Models\CbcConduct
 *
 * @property int $id
 * @property int $student_id
 * @property int $exam_id
 * @property string $work
 * @property string $self_respect
 * @property string $courtesy
 * @property string $participates
 * @property string $cooperates
 * @property string $enthusiastic
 * @property string $sets_goals
 * @property string $confidence
 * @property string|null $conduct
 * @property string|null $sports_grade
 * @property int|null $attendance
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereAttendance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereConduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereConfidence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereCooperates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereCourtesy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereEnthusiastic($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereParticipates($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereSelfRespect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereSetsGoals($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereSportsGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CbcConduct whereWork($value)
 * @mixin \Eloquent
 */
	class IdeHelperCbcConduct {}
}

namespace App\Models{
/**
 * App\Models\CumulativeExamAverage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeExamAverage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeExamAverage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeExamAverage query()
 * @mixin \Eloquent
 */
	class IdeHelperCumulativeExamAverage {}
}

namespace App\Models{
/**
 * App\Models\CumulativeResult
 *
 * @property int $id
 * @property int $student_id
 * @property int $exam_id
 * @property string|null $average
 * @property int|null $quarter
 * @property int|null $passes
 * @property int|null $rank
 * @property string|null $conduct
 * @property string|null $sports_grade
 * @property int|null $days_attended
 * @property int|null $total_days
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\CumulativeExamAverage|null $cumulativeExamAverage
 * @property-read \App\Models\Exam $exam
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Result> $results
 * @property-read int|null $results_count
 * @property-read \App\Models\Student $student
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult query()
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereAverage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereConduct($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereDaysAttended($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereExamId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult wherePasses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereQuarter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereRank($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereSportsGrade($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereStudentId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereTotalDays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeResult whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class IdeHelperCumulativeResult {}
}

namespace App\Models{
/**
 * App\Models\CumulativeSubjectAverage
 *
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeSubjectAverage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeSubjectAverage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CumulativeSubjectAverage query()
 * @mixin \Eloquent
 */
	class IdeHelperCumulativeSubjectAverage {}
}

namespace App\Models{
/**
 * App\Models\Exam
 *
 * @property int $id
 * @property \App\Enums\Exam $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CumulativeResult> $cumulativeResults
 * @property-read int|null $cumulative_results_count
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
 * @property \Illuminate\Support\Carbon $report_exam_date
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
 * @property string|null $level
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LearningArea> $learningAreas
 * @property-read int|null $learning_areas_count
 * @property-read \App\Models\Stream|null $stream
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Student> $students
 * @property-read int|null $students_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Subject> $subjects
 * @property-read int|null $subjects_count
 * @method static \Illuminate\Database\Eloquent\Builder|Grade newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade primary()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade query()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade secondary()
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Grade whereLevel($value)
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
 * App\Models\Indicator
 *
 * @property int $id
 * @property int $sub_strand_id
 * @property string $name
 * @property string $highly_competent
 * @property string $competent
 * @property string $approaching_competence
 * @property string $needs_improvement
 * @property-read \App\Models\SubStrand $subStrand
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator query()
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereApproachingCompetence($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereCompetent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereHighlyCompetent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereNeedsImprovement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Indicator whereSubStrandId($value)
 * @mixin \Eloquent
 */
	class IdeHelperIndicator {}
}

namespace App\Models{
/**
 * App\Models\LearningArea
 *
 * @property int $id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Grade> $grades
 * @property-read int|null $grades_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Strand> $strands
 * @property-read int|null $strands_count
 * @method static \Illuminate\Database\Eloquent\Builder|LearningArea newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningArea newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningArea query()
 * @method static \Illuminate\Database\Eloquent\Builder|LearningArea whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|LearningArea whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperLearningArea {}
}

namespace App\Models{
/**
 * App\Models\PriResult
 *
 * @method static \Illuminate\Database\Eloquent\Builder|PriResult newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriResult newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|PriResult query()
 * @mixin \Eloquent
 */
	class IdeHelperPriResult {}
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
 * @property-read \App\Models\CumulativeResult $cumulativeResult
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
 * App\Models\Strand
 *
 * @property int $id
 * @property int $learning_area_id
 * @property string $name
 * @property-read \App\Models\LearningArea $learningArea
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SubStrand> $subStrands
 * @property-read int|null $sub_strands_count
 * @method static \Illuminate\Database\Eloquent\Builder|Strand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Strand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Strand query()
 * @method static \Illuminate\Database\Eloquent\Builder|Strand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Strand whereLearningAreaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Strand whereName($value)
 * @mixin \Eloquent
 */
	class IdeHelperStrand {}
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
 * @property-read \App\Models\CumulativeResult|null $cumulativeResult
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\CumulativeResult> $cumulativeResults
 * @property-read int|null $cumulative_results_count
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
 * App\Models\SubStrand
 *
 * @property int $id
 * @property int $strand_id
 * @property string $name
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Indicator> $indicators
 * @property-read int|null $indicators_count
 * @property-read \App\Models\Strand $strand
 * @method static \Illuminate\Database\Eloquent\Builder|SubStrand newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubStrand newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|SubStrand query()
 * @method static \Illuminate\Database\Eloquent\Builder|SubStrand whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubStrand whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|SubStrand whereStrandId($value)
 * @mixin \Eloquent
 */
	class IdeHelperSubStrand {}
}

namespace App\Models{
/**
 * App\Models\Subject
 *
 * @property int $id
 * @property string $name
 * @property string|null $short_name
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

