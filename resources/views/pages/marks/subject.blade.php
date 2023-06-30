@extends('layouts.app')
@section('title', 'Marks')
@section('content')
    <div x-data="marks">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Marks</h2>
        </div>

        <div class="panel">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
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
                    <select class="selectize" x-model="grade_id" @change="updateClass">
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Subject</label>
                    <select x-ref="subjectNice" x-model="subject_id" @change="updateTable">
                        <template x-for="subject in subjects" :key="subject.id">
                            <option :value="subject.id" x-text="subject.name" :selected="subject.selected"></option>
                        </template>
                    </select>
                </div>
            </div>
        </div>

        <div class="panel mt-5 border-0">
            <div class="table-responsive mb-3">
                <table class="table-striped table-hover">
                    <thead>
                    <tr>
                        <th colspan="2" style="width: 206px">Name</th>
                        <th>Course Work</th>
                        <th>Exam</th>
                        <th>Ave</th>
                        <th>Quarter</th>
                        <th>Rank</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(student, i) in students" :key="student.id">
                        <tr>
                            <td class="py-1 w-3" x-text="student.class_no"></td>
                            <td class="py-1 w-1/4" x-text="student.name"></td>
                            <td class="py-1 w-32">
                                <input
                                    :id="`cw-${i}`"
                                    @input="onMarkChange(i, 'cw', $event.target)"
                                    type="number"
                                    max="99"
                                    min="0"
                                    step="1"
                                    placeholder="%"
                                    class="form-input w-16 px-2 py-1"
                                    x-model="student.result.course_work_mark"
                                />
                            </td>
                            <td class="py-1">
                                <input
                                    :id="`exam-${i}`"
                                    @input="onMarkChange(i, 'exam', $event.target)"
                                    type="number"
                                    max="99"
                                    min="0"
                                    step="1"
                                    placeholder="%"
                                    class="form-input w-16 px-2 py-1"
                                    x-model="student.result.exam_mark"
                                />
                            </td>
                            <td class="py-1" x-text="student.result?.average"></td>
                            <td class="py-1" x-text="student.result?.quarter"></td>
                            <td class="py-1" x-text="student.result?.rank"></td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-end">
                <button type="button" class="btn btn-primary" @click="saveMarks"
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
        document.addEventListener('DOMContentLoaded', function (e) {
            //  Default
            document.querySelectorAll('.selectize').forEach(select => NiceSelect.bind(select));
        });

        document.addEventListener('alpine:init', () => {
            // Marks
            Alpine.data('marks', () => ({
                loading: false,
                updateMarksModal: false,
                exam_id: '<?= $currentExam ?>',
                grade_id: null,
                subject_id: null,
                subjects: [],
                students: [],
                subjectNice: null,

                init() {
                    this.subjectNice = NiceSelect.bind(this.$refs.subjectNice, {searchable: true,})
                },

                onMarkChange(i, markName, el) {
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
                },

                updateTable() {
                    if (this.exam_id && this.grade_id && this.subject_id) {
                        axios.get(`/api/grades/${this.grade_id}/results`, {
                            params: {
                                exam_id: this.exam_id,
                                subject_id: this.subject_id,
                            }
                        }).then(({data}) => this.students = data.map(s => {
                            if (!s.result) {
                                s.result = {
                                    course_work_mark: null,
                                    exam_mark: null,
                                }
                            }

                            return s
                        })).catch(err => console.error(err))
                    }
                },

                updateClass() {
                    this.subjects = []

                    if (this.grade_id) {
                        axios.get(`/api/grades/${this.grade_id}/subjects`)
                            .then(({data}) => {
                                this.subjects = data.map(d => ({...d, selected: d.id === data[0].id}))
                                this.subject_id = data[0]?.id

                                setTimeout(() => {
                                    this.subjectNice.update()

                                    this.updateTable()
                                }, 50)
                            }).catch(err => console.error(err))
                    }
                },

                saveMarks() {
                    for (const s of this.students) {
                        if (!s.result.exam_mark) {
                            this.showMessage(`Please key in EXAM marks for ${s.class_no}. ${s.name}.`, 'error');
                            return true;
                        }
                    }

                    this.loading = true

                    axios.post(`/api/results/subject`, {
                        marks: this.students.map(s => s.result),
                        subject_id: this.subject_id,
                        exam_id: this.exam_id,
                        grade_id: this.grade_id
                    }, {
                        header: {'Content-Type': 'application/json'}
                    }).then(({data}) => {
                        console.log(data)
                        if (data.status === 'error') {
                            this.showMessage(data.msg, 'error');

                            console.log(data.error)
                        } else {
                            this.showMessage(data.msg)

                            this.updateTable()
                            window.scrollTo({top: 0});
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
