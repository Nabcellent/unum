@extends('layouts.app')
@section('title', 'Marks')
@section('content')
    <div x-data="marks" class="2xl:px-48">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Enter Marks Per Learning Area</h2>
        </div>

        <div class="panel">
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <div class="mb-5">
                    <label for="cat">Cat</label>
                    <select id="cat" class="selectize" x-model="exam_id" @change="updateTable">
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" @selected($exam->name === $currentExam->name)>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="grade">Grade</label>
                    <select id="grade" class="selectize" x-model="grade_id" @change="fetchLearningAreas">
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-5">
                    <label for="learning-area">Learning Area</label>
                    <select id="learning-area" x-ref="learningAreaNice" x-model="learning_area_id" @change="fetchStrands">
                        <template x-for="l in learning_areas" :key="l.id">
                            <option :value="l.id" x-text="l.name" :selected="l.selected"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="strand">Strand</label>
                    <select id="strand" x-ref="tomStrandEl" x-model="strand_id" @change="fetchSubStrands">
                        <template x-for="s in strands" :key="s.id">
                            <option :value="s.id" x-text="s.name" :selected="s.selected"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="sub-strand">Sub Strands</label>
                    <select id="sub-strand" x-ref="tomSubStrandEl" x-model="sub_strand_id" @change="fetchIndicators">
                        <template x-for="i in indicators" :key="i.id">
                            <option :value="i.id" x-text="i.name" :selected="i.selected"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-5">
                    <label for="sub-strand">Sub Strands</label>
                    <select id="sub-strand" x-ref="tomIndicatorEl" x-model="sub_strand_id" @change="fetchIndicators">
                        <template x-for="i in indicators" :key="i.id">
                            <option :value="i.id" x-text="i.name" :selected="i.selected"></option>
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
                                    aria-label
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
                                    aria-label
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

            <div class="flex justify-between">
                <div class="relative inline-flex align-middle">
                    <button type="button" x-tooltip="First Learning Area" @click="goToFirstLearningArea"
                            :disabled="!canFirstLearningArea"
                            class="btn btn-warning ltr:rounded-l-full rtl:rounded-r-full ltr:rounded-r-none rtl:rounded-l-none">
                        <i class="fa-solid fa-angles-left"></i>
                    </button>
                    <button type="button" class="btn btn-warning rounded-none" x-tooltip="Previous Learning Area" @click="goToPreviousLearningArea"
                            :disabled="!canPrevLearningArea">
                        <i class="fa-solid fa-angle-left"></i>
                    </button>
                    <button type="button" class="btn btn-warning rounded-none" x-tooltip="Next Learning Area" @click="goToNextLearningArea"
                            :disabled="!canNextLearningArea">
                        <i class="fa-solid fa-angle-right"></i>
                    </button>
                    <button type="button" x-tooltip="Last Learning Area" @click="goToLastLearningArea"
                            :disabled="!canLastLearningArea"
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
        document.addEventListener('DOMContentLoaded', function (e) {
            //  Default
            document.querySelectorAll('.selectize').forEach(select => NiceSelect.bind(select));
        });

        document.addEventListener('alpine:init', () => {
            // Marks
            Alpine.data('marks', () => ({
                loading: false,
                updateMarksModal: false,
                exam_id: '<?= $currentExam->id ?>',
                grade_id: null,
                learning_area_id: null,
                learning_areas: [],
                strand_id: null,
                strands: [],
                sub_strands: [],
                sub_strand_id: null,
                indicators: [],
                indicator_id: null,
                students: [],
                learningAreaNice: null,
                canFirstLearningArea: false,
                canLastLearningArea: false,
                canNextLearningArea: false,
                canPrevLearningArea: false,
                tom:{
                    strand:null,
                    subStrand:null,
                    indicator:null,
                },

                init() {
                    const tomSelectOpts = {
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    }
                    this.tom.strand = new TomSelect(this.$refs.tomStrandEl, tomSelectOpts);
                    this.tom.subStrand = new TomSelect(this.$refs.tomSubStrandEl, tomSelectOpts);
                    this.tom.indicator = new TomSelect(this.$refs.tomIndicatorEl, tomSelectOpts);

                    this.learningAreaNice = NiceSelect.bind(this.$refs.learningAreaNice, {searchable: true,})
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

                learningAreaUpdatedEvent() {
                    setTimeout(() => {
                        this.learningAreaNice.update()

                        this.updateTable()
                    }, 50)
                },

                goToFirstLearningArea() {
                    this.learning_areas[0].selected = true
                    this.learning_area_id = this.learning_areas[0].id

                    this.learningAreaUpdatedEvent()
                },
                goToPreviousLearningArea() {
                    const currentIndex = this.learning_areas.findIndex(s => s.id === this.learning_area_id)
                    const prevIndex = (currentIndex - 1) % this.learning_areas.length

                    this.learning_areas = this.learning_areas.map((s, i) => ({...s, selected: i === prevIndex}))
                    this.learning_area_id = this.learning_areas[prevIndex].id

                    this.learningAreaUpdatedEvent()
                },
                goToNextLearningArea() {
                    const currentIndex = this.learning_areas.findIndex(s => s.id === this.learning_area_id)
                    const nextIndex = (currentIndex + 1) % this.learning_areas.length

                    this.learning_areas = this.learning_areas.map((s, i) => ({...s, selected: i === nextIndex}))
                    this.learning_area_id = this.learning_areas[nextIndex].id

                    this.learningAreaUpdatedEvent()
                },
                goToLastLearningArea() {
                    this.learning_areas = this.learning_areas.map((s, i) => ({...s, selected: i === this.learning_areas.length - 1}))
                    this.learning_area_id = this.learning_areas[this.learning_areas.length - 1].id

                    this.learningAreaUpdatedEvent()
                },

                updatePagination() {
                    const learningAreasExist = this.students.length > 0 && !this.loading
                    const isLastLearningArea = this.learning_area_id === this.learning_areas[this.learning_areas.length - 1].id
                    const isFirstLearningArea = this.learning_area_id === this.learning_areas[0].id

                    this.canFirstLearningArea = learningAreasExist && !isFirstLearningArea
                    this.canLastLearningArea = learningAreasExist && !isLastLearningArea
                    this.canPrevLearningArea = learningAreasExist && !isFirstLearningArea
                    this.canNextLearningArea = learningAreasExist && !isLastLearningArea
                },

                updateTable() {
                    if (this.exam_id && this.grade_id && this.learning_area_id) {
                        axios.get(`/api/grades/${this.grade_id}/results`, {
                            params: {
                                exam_id: this.exam_id,
                                learning_area_id: this.learning_area_id,
                            }
                        }).then(({data}) => {
                            this.students = data.map(s => {
                                if (!s.result) {
                                    s.result = {
                                        course_work_mark: null,
                                        exam_mark: null,
                                    }
                                }

                                return s
                            })

                            this.updatePagination()
                        }).catch(err => console.error(err))
                    }
                },

                fetchLearningAreas() {
                    this.learning_areas = []

                    if (this.grade_id) {
                        axios.get(`/api/grades/${this.grade_id}/learning-areas`)
                            .then(({data}) => {
                                this.learning_areas = data.data.map(d => ({...d, selected: d.id === data.data[0].id}))
                                this.learning_area_id = data.data[0]?.id

                                this.learningAreaUpdatedEvent()
                            }).catch(err => console.error(err))
                    }
                },

                fetchStrands() {
                    if (this.learning_area_id) {
                        axios.get(`/api/learning-areas/${this.learning_area_id}/strands`)
                            .then(({data}) => {
                                this.strands = data.map(d => ({...d, selected: d.id === data[0].id}))
                                console.log(this.strands)
                                this.strand_id = data[0]?.id
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

                    axios.post(`/api/results/learning-area`, {
                        marks: this.students.map(s => s.result),
                        learning_area_id: this.learning_area_id,
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
                }
            }));
        });
    </script>
@endpush
