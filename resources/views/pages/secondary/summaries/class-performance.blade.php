@extends('layouts.app')
@section('title', 'Summaries')
@section('content')

    <div x-data="classPerformance">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Class Performance Summary</h2>
        </div>

        <div class="panel mb-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:grid-cols-3">
                <div class="mb-5">
                    <label for="cat">Cat</label>
                    <select id="cat" class="selectize" x-model="exam_id" @change="updatePreview">
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" @selected($exam->name === $currentExam->name)>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Class</label>
                    <select id="grade" x-ref="tomSelectGradesEl" x-model="grade" aria-label @change="updatePreview">
                        <option value="" selected>Select Grades</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->name }}">{{ $grade->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="my-auto col-span-2 lg:col-span-1">
                    <button id="addonsRight" type="button" class="btn btn-primary w-full"
                            :disabled="summaries.length < 1 || loading" @click="saveSummaries()">
                        <i class="fa-solid fa-spinner fa-spin-pulse ltr:mr-2 rtl:ml-2" x-show="loading"></i>
                        <i class="fa-solid fa-download ltr:mr-2 rtl:ml-2" x-show="!loading"></i>
                        Save All Summaries
                    </button>
                </div>
            </div>
        </div>

        <div class="panel flex justify-center items-center h-[36rem]" x-show="fetchingReport">
            <i class="fa-regular fa-snowflake fa-spin text-9xl"></i>
        </div>

        <template x-for="(summary, i) in summaries" :key="i">
            <div class="class-performance-summary panel mb-3" x-show="!fetchingReport">
                <button type="button" class="btn btn-sm btn-primary absolute top-0 right-0 m-1" x-tooltip="Save Summary"
                        :disabled="loading"
                        @click="saveSummaries(summary.grade_id)">
                    <i class="fa-solid fa-spinner fa-spin-pulse" x-show="loading"></i>
                    <i class="fa-solid fa-download" x-show="!loading"></i>
                </button>

                <div x-html="summary.html" class="mt-3"></div>
            </div>
        </template>
    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('classPerformance', () => ({
                loading: false,
                fetchingReport: false,
                exam_id: '<?= $currentExam->id ?>',
                grade: null,
                summaries: [],
                tomSelectGradesInstance: null,

                init: () => {
                    this.tomSelectGradesInstance = new TomSelect('#grade', {
                        plugins: {
                            remove_button: {title: 'Remove this item',}
                        },
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });

                    document.querySelectorAll('.selectize').forEach(select => NiceSelect.bind(select));
                },

                updatePreview() {
                    if (this.exam_id && this.grade) {
                        this.fetchingReport = true

                        axios.get(`/api/summaries/exams/${this.exam_id}/preview`, {
                            params: {grade: this.grade}
                        }).then(({data: {status, data}}) => {
                            if (status) {
                                this.summaries = data
                            } else {
                                this.showMessage(msg, 'error')
                            }

                            this.fetchingReport = false
                        }).catch(err => {
                            console.error(err)

                            this.fetchingReport = false
                        })
                    }
                },

                saveSummaries(grade_id = null) {
                    this.loading = true

                    axios.post(`/api/summaries/exams/${this.exam_id}`, {grade: this.grade, grade_id})
                        .then(({data: {status, msg}}) => {
                            if (status) {
                                this.showMessage(msg)
                            } else if (msg) {
                                this.showMessage(msg, 'error')
                            } else {
                                this.showMessage('Something went wrong', 'error')
                            }

                            this.loading = false
                        }).catch(err => {
                        this.loading = false

                        console.error(err)

                        this.showMessage('Something went wrong', 'error')
                    })
                }
            }))
        })
    </script>
@endpush
