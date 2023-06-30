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
                    <label for="class">Cat</label>
                    <select class="selectize" x-model="exam_id" @change="updateTable">
                        @foreach($exams as $exam)
                            <option
                                value="{{ $exam->id }}" @selected($exam->id === $currentExam)>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Class</label>
                    <select class="selectize" x-model="grade_id" @change="updateTable">
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="panel mt-5 border-0">
            <h3 class="text-base font-bold text-center" x-show="term_days">
                TOTAL TERM DAYS: <span class="font-extrabold" x-text="term_days"></span>
            </h3>

            <div class="table-responsive mb-3">
                <table class="table-striped table-hover">
                    <thead>
                    <tr>
                        <th colspan="2" style="width: 206px">Name</th>
                        <th>Days Attended</th>
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
                                    :max="term_days"
                                    min="0"
                                    step="1"
                                    :placeholder="term_days"
                                    class="form-input w-20 px-2 py-1"
                                    x-model="student.cumulative_result.days_attended"
                                    maxlength="2"
                                    @keyup="e => onAttendanceChange(e, student)"
                                    aria-label
                                />
                            </td>
                            <td class="py-1"
                                x-text="`${Math.round((student.cumulative_result.days_attended / term_days) * 100)}%`"></td>
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
    <script src="{{ asset('/js/nice-select2.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('attendance', () => ({
                loading: false,
                exam_id: '<?= $currentExam ?>',
                grade_id: null,
                term_days: null,
                students: [],

                init: () => {
                    document.querySelectorAll('.selectize').forEach(select => NiceSelect.bind(select));
                },

                updateTable() {
                    if (this.exam_id && this.grade_id) {
                        axios.get(`/api/grades/${ this.grade_id }/attendances`, {
                            params: { exam_id: this.exam_id, }
                        }).then(({ data }) => {
                            this.students = data.students.map(s => {
                                if (!s.cumulative_result) {
                                    s.cumulative_result = {
                                        days_attended: null
                                    }
                                }

                                return s
                            })

                            this.term_days = data.term_days
                        }).catch(err => console.error(err))
                    }
                },

                onAttendanceChange(e, student) {
                    const nextStudent = () => {
                        let currentTab = document.activeElement;
                        let nextTab = currentTab.tabIndex + 1;
                        let nextElement = document.querySelector('[tabindex="' + nextTab + '"]');

                        if (nextElement) {
                            nextElement.focus();
                        }
                    }

                    if (e.target.value > this.term_days) {
                        e.target.value = this.term_days
                        student.cumulative_result.days_attended = this.term_days

                        this.showMessage(`Attendance For ${ student.name } mustn't be above ${ this.term_days } days.`, 'error');

                        nextStudent()
                    } else if (e.target.value < 0) {
                        e.target.value = ''
                        student.cumulative_result.days_attended = null

                        this.showMessage(`Attendance mustn't be a negative number.`, 'error');
                    } else if (e.target.value.length === this.term_days.toString().length) {
                        nextStudent()
                    }
                },

                saveAttendances() {
                    this.loading = true

                    axios.put(`/api/grades/${ this.grade_id }/attendances`, this.students.map(s => ({
                        ...s.cumulative_result,
                        total_days: this.term_days
                    }))).then(({ data }) => {
                        if (data.status === 'error') {
                            this.showMessage(data.msg, 'error');

                            console.log(data.error)
                        } else {
                            this.showMessage(data.msg)

                            this.updateTable()
                            window.scrollTo({ top: 0 });
                        }

                        this.loading = false
                    }).catch(err => {
                        this.loading = false

                        console.error(err)
                        this.showMessage(err.message, 'error')
                    })
                },

                showMessage(msg = '', type = 'success') {
                    const toast = window.Swal.mixin({
                        toast: true,
                        position: 'top',
                        showConfirmButton: false,
                        timer: 3000,
                    });

                    toast.fire({
                        icon: type,
                        title: msg,
                        padding: '10px 20px',
                    });
                },
            }));
        });
    </script>
@endpush
