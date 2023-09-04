@extends('layouts.app')
@section('title', 'Assess Behaviours')
@section('content')
    <div x-data="marks">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Enter Marks Per Subject</h2>
        </div>

        <div class="panel">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="mb-5">
                    <label for="cat">Cat</label>
                    <select id="cat" x-ref="tomCatEl" x-model="exam_id" @change="updateTable">
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" @selected($exam->name === $currentExam->name)>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="class">Class</label>
                    <select id="class" x-ref="tomClassEl" x-model="grade_id" @change="updateTable">
                        <option value="" selected>Select Grade</option>
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="panel mt-5 border-0">
            <div class="table-responsive mb-3">
                <table class="table-striped table-hover">
                    <thead>
                    <tr>
                        <th colspan="2">Name</th>
                        <template x-for="c in behaviour_categories">
                            <th x-text="str.headline(c)"></th>
                        </template>
                        <th>Conduct</th>
                        <th>Sports</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(student, i) in students" :key="student.id">
                        <tr>
                            <td class="py-1 w-3" x-text="student.class_no"></td>
                            <td class="py-1" x-text="student.name"></td>
                            <template x-for="c in behaviour_categories">
                                <td>
                                    <select class="form-select border-0 border-b rounded-none p-1"
                                            x-model="student.primary_cumulative_result.behaviour[c]" aria-label>
                                        <option value="" selected hidden>Select</option>
                                        <template x-for="c in conduct_values" :key="c">
                                            <option :value="c" x-text="c.toUpperCase()"
                                                    :selected="c === student.primary_cumulative_result.behaviour[c]"></option>
                                        </template>
                                    </select>
                                </td>
                            </template>
                            <td class="py-1">
                                <select class="form-select border-0 border-b rounded-none p-1"
                                        x-model="student.primary_cumulative_result.conduct" aria-label>
                                    <option value="" selected hidden>Select</option>
                                    <template x-for="g in ['A', 'B', 'C', 'D']" :key="g">
                                        <option :value="g" x-text="g"
                                                :selected="g === student.primary_cumulative_result.conduct"></option>
                                    </template>
                                </select>
                            </td>
                            <td class="py-1">
                                <select class="form-select border-0 border-b rounded-none p-1"
                                        x-model="student.primary_cumulative_result.sports_grade" aria-label>
                                    <option value="" selected hidden>Select</option>
                                    <template x-for="g in ['A', 'B', 'C', 'D']" :key="g">
                                        <option :value="g" x-text="g"
                                                :selected="g === student.primary_cumulative_result.sports_grade"></option>
                                    </template>
                                </select>
                            </td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between">
                <div class="relative inline-flex align-middle">
                    <button type="button" x-tooltip="First Subject" @click="goToFirstSubject"
                            :disabled="!canFirstSubject"
                            class="btn btn-warning ltr:rounded-l-full rtl:rounded-r-full ltr:rounded-r-none rtl:rounded-l-none">
                        <i class="fa-solid fa-angles-left"></i>
                    </button>
                    <button type="button" class="btn btn-warning rounded-none" x-tooltip="Previous Subject"
                            @click="goToPreviousSubject"
                            :disabled="!canPrevSubject">
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                    <button type="button" class="btn btn-warning rounded-none" x-tooltip="Next Subject"
                            @click="goToNextSubject"
                            :disabled="!canNextSubject">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                    <button type="button" x-tooltip="Last Subject" @click="goToLastSubject"
                            :disabled="!canLastSubject"
                            class="btn btn-warning ltr:rounded-r-full rtl:rounded-l-full ltr:rounded-l-none rtl:rounded-r-none">
                        <i class="fa-solid fa-angles-right"></i>
                    </button>
                </div>
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
    <script>
        document.addEventListener('alpine:init', () => {
            // Marks
            Alpine.data('marks', () => ({
                loading: false,
                exam_id: '<?= $currentExam->id ?>',
                grade_id: null,
                subjects: [],
                students: [],
                behaviour_categories: [
                    'work',
                    'self_respect',
                    'courtesy',
                    'participates',
                    'cooperates',
                    'enthusiastic',
                    'sets_goals',
                    'confidence',
                ],
                conduct_values: ['consistently', 'often', 'sometimes', 'seldom'],
                subjectNice: null,
                canFirstSubject: false,
                canLastSubject: false,
                canNextSubject: false,
                canPrevSubject: false,

                init() {
                    this.tomCat = new TomSelect(this.$refs.tomCatEl)
                    this.tomClass = new TomSelect(this.$refs.tomClassEl)
                },

                updateTable() {
                    if (this.exam_id && this.grade_id) {
                        axios.get(`/api/grades/${this.grade_id}/cumulative-results`, {
                            params: {exam_id: this.exam_id,}
                        }).then(({data: {data}}) => {
                            this.students = data.map(s => {
                                if (!s.primary_cumulative_result) {
                                    s.primary_cumulative_result = {
                                        behaviour: {
                                            work: null,
                                            self_respect: null,
                                            courtesy: null,
                                            participates: null,
                                            cooperates: null,
                                            enthusiastic: null,
                                            sets_goals: null,
                                            confidence: null,
                                        },
                                    }
                                }

                                return s
                            })
                        }).catch(err => console.error(err))
                    }
                },

                saveMarks() {
                    for (const s of this.students) {
                        const behaviour = s.primary_cumulative_result.behaviour

                        /*for (const c in behaviour) {
                            if (!behaviour[c]) {
                                const cont = confirm(`You have not selected a frequency for ${s.class_no}. ${s.name}`)

                                if(cont) {
                                    break
                                } else {
                                    return
                                }
                            }
                        }*/
                    }

                    this.loading = true

                    axios.put(`/api/primary/cumulative-results`, {
                        results: this.students.map(s => ({...s.primary_cumulative_result, student_id: s.id})),
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
