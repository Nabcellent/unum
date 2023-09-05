@extends('layouts.app')
@section('title', 'Marks')
@section('content')
    <div x-data="attendance">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Attendance</h2>
        </div>

        <div class="panel">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="mb-5">
                    <label for="cat">Cat</label>
                    <select id="cat" class="selectize" x-model="exam_id" @change="updateTable">
                        @foreach($exams as $exam)
                            <option
                                value="{{ $exam->id }}" @selected($exam->name === $currentExam->name)>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Class</label>
                    <select id="class" class="selectize" x-model="grade_id" @change="updateTable">
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="panel mt-5 border-0">
            <h3 class="text-base font-bold text-center" x-show="cat_days">
                TOTAL TERM DAYS: <span class="font-extrabold" x-text="cat_days"></span>
            </h3>

            <div class="table-responsive mb-3">
                <table class="table-striped table-hover">
                    <thead>
                    <tr>
                        <th colspan="2" style="width: 206px">Name</th>
                        <th>Days Absent</th>
                        <th>Percentage</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(student, i) in students" :key="student.id">
                        <tr>
                            <td class="py-1 w-3" x-text="student.class_no"></td>
                            <td class="py-1 w-1/3" x-text="student.name"></td>
                            <td class="py-1 w-48">
                                <input
                                    :tabindex="i"
                                    type="number"
                                    :max="cat_days"
                                    min="0"
                                    step="1"
                                    :placeholder="cat_days"
                                    class="form-input w-20 px-2 py-1"
                                    x-model="student.result.days_absent"
                                    maxlength="2"
                                    @keyup="e => onAttendanceChange(e, student)"
                                    aria-label
                                />
                            </td>
                            <td class="py-1"
                                x-text="`${Math.round((student.result.days_absent / cat_days) * 100)}%`"></td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button type="button" class="btn btn-primary" @click="saveAttendances"
                        :disabled="!students.length || loading">
                    <i class="fa-solid fa-spinner fa-spin-pulse ltr:mr-2 rtl:ml-2" x-show="loading"></i>
                    <i class="fa-solid fa-floppy-disk ltr:mr-2 rtl:ml-2" x-show="!loading"></i>
                    Save Marks
                </button>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('attendance', () => ({
                loading: false,
                exam_id: '<?= $currentExam->id ?>',
                grade_id: null,
                cat_days: null,
                students: [],

                init: () => {
                    document.querySelectorAll('.selectize').forEach(select => NiceSelect.bind(select));
                },

                updateTable() {
                    if (this.exam_id && this.grade_id) {
                        axios.get(`/api/grades/${this.grade_id}/attendances`, {
                            params: {exam_id: this.exam_id,}
                        }).then(({data: {data}}) => {
                            this.students = data.students.map(s => {
                                if ('cumulative_result' in s) s.result = s.cumulative_result
                                if ('primary_cumulative_result' in s) s.result = s.primary_cumulative_result

                                if (!s.result) s.result = {student_id: s.id, days_absent: null}

                                return s
                            })

                            console.log(data)

                            this.cat_days = data.cat_days
                        }).catch(err => console.error(err))
                    }
                },

                onAttendanceChange(e, student) {
                    const nextStudent = () => {
                        let currentTab = document.activeElement;
                        let nextTab = currentTab.tabIndex + 1;
                        let nextElement = document.querySelector('[tabindex="' + nextTab + '"]');

                        if (nextElement) nextElement.focus();
                    }

                    if (e.target.value > this.cat_days) {
                        e.target.value = this.cat_days
                        student.result.days_absent = this.cat_days

                        this.showMessage(`Attendance For ${student.name} mustn't be above ${this.cat_days} days.`, 'error');

                        nextStudent()
                    } else if (e.target.value < 0) {
                        e.target.value = ''
                        student.result.days_absent = null

                        this.showMessage(`Attendance mustn't be a negative number.`, 'error');
                    } else if (e.target.value.length === this.cat_days.toString().length) {
                        nextStudent()
                    }
                },

                saveAttendances() {
                    this.loading = true

                    axios.put(`/api/grades/${this.grade_id}/attendances`, {
                        attendances: this.students.map(s => s.result),
                        exam_id: this.exam_id
                    }).then(({data: {status, msg}}) => {
                        if (!status) {
                            this.showMessage(msg, 'error');
                        } else {
                            this.showMessage(msg)

                            this.updateTable()
                            window.scrollTo({top: 0});
                        }

                        this.loading = false
                    }).catch(err => {
                        this.loading = false

                        console.error(err)
                        this.showMessage(err.message, 'error')
                    })
                }
            }));
        });
    </script>
@endpush
