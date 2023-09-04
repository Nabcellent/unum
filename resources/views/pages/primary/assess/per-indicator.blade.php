@extends('layouts.app')
@section('title', 'Primary Marks')
@section('content')
    <div x-data="marks" class="2xl:px-20">
        <div class="flex flex-wrap items-center justify-between gap-4 mb-3">
            <h2 class="text-xl">Enter Marks Per Indicator</h2>
        </div>

        <div class="panel">
            <div class="grid grid-cols-1 gap-3 lg:grid-cols-3">
                <div class="mb-3">
                    <label for="cat">Cat</label>
                    <select id="cat" class="selectize" x-model="exam_id" @change="updateTable">
                        @foreach($exams as $exam)
                            <option value="{{ $exam->id }}" @selected($exam->name === $currentExam->name)>
                                {{ $exam->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="grade">Grade</label>
                    <select id="grade" class="selectize" x-model="grade_id" @change="fetchLearningAreas">
                        @foreach($grades as $grade)
                            <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="learning-area">Learning Area</label>
                    <select id="learning-area" x-ref="tomLearningAreaEl" x-model="learning_area_id"
                            @change="fetchStrands">
                        <template x-for="l in learning_areas" :key="l.id">
                            <option :value="l.id" x-text="l.name"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="strand">Strand</label>
                    <select id="strand" x-ref="tomStrandEl" x-model="strand_id" @change="fetchSubStrands">
                        <template x-for="s in strands" :key="s.id">
                            <option :value="s.id" x-text="s.name"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="sub-strand">Sub Strand</label>
                    <select id="sub-strand" x-ref="tomSubStrandEl" x-model="sub_strand_id" @change="fetchIndicators">
                        <template x-for="s in sub_strands" :key="s.id">
                            <option :value="s.id" x-text="s.name"></option>
                        </template>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="indicators">Indicator</label>
                    <select id="indicators" x-ref="tomIndicatorEl" x-model="indicator_id" @change="updateTable">
                        <template x-for="i in indicators" :key="i.id">
                            <option :value="i.id" x-text="i.name"></option>
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
                        <th>Exam</th>
                        <th>Quarter</th>
                        <th>Rank</th>
                    </tr>
                    </thead>
                    <tbody>
                    <template x-for="(student, i) in students" :key="student.id">
                        <tr>
                            <td class="py-1 w-3" x-text="student.class_no"></td>
                            <td class="py-1 w-1/3" x-text="student.name"></td>
                            <td class="py-1">
                                <input
                                    :id="`exam-${i}`"
                                    @input="onMarkChange(i, $event.target)"
                                    aria-label
                                    type="number"
                                    max="99"
                                    min="0"
                                    step="1"
                                    placeholder="%"
                                    class="form-input w-14 px-2 py-1"
                                    x-model="student.primary_result.mark"
                                />
                            </td>
                            <td class="py-1" x-text="student.primary_result?.quarter"></td>
                            <td class="py-1" x-text="student.primary_result?.rank"></td>
                        </tr>
                    </template>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between">
                <div class="flex">
                    <div class="me-2">
                        <div class="relative inline-flex align-middle">
                            <button type="button" x-tooltip="First Indicator" @click="goTo('first', 'i')"
                                    :disabled="!can.firstIndicator"
                                    class="btn btn-sm btn-warning ltr:rounded-l-full rtl:rounded-r-full ltr:rounded-r-none rtl:rounded-l-none">
                                <i class="fa-solid fa-angles-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none"
                                    x-tooltip="Previous Indicator"
                                    @click="goTo('previous', 'i')"
                                    :disabled="!can.prevIndicator">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none" x-tooltip="Next Indicator"
                                    @click="goTo('next', 'i')"
                                    :disabled="!can.nextIndicator">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>
                            <button type="button" x-tooltip="Last Indicator" @click="goTo('last', 'i')"
                                    :disabled="!can.lastIndicator"
                                    class="btn btn-sm btn-warning ltr:rounded-r-full rtl:rounded-l-full ltr:rounded-l-none rtl:rounded-r-none">
                                <i class="fa-solid fa-angles-right"></i>
                            </button>
                        </div>
                        <small class="block text-center">Indicator Navigator</small>
                    </div>
                    <div class="me-2">
                        <div class="relative inline-flex align-middle">
                            <button type="button" x-tooltip="First Sub Strand" @click="goTo('first', 'ss')"
                                    :disabled="!can.firstSubStrand"
                                    class="btn btn-sm btn-warning ltr:rounded-l-full rtl:rounded-r-full ltr:rounded-r-none rtl:rounded-l-none">
                                <i class="fa-solid fa-angles-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none"
                                    x-tooltip="Previous Sub Strand"
                                    @click="goTo('previous', 'ss')"
                                    :disabled="!can.prevSubStrand">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none"
                                    x-tooltip="Next Sub Strand"
                                    @click="goTo('next', 'ss')"
                                    :disabled="!can.nextSubStrand">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>
                            <button type="button" x-tooltip="Last Sub Strand" @click="goTo('last', 'ss')"
                                    :disabled="!can.lastSubStrand"
                                    class="btn btn-sm btn-warning ltr:rounded-r-full rtl:rounded-l-full ltr:rounded-l-none rtl:rounded-r-none">
                                <i class="fa-solid fa-angles-right"></i>
                            </button>
                        </div>
                        <small class="block text-center">Sub Strand Navigator</small>
                    </div>
                    <div class="me-2">
                        <div class="relative inline-flex align-middle">
                            <button type="button" x-tooltip="First Strand" @click="goTo('first', 's')"
                                    :disabled="!can.firstStrand"
                                    class="btn btn-sm btn-warning ltr:rounded-l-full rtl:rounded-r-full ltr:rounded-r-none rtl:rounded-l-none">
                                <i class="fa-solid fa-angles-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none"
                                    x-tooltip="Previous Strand"
                                    @click="goTo('previous', 's')"
                                    :disabled="!can.prevStrand">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none" x-tooltip="Next Strand"
                                    @click="goTo('next', 's')"
                                    :disabled="!can.nextStrand">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>
                            <button type="button" x-tooltip="Last Strand" @click="goTo('last', 's')"
                                    :disabled="!can.lastStrand"
                                    class="btn btn-sm btn-warning ltr:rounded-r-full rtl:rounded-l-full ltr:rounded-l-none rtl:rounded-r-none">
                                <i class="fa-solid fa-angles-right"></i>
                            </button>
                        </div>
                        <small class="block text-center">Strand Navigator</small>
                    </div>
                    <div class="me-2">
                        <div class="relative inline-flex align-middle">
                            <button type="button" x-tooltip="First Learning Area" @click="goTo('first', 'la')"
                                    :disabled="!can.firstLearningArea"
                                    class="btn btn-sm btn-warning ltr:rounded-l-full rtl:rounded-r-full ltr:rounded-r-none rtl:rounded-l-none">
                                <i class="fa-solid fa-angles-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none"
                                    x-tooltip="Previous Learning Area"
                                    @click="goTo('previous', 'la')"
                                    :disabled="!can.prevLearningArea">
                                <i class="fa-solid fa-angle-left"></i>
                            </button>
                            <button type="button" class="btn btn-sm btn-warning rounded-none"
                                    x-tooltip="Next Learning Area"
                                    @click="goTo('next', 'la')"
                                    :disabled="!can.nextLearningArea">
                                <i class="fa-solid fa-angle-right"></i>
                            </button>
                            <button type="button" x-tooltip="Last Learning Area" @click="goTo('last', 'la')"
                                    :disabled="!can.lastLearningArea"
                                    class="btn btn-sm btn-warning ltr:rounded-r-full rtl:rounded-l-full ltr:rounded-l-none rtl:rounded-r-none">
                                <i class="fa-solid fa-angles-right"></i>
                            </button>
                        </div>
                        <small class="block text-center">Learning Area Navigator</small>
                    </div>
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
                exam_id: '{{ $currentExam->id }}',
                grade_id: null,
                learning_area_id: null,
                learning_areas: [],
                strand_id: null,
                strands: [],
                sub_strand_id: null,
                sub_strands: [],
                indicator_id: null,
                indicators: [],
                students: [],
                tom: {
                    learning_area: null,
                    strand: null,
                    sub_strand: null,
                    indicator: null,
                },
                can: {
                    firstIndicator: null,
                    prevIndicator: null,
                    nextIndicator: null,
                    lastIndicator: null,
                    firstSubStrand: null,
                    prevSubStrand: null,
                    nextSubStrand: null,
                    lastSubStrand: null,
                    firstStrand: null,
                    prevStrand: null,
                    nextStrand: null,
                    lastStrand: null,
                    firstLearningArea: null,
                    prevLearningArea: null,
                    nextLearningArea: null,
                    lastLearningArea: null,
                },
                navIndices:{
                    currIIndex:0,
                    currSSIndex:0,
                    currSIndex:0,
                    currLAIndex:0,
                },

                init() {
                    this.tom = {
                        learning_area: new TomSelect(this.$refs.tomLearningAreaEl),
                        strand: new TomSelect(this.$refs.tomStrandEl),
                        sub_strand: new TomSelect(this.$refs.tomSubStrandEl),
                        indicator: new TomSelect(this.$refs.tomIndicatorEl)
                    }
                },

                onMarkChange(i, el) {
                    const nextInput = () => {
                        i++

                        const nextInputElement = document.getElementById(`exam-${i}`);

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

                goTo(direction, category) {
                    this.navIndices = {
                        currIIndex:this.indicators.findIndex(i => i.id === this.indicator_id),
                        currSSIndex:this.sub_strands.findIndex(s => s.id === this.sub_strand_id),
                        currSIndex:this.strands.findIndex(s => s.id === this.strand_id),
                        currLAIndex:this.learning_areas.findIndex(l => l.id === this.learning_area_id),
                    }

                    const categories = {
                        'la': this.learning_areas,
                        's': this.strands,
                        'ss': this.sub_strands,
                        'i': this.indicators,
                    };

                    const currIndex = this.navIndices[`curr${category.toUpperCase()}Index`];
                    const selectedCategory = categories[category];

                    let nextIndex;

                    switch (direction) {
                        case 'first':
                            nextIndex = 0;
                            break;
                        case 'next':
                            nextIndex = (currIndex + 1) % selectedCategory.length;
                            break;
                        case 'previous':
                            nextIndex = (currIndex - 1) % selectedCategory.length;
                            break;
                        default:
                            nextIndex = selectedCategory.length - 1;
                            break;
                    }

                    selectedCategory.forEach((item, i) => {
                        item.selected = i === nextIndex;
                    });

                    switch (category) {
                        case 'la':
                            this.learning_area_id = selectedCategory[nextIndex].id;
                            this.tom.learning_area.addItem(this.learning_area_id, true);
                            this.fetchStrands();
                            break;
                        case 's':
                            this.strand_id = selectedCategory[nextIndex].id;
                            this.tom.strand.addItem(this.strand_id, true);
                            this.fetchSubStrands();
                            break;
                        case 'ss':
                            this.sub_strand_id = selectedCategory[nextIndex].id;
                            this.tom.sub_strand.addItem(this.sub_strand_id, true);
                            this.fetchIndicators();
                            break;
                        case 'i':
                            this.indicator_id = selectedCategory[nextIndex].id;
                            this.tom.indicator.addItem(this.indicator_id, true);
                            this.updateTable();
                            break;
                    }
                },

                updatePagination() {
                    const getPaginationInfo = (arr, currentId) =>({
                        exists: arr.length > 0 && !this.loading,
                        is_first: currentId === arr[0]?.id,
                        is_last: currentId === arr[arr.length - 1]?.id,
                    })

                    const la = getPaginationInfo(this.learning_areas, this.learning_area_id);
                    const s = getPaginationInfo(this.strands, this.strand_id);
                    const ss = getPaginationInfo(this.sub_strands, this.sub_strand_id);
                    const i = getPaginationInfo(this.indicators, this.indicator_id);

                    this.can = {
                        firstIndicator: i.exists && !i.is_first,
                        prevIndicator: i.exists && !i.is_first,
                        nextIndicator: i.exists && !i.is_last,
                        lastIndicator: i.exists && !i.is_last,
                        firstSubStrand: ss.exists && !ss.is_first,
                        prevSubStrand: ss.exists && !ss.is_first,
                        nextSubStrand: ss.exists && !ss.is_last,
                        lastSubStrand: ss.exists && !ss.is_last,
                        firstStrand: s.exists && !s.is_first,
                        prevStrand: s.exists && !s.is_first,
                        nextStrand: s.exists && !s.is_last,
                        lastStrand: s.exists && !s.is_last,
                        firstLearningArea: la.exists && !la.is_first,
                        prevLearningArea: la.exists && !la.is_first,
                        nextLearningArea: la.exists && !la.is_last,
                        lastLearningArea: la.exists && !la.is_last,
                    }
                },

                updateTable() {
                    if (this.exam_id && this.grade_id && this.indicator_id) {
                        axios.get(`/api/grades/${this.grade_id}/results`, {
                            params: {
                                exam_id: this.exam_id,
                                indicator_id: this.indicator_id,
                            }
                        }).then(({data: {data}}) => {
                            this.students = data.map(s => {
                                if (!s.primary_result) {
                                    s.primary_result = {mark: null}
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
                            .then(({data: {data}}) => {
                                this.tom.learning_area.clear()
                                this.tom.learning_area.clearOptions()

                                data.sort((a, b) => a.name.localeCompare(b.name))

                                this.learning_areas = data.map(d => {
                                    this.tom.learning_area.addOption({value: d.id, text: d.name})

                                    return {...d, selected: d.id === data[0].id}
                                })
                                this.learning_area_id = data[0]?.id
                                this.tom.learning_area.addItem(this.learning_area_id, true)

                                this.fetchStrands()
                            }).catch(err => console.error(err))
                    }
                },
                fetchStrands() {
                    if (this.learning_area_id) {
                        axios.get(`/api/learning-areas/${this.learning_area_id}/strands`)
                            .then(({data: {data, status}}) => {
                                if (status) {
                                    this.tom.strand.clear()
                                    this.tom.strand.clearOptions()

                                    this.strand_id = data[0].id

                                    this.strands = data.map(s => {
                                        this.tom.strand.addOption({value: s.id, text: s.name})

                                        return {...s, selected: s.id === Number(this.strand_id)}
                                    })

                                    this.tom.strand.addItem(this.strand_id, true)

                                    this.fetchSubStrands()
                                }
                            })
                    }
                },
                fetchSubStrands() {
                    if (this.strand_id) {
                        axios.get(`/api/strands/${this.strand_id}/sub-strands`).then(({data: {data, status}}) => {
                            if (status) {
                                this.tom.sub_strand.clear()
                                this.tom.sub_strand.clearOptions()

                                this.sub_strand_id = data[0].id

                                this.sub_strands = data.map(s => {
                                    this.tom.sub_strand.addOption({value: s.id, text: s.name})

                                    return s
                                })

                                this.tom.sub_strand.addItem(this.sub_strand_id, true)

                                this.fetchIndicators()
                            }
                        })
                    }
                },
                fetchIndicators() {
                    if (this.sub_strand_id) {
                        axios.get(`/api/sub-strands/${this.sub_strand_id}/indicators`)
                            .then(({data: {data, status}}) => {
                                if (status) {
                                    this.tom.indicator.clear()
                                    this.tom.indicator.clearOptions()

                                    this.indicator_id = data[0].id

                                    this.indicators = data.map(s => {
                                        this.tom.indicator.addOption({value: s.id, text: s.name})

                                        return s
                                    })

                                    this.tom.indicator.addItem(this.indicator_id, true)

                                    this.updateTable()
                                }
                            })
                    }
                },

                saveMarks() {
                    for (const s of this.students) {
                        if (s.primary_result.mark === null) {
                            this.showMessage(`Please key in EXAM marks for ${s.class_no}. ${s.name}.`, 'error');
                            return true;
                        }
                    }

                    this.loading = true

                    axios.put(`/api/primary/results`, {
                        marks: this.students.map(s => ({...s.primary_result, student_id: s.id})),
                        indicator_id: this.indicator_id,
                        exam_id: this.exam_id,
                        grade_id: this.grade_id
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

                        if (err.response.status === 422) {
                            console.log(err.response.data.message)
                            this.showMessage(err.response.data.message, 'error')
                        } else {
                            this.showMessage(err.message, 'error')
                        }
                    })
                }
            }));
        });
    </script>
@endpush
