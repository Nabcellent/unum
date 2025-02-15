@extends('layouts.app')
@section('title', 'Mark Entry')
@section('content')
    <div x-data="marks" class="2xl:px-48">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Enter Marks Per Student</h2>
        </div>

        <div class="panel">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="mb-5">
                    <label for="class">Cat</label>
                    <select class="selectize" x-model="exam_id" @change="updateForm" aria-label>
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" @selected($exam->id === $currentExam->id)>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Class</label>
                    <select class="selectize" x-model="grade_id" @change="updateClass" aria-label>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Student</label>
                    <select x-ref="studentSelect" id="student-select" x-model="student_id" @change="updateForm"
                            aria-label>
                        <template x-for="student in students" :key="student.id">
                            <option :value="student.id" x-text="student.name" :selected="student.selected"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <form @submit.prevent="saveMarks" class="panel mt-5 border-0">
            <div class="table-responsive mb-3 overflow-visible">
                <table class="table-striped table-hover">
                    <thead>
                    <tr>
                        <th class="font-bold">Subject</th>
                        <th class="font-bold">Course Work</th>
                        <th class="font-bold">Exam</th>
                        <th class="font-bold">Ave</th>
                        <th class="font-bold">Quarter</th>
                        <th class="font-bold">Rank</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(r, i) in results" :key="i">
                        <tr>
                            <td class="py-1" x-text="r.subject.name"></td>
                            <td class="py-1 w-32">
                                <input
                                    :id="`cw-${i}`"
                                    type="number"
                                    max="99"
                                    min="0"
                                    step="1"
                                    placeholder="%"
                                    class="form-input w-16 px-2 py-1"
                                    x-model="r.course_work_mark"
                                    maxlength="2"
                                    aria-label
                                    @input="onMarkChange(i, 'cw', $event.target, r)"
                                />
                            </td>
                            <td class="py-1">
                                <input
                                    :id="`exam-${i}`"
                                    type="number"
                                    max="99"
                                    min="0"
                                    step="1"
                                    placeholder="%"
                                    class="form-input w-16 px-2 py-1"
                                    x-model="r.exam_mark"
                                    maxlength="2"
                                    aria-label
                                    @input="onMarkChange(i, 'exam', $event.target, r)"
                                />
                            </td>
                            <td class="py-1" x-text="`${r.average ?? ''}%`"></td>
                            <td class="py-1" x-text="r.quarter"></td>
                            <td class="py-1" x-text="r.rank"></td>
                        </tr>
                    </template>
                    </tbody>
                </table>
                <hr class="border-2">
                <table>
                    <tbody>
                    <tr>
                        <td class="py-1" style="text-align: end">Sports</td>
                        <td class="py-1 w-1/5">
                            <select x-ref="sportsSelect" x-model="cumulative_result.sports_grade" class="small"
                                    aria-label>
                                <template x-for="grade in grades" :key="grade">
                                    <option :value="grade" x-text="grade"
                                            :selected="cumulative_result.sports_grade === grade"></option>
                                </template>
                            </select>
                        </td>
                        <td style="text-align: end">Quarter</td>
                        <td class="w-1/5" x-text="cumulative_result.quarter ?? '-'"></td>
                    </tr>
                    <tr>
                        <td class="py-1" style="text-align: end">Conduct</td>
                        <td class="py-1 w-1/5">
                            <select x-ref="conductSelect" x-model="cumulative_result.conduct" class="small">
                                <template x-for="grade in grades" :key="grade">
                                    <option :value="grade" x-text="grade"
                                            :selected="cumulative_result.conduct === grade"></option>
                                </template>
                            </select>
                        </td>
                        <td style="text-align: end">Average</td>
                        <td class="w-1/5" x-text="cumulative_result.average? `${cumulative_result.average}%` : '-'">
                    </tr>
                    <tr>
                        <td class="py-1" style="text-align: end">
                            <span class="block">Attendance</span>
                            <small><i>(Days Absent)</i></small>
                        </td>
                        <td class="py-1 w-1/5">
                            <div class="flex items-center">
                                <input
                                    id="exam"
                                    type="number"
                                    max="99"
                                    min="0"
                                    step="1"
                                    :placeholder="cat_days"
                                    :disabled="!results.length"
                                    class="form-input px-2 mr-2"
                                    x-model="cumulative_result.days_absent"
                                    maxlength="2"
                                    aria-label
                                    @keyup="onAttendanceChange"
                                />
                                <span
                                    x-text="`${Math.round(cumulative_result.days_absent / cat_days * 100)}%`"></span>
                            </div>
                        </td>
                        <td style="text-align: end">Passes</td>
                        <td class="w-1/5" x-text="cumulative_result.passes ?? '-'"></td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-5 flex items-center justify-between">
                <div class="relative inline-flex align-middle">
                    <button type="button" x-tooltip="First Student" @click="goToFirstStudent"
                            :disabled="!canFirstStudent"
                            class="btn btn-warning ltr:rounded-l-full rtl:rounded-r-full ltr:rounded-r-none rtl:rounded-l-none">
                        <i class="fa-solid fa-angles-left"></i>
                    </button>
                    <button type="button" class="btn btn-warning rounded-none" x-tooltip="Previous Student"
                            @click="goToPreviousStudent"
                            :disabled="!canPrevStudent">
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                    <button type="button" class="btn btn-warning rounded-none" x-tooltip="Next Student"
                            @click="goToNextStudent"
                            :disabled="!canNextStudent">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                    <button type="button" x-tooltip="Last Student" @click="goToLastStudent"
                            :disabled="!canLastStudent"
                            class="btn btn-warning ltr:rounded-r-full rtl:rounded-l-full ltr:rounded-l-none rtl:rounded-r-none">
                        <i class="fa-solid fa-angles-right"></i>
                    </button>
                </div>
                <button type="button" class="btn btn-primary" @click="saveMarks"
                        :disabled="!results.length || loading">
                    <i class="fa-solid fa-spinner fa-spin-pulse ltr:mr-2 rtl:ml-2" x-show="loading"></i>
                    <i class="fa-solid fa-floppy-disk ltr:mr-2 rtl:ml-2" x-show="!loading"></i>
                    Save Marks
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            // Marks
            Alpine.data('marks', () => ({
                exam_id: '{{ $currentExam->id }}',
                grade_id: null,
                cat_days: {{ $catDays }},
                results: [],
                cumulative_result: {
                    days_absent: null
                },
                student_id: null,
                students: [],
                student: {},
                studentSelectInstance: null,
                grades: ['A', 'B', 'C', 'D'],
                loading: false,
                canFirstStudent: false,
                canLastStudent: false,
                canNextStudent: false,
                canPrevStudent: false,

                init() {
                    document.querySelectorAll('.selectize').forEach(select => NiceSelect.bind(select));

                    this.studentSelectInstance = NiceSelect.bind(this.$refs.studentSelect, {searchable: true,})
                    this.sportsGradeSelectInstance = NiceSelect.bind(this.$refs.sportsSelect);
                    this.conductGradeSelectInstance = NiceSelect.bind(this.$refs.conductSelect);
                },

                onMarkChange(i, markName, el, result) {
                    const nextInput = () => {
                        if (markName === 'exam') i++

                        const nextInputIdentifier = markName === 'cw' ? 'exam' : 'cw';
                        const nextInputElement = document.getElementById(`${nextInputIdentifier}-${i}`);

                        if (nextInputElement) {
                            nextInputElement.focus();
                        }
                    }

                    if (el.value > 99 || el.value < 0) {
                        el.value = el.value > 99 ? 99 : ''

                        this.showMessage(`The value must be between 0 and 99.`, 'error')

                        return
                    }

                    if (el.value.length === 2) nextInput()

                    result.average = result.course_work_mark && result.exam_mark
                        ? Math.round(result.course_work_mark * .3 + result.exam_mark * .7)
                        : result.exam_mark
                },

                onAttendanceChange(e) {
                    if (e.target.value > this.cat_days) {
                        e.target.value = this.cat_days
                        this.cumulative_result.days_absent = this.cat_days

                        this.showMessage(`Attendance mustn't be above ${this.cat_days} days.`, 'error');
                    } else if (e.target.value < 0) {
                        e.target.value = ''
                        this.cumulative_result.days_absent = null

                        this.showMessage(`Attendance mustn't be a negative number.`, 'error');
                    }
                },

                studentUpdatedEvent() {
                    setTimeout(() => {
                        this.updateForm()

                        this.studentSelectInstance.update()
                    }, 50)
                },

                goToFirstStudent() {
                    this.students[0].selected = true
                    this.student_id = this.students[0].id

                    this.studentUpdatedEvent()
                },

                goToPreviousStudent() {
                    const currentIndex = this.students.findIndex(s => s.id === this.student_id)
                    const prevIndex = (currentIndex - 1) % this.students.length

                    this.students = this.students.map((s, i) => ({...s, selected: i === prevIndex}))
                    this.student_id = this.students[prevIndex].id

                    this.studentUpdatedEvent()
                },

                goToNextStudent() {
                    const currentIndex = this.students.findIndex(s => s.id === this.student_id)
                    const nextIndex = (currentIndex + 1) % this.students.length

                    this.students = this.students.map((s, i) => ({...s, selected: i === nextIndex}))
                    this.student_id = this.students[nextIndex].id

                    this.studentUpdatedEvent()
                },

                goToLastStudent() {
                    this.students = this.students.map((s, i) => ({...s, selected: i === this.students.length - 1}))
                    this.student_id = this.students[this.students.length - 1].id

                    this.studentUpdatedEvent()
                },

                updatePagination() {
                    const studentsExist = this.students.length > 0 && !this.loading
                    const isLastStudent = this.student_id === this.students[this.students.length - 1].id
                    const isFirstStudent = this.student_id === this.students[0].id

                    this.canFirstStudent = studentsExist && !isFirstStudent
                    this.canLastStudent = studentsExist && !isLastStudent
                    this.canPrevStudent = studentsExist && !isFirstStudent
                    this.canNextStudent = studentsExist && !isLastStudent
                },

                updateForm() {
                    if (this.exam_id && this.grade_id && this.student_id) {
                        axios.get(`/api/secondary/students/${this.student_id}/results`, {params: {exam_id: this.exam_id,}})
                            .then(({data: {data}}) => {
                                this.results = data.results
                                this.cumulative_result = data.cumulative_result

                                if (!this.cumulative_result) {
                                    this.cumulative_result = {
                                        days_absent: null
                                    }
                                }

                                setTimeout(() => {
                                    this.sportsGradeSelectInstance.update()
                                    this.conductGradeSelectInstance.update()
                                }, 100)

                                this.updatePagination()
                            }).catch(err => console.error(err))
                    }
                },

                updateClass() {
                    this.results = []

                    axios.get(`/api/grades/${this.grade_id}/students`)
                        .then(({data: {data}}) => {
                            this.students = data

                            if (data[0]) this.student_id = data[0].id

                            this.studentUpdatedEvent()
                        })
                },

                saveMarks() {
                    for (const r of this.results) {
                        if ([1, 2, 3].includes(r.subject_id) && (!r.course_work_mark || !r.exam_mark)) {
                            this.showMessage(`${r.subject.name} mark is required.`, 'error');
                            return true;
                        }
                    }

                    this.loading = true

                    const data = {
                        exam_id: this.exam_id,
                        results: this.results.map(r => ({
                            subject_id: r.subject_id,
                            course_work_mark: r.course_work_mark,
                            exam_mark: r.exam_mark
                        })),
                        cumulative_result: {
                            conduct: this.cumulative_result.conduct,
                            sports_grade: this.cumulative_result.sports_grade,
                            days_absent: this.cumulative_result.days_absent,
                            total_days: this.cumulative_result.total_days ?? this.cat_days,
                        },
                        attendance: this.attendance
                    }

                    axios.post(`/api/results/students/${this.student_id}`, data).then(({data: {status, msg}}) => {
                        if (!status) {
                            this.showMessage(msg, 'error');
                        } else {
                            this.showMessage(msg)

                            this.updateForm()
                        }

                        this.loading = false
                    }).catch(err => {
                        this.loading = false

                        console.error(err)
                        this.showMessage(err.message, 'error')
                    })
                },

                headline: str => {
                    if (!str) return "";

                    str = str.replaceAll('_', ' ').replaceAll('-', ' ');

                    return str.replaceAll(/\w\S*/g, (t) => t.charAt(0).toUpperCase() + t.substring(1).toLowerCase());
                },
            }));
        });
    </script>
@endpush
