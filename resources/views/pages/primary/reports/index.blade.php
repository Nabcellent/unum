@extends('layouts.app')
@section('title', 'Primary Reports')
@section('content')

    <div x-data="reports" class="2xl:px-48">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Primary Reports</h2>
        </div>

        <div class="panel mb-3">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div>
                    <label for="cat" class="mb-0">Cat</label>
                    <select id="cat" class="selectize" x-model="exam_id" @change="updatePreview">
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" @selected($exam->id === $currentExam->id)>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="class" class="mb-0">Class</label>
                    <select id="class" class="selectize" x-model="grade_id" @change="updateClass">
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="student" class="mb-0">Student</label>
                    <select id="student" x-ref="studentSelect" x-model="student_id" @change="updatePreview">
                        <template x-for="student in students" :key="student.id">
                            <option :value="student.id" x-text="student.name"></option>
                        </template>
                    </select>
                </div>
                <div class="flex justify-end items-center">
                    <button id="addonsRight" type="button" class="btn btn-primary w-full"
                            :disabled="reports.length < 1 || loading" @click="saveReports()">
                        <i class="fa-solid fa-spinner fa-spin-pulse ltr:mr-2 rtl:ml-2" x-show="loading"></i>
                        <i class="fa-solid fa-download ltr:mr-2 rtl:ml-2" x-show="!loading"></i>
                        Save All Reports
                    </button>
                </div>
            </div>
        </div>

        <div class="panel flex justify-center items-center h-[36rem]" x-show="fetchingReport">
            <i class="fa-regular fa-snowflake fa-spin text-9xl"></i>
        </div>

        <template x-for="(report, i) in reports" :key="i">
            <div class="panel report-preview mb-3" x-show="!fetchingReport">
                <button type="button" class="btn btn-primary absolute top-0 right-0 m-1" x-tooltip="Save Report"
                        :disabled="loading"
                        @click="saveReports(report.student_id)">
                    <i class="fa-solid fa-spinner fa-spin-pulse" x-show="loading"></i>
                    <i class="fa-solid fa-download" x-show="!loading"></i>
                </button>

                <div x-html="report.html"></div>
            </div>
        </template>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            // Reports
            Alpine.data('reports', () => ({
                loading: false,
                fetchingReport: false,
                exam_id: '<?= $currentExam->id ?>',
                grade_id: null,
                student_id: null,
                students: [],
                studentSelectInstance: null,
                reports: [],
                studentSelectDisabled: true,
                buttonIcon: false,

                init() {
                    //  Nice select
                    document.querySelectorAll('.selectize').forEach(select => NiceSelect.bind(select));
                    this.studentSelectInstance = NiceSelect.bind(this.$refs.studentSelect, {searchable: true,})
                },

                updatePreview() {
                    if (this.exam_id && this.grade_id && this.student_id) {
                        this.fetchingReport = true

                        axios.get(`/api/reports/exams/${this.exam_id}/grades/${this.grade_id}/preview`, {
                            params: {student_id: this.student_id}
                        }).then(({data: {data, status}}) => {
                            if (status) {
                                this.reports = data
                            } else {
                                this.showMessage(data.msg, 'error')
                            }

                            this.fetchingReport = false
                        }).catch(err => {
                            console.error(err)

                            this.fetchingReport = false
                        })
                    }
                },

                updateClass() {
                    if (this.student_id) this.student_id = null

                    axios.get(`/api/grades/${this.grade_id}/students`)
                        .then(({data: {data}}) => {
                            this.students = data

                            setTimeout(() => {
                                this.studentSelectInstance.update()

                                this.updatePreview()
                            }, 50)
                        })
                },

                disableStudentSelect() {
                    this.studentSelectInstance[this.studentSelectDisabled ? 'enable' : 'disable']()
                    this.studentSelectDisabled = !this.studentSelectDisabled
                    this.buttonIcon = !this.buttonIcon

                    this.updatePreview()
                },

                saveReports(student_id) {
                    this.loading = true

                    axios.post(`/api/reports/exams/${this.exam_id}/grades/${this.grade_id}`, {student_id})
                        .then(({data: {status, msg}}) => {
                            if (status) {
                                this.showMessage(msg)
                            } else {
                                this.showMessage(msg ?? 'Something went wrong', 'error')
                            }

                            this.loading = false
                        }).catch(err => {
                        this.loading = false

                        console.error(err)

                        this.showMessage('Something went wrong', 'error')
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
