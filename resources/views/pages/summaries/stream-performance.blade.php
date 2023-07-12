@extends('layouts.app')
@section('title', 'Summaries')
@push('links')
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endpush
@section('content')

    <div x-data="classPerformance">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Class Performance Summary</h2>
        </div>

        <div class="panel mb-3">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="mb-5">
                    <label for="class">Cat</label>
                    <select class="selectize" x-model="exam_id" @change="updatePreview">
                        @foreach($exams as $exam)
                            <option
                                value="{{ $exam->id }}" @selected($exam->name === $currentExam->name)>{{ $exam->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Class</label>
                    <select id="grades" x-ref="tomSelectGradesEl" x-model="grades" aria-label multiple
                            @change="updatePreview">
                        <option value="" selected>Select Grades</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
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
                        @click="saveSummaries([summary.grade_id])">
                    <i class="fa-solid fa-spinner fa-spin-pulse" x-show="loading"></i>
                    <i class="fa-solid fa-download" x-show="!loading"></i>
                </button>

                <div x-html="summary.html" class="mt-3"></div>
            </div>
        </template>
    </div>

@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('classPerformance', () => ({
                loading: false,
                fetchingReport: false,
                exam_id: '<?= $currentExam->id ?>',
                grades: null,
                summaries: [],
                tomSelectGradesInstance: null,

                init: () => {
                    this.tomSelectGradesInstance = new TomSelect('#grades', {
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
                    if (this.exam_id && this.grades) {
                        this.fetchingReport = true

                        axios.get(`/api/summaries/exams/${this.exam_id}/preview`, {
                            params: {grades: this.grades}
                        }).then(({data}) => {
                            if (data.status === 'alert') {
                                this.showMessage(data.msg, data.type)
                            }
                            if (data.status === 'success') {
                                this.summaries = data.summaries
                            }

                            this.fetchingReport = false
                        }).catch(err => {
                            console.error(err)

                            this.fetchingReport = false
                        })
                    }
                },



                saveSummaries(grades) {
                    this.loading = true

                    if(grades.length <= 0) grades = this.grades

                    axios.post(`/api/summaries/exams/${ this.exam_id }`, {
                        grades
                    }).then(({ data }) => {
                        if (data.status === 'success') {
                            this.showMessage(data.msg)
                        } else if (data.status === 'error') {
                            this.showMessage(data.msg, 'error')

                            console.log(data)
                        } else {
                            this.showMessage('Something went wrong', 'error')

                            console.log(data)
                        }

                        this.loading = false
                    }).catch(err => {
                        this.loading = false

                        console.error(err)

                        this.showMessage('Something went wrong', 'error')
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
            }))
        })
    </script>
@endpush
