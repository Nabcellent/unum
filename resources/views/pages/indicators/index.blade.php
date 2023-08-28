@extends('layouts.app')
@section('title', 'Indicators')
@push('links')
    <link href="{{ asset('/vendors/tom-select/tom-select.css') }}" rel="stylesheet">
@endpush
@section('content')

    <div x-data="indicators">
        <!-- button -->
        <button type="button" class="btn btn-warning mb-3 text-end ml-auto" @click="toggleModal">
            Create Indicator
        </button>
        <!-- modal -->
        <div class="fixed inset-0 bg-[black]/60 z-[999]  hidden" :class="openModal && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="openModal = false">
                <div x-show="openModal" x-transition x-transition.duration.300
                     class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-5xl my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">
                            <span x-text="update ? 'Edit':'Create'"></span> Indicator
                        </h5>
                        <button type="button" class="text-white-dark hover:text-dark" @click="toggleModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24px" height="24px" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                 stroke-linejoin="round" class="h-6 w-6">
                                <line x1="18" y1="6" x2="6" y2="18"></line>
                                <line x1="6" y1="6" x2="18" y2="18"></line>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <div x-show="Object.keys(errors).length > 0"
                             class="bg-red-100 border-t-4 border-red-500 rounded-b text-red-900 px-4 py-3 shadow-md mb-3"
                             role="alert">
                            <div class="flex">
                                <div class="py-1">
                                    <svg class="fill-current h-6 w-6 text-red-500 mr-4 rotate-180"
                                         xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path
                                            d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-bold">Oops! Something's not right.</p>
                                    <ol class="list-[lower-roman] ms-5">
                                        <template x-for="e in Object.values(errors)">
                                            <li class="text-sm" x-text="e"></li>
                                        </template>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="dark:text-white-dark/70 text-base font-medium text-[#1f2937]">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="mb-0 text-gray-400">Learning Area</label>
                                    <select x-ref="tomLearningAreaEl" x-model="learning_area_id" aria-label
                                            @change="fetchStrands">
                                        <option value="" selected>Select Learning Area</option>
                                        @foreach($learningAreas as $lA)
                                            <option value="{{ $lA->id }}">{{ $lA->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="mb-0 text-gray-400">Strand</label>
                                    <select x-ref="tomStrandEl" x-model="strand_id" aria-label
                                            @change="fetchSubStrands">
                                        <option value="" selected>Select Strand</option>
                                        <template x-for="strand in strands" :key="strand.id">
                                            <option :value="strand.id" x-text="strand.name"
                                                    :selected="strand.selected"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="col-span-2">
                                    <label class="mb-0 text-gray-400">Sub Strand</label>
                                    <select x-ref="tomSubStrandEl" x-model="form.sub_strand_id" aria-label>
                                        <option value="" selected>Select Sub Strand</option>
                                        <template x-for="s in sub_strands" :key="s.id">
                                            <option :value="s.id" x-text="s.name" :selected="s.selected"></option>
                                        </template>
                                    </select>
                                </div>

                                <div class="col-span-2">
                                    <label class="mb-0 text-gray-400">Indicator</label>
                                    <input type="text" placeholder="Enter indicator name" class="form-input" required
                                           aria-label
                                           x-model="form.name"/>
                                </div>

                                <div>
                                    <label class="mb-0 text-gray-400">Highly Competent</label>
                                    <textarea placeholder="Enter highly competent comment..." rows="3"
                                              class="form-input" required aria-label
                                              x-model="form.highly_competent"></textarea>
                                </div>
                                <div>
                                    <label class="mb-0 text-gray-400">Competent</label>
                                    <textarea placeholder="Enter competent comment..." rows="3" class="form-input"
                                              required aria-label
                                              x-model="form.competent"></textarea>
                                </div>
                                <div>
                                    <label class="mb-0 text-gray-400">Approaching Competence</label>
                                    <textarea placeholder="Enter approaching competence comment..." rows="3"
                                              class="form-input" required aria-label
                                              x-model="form.approaching_competence"></textarea>
                                </div>
                                <div>
                                    <label class="mb-0 text-gray-400">Needs Improvement</label>
                                    <textarea placeholder="Enter needs improvement comment..." rows="3"
                                              class="form-input" required aria-label
                                              x-model="form.needs_improvement"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-end items-center mt-8">
                            <button type="button" class="btn btn-outline-danger" @click="toggleModal">Discard</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                    :disabled="!form.name || loading" @click="saveIndicator">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <div class="lg:flex gap-3 mb-3">
                <div class="w-full">
                    <label class="mb-0 text-gray-400">Learning Area</label>
                    <select class="form-select me-2 pe-3 z-[2] border-0 border-b-2 rounded-none"
                            x-model="learning_area_id" @change="fetchStrands" aria-label>
                        <option value="" selected hidden>Select</option>
                        @foreach($learningAreas as $lA)
                            <option value="{{ $lA->id }}" @selected($lA->id === $subStrand?->strand?->learning_area_id)>
                                {{ $lA->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-full">
                    <label class="mb-0 text-gray-400">Strand</label>
                    <select class="form-select me-2 pe-3 z-[2] border-0 border-b-2 rounded-none"
                            x-model="strand_id" @change="fetchSubStrands" aria-label>
                        <option value="" selected hidden>Select</option>
                        <template x-for="s in strands" :key="s.id">
                            <option :value="s.id" x-text="s.name" :selected="s.selected"></option>
                        </template>
                    </select>
                </div>
                <div class="w-full">
                    <label class="mb-0 text-gray-400">Sub Strand</label>
                    <select class="form-select me-2 pe-3 z-[2] border-0 border-b-2 rounded-none"
                            x-model="form.sub_strand_id" @change="fetchIndicators" aria-label>
                        <option value="" selected hidden>Select</option>
                        <template x-for="s in sub_strands" :key="s.id">
                            <option :value="s.id" x-text="s.name" :selected="s.selected"></option>
                        </template>
                    </select>
                </div>
            </div>
            <div class="flex md:absolute md:top-[100px] items-center">
                <h5 class="mb-5 text-lg font-semibold dark:text-white-light md:mb-0">Indicators</h5>
            </div>
            <div class="relative">
                <div class="mb-5 sm:absolute sm:top-0 sm:mb-0 sm:ltr:right-56 sm:rtl:left-56">
                    <div class="flex items-center">
                        <div class="theme-dropdown relative" x-data="{ columnDropdown: false }"
                             @click.outside="columnDropdown = false">
                            <a
                                href="javascript:;"
                                class="flex items-center rounded-md border border-[#e0e6ed] px-4 py-2 text-sm font-semibold dark:border-[#253b5c] dark:bg-[#1b2e4b] dark:text-white-dark"
                                @click="columnDropdown = ! columnDropdown"
                            >
                                <span class="ltr:mr-1 rtl:ml-1">Columns</span>
                                <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M19 9L12 15L5 9"
                                        stroke="currentColor"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    ></path>
                                </svg>
                            </a>
                            <div
                                class="absolute top-11 z-[10] hidden w-[230px] min-w-[150px] rounded bg-white py-2 text-dark shadow ltr:left-0 rtl:right-0 dark:bg-[#1b2e4b] dark:text-white-light"
                                :class="columnDropdown && '!block'"
                            >
                                <ul class="space-y-2 px-4 font-semibold">
                                    <template x-for="(col,i) in columns" :key="i">
                                        <li>
                                            <div>
                                                <label class="cursor-pointer">
                                                    <input
                                                        type="checkbox"
                                                        class="form-checkbox"
                                                        :id="`chk-${i}`"
                                                        :value="(i)"
                                                        @change="col.hidden=  $event.target.checked,showHideColumns(i,$event.target.checked)"
                                                        :checked="col.hidden"
                                                    />
                                                    <span :for="`chk-${i}`" class="ltr:ml-2 rtl:mr-2"
                                                          x-text="col.name"></span>
                                                </label>
                                            </div>
                                        </li>
                                    </template>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <table x-ref="table"></table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('/js/simple-datatables.js') }}"></script>
    <script src="{{ asset('/vendors/tom-select/tom-select.complete.min.js') }}"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('indicators', (initialOpenState = false) => ({
                update: false,
                loading: false,
                errors: {},
                openModal: initialOpenState,
                learning_area_id: `{{ $subStrand->strand->learning_area_id }}`,
                strand_id: `{{ $subStrand->strand_id }}`,
                indicator_id: null,
                datatable: null,
                indicators: [],
                strands: [],
                sub_strands: [],
                form: {
                    sub_strand_id: `{{ $subStrand->id }}`,
                    name: '',
                    highly_competent: '',
                    competent: '',
                    approaching_competence: '',
                    needs_improvement: '',
                },
                tomLearningArea: null,
                tomStrand: null,
                tomSubStrand: null,
                columns: [
                    {name: 'Name'},
                    {name: 'Highly Competent'},
                    {name: 'Competent'},
                    {name: 'Approaching Competence'},
                    {name: 'Needs Improvement'},
                    {name: 'Actions'},
                ],
                hideCols: [],
                showCols: [0, 1, 2, 3, 4],

                dtOptions: {
                    data: {
                        headings: [],
                        data: [],
                    },
                    sortable: true,
                    searchable: true,
                    perPage: 10,
                    perPageSelect: [10, 20, 30, 50, 100],
                    firstLast: true,
                    firstText:
                        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M13 19L7 12L13 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M16.9998 19L10.9998 12L16.9998 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    lastText:
                        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M11 19L17 12L11 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> <path opacity="0.5" d="M6.99976 19L12.9998 12L6.99976 5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    prevText:
                        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M15 5L9 12L15 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    nextText:
                        '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-4.5 h-4.5 rtl:rotate-180"> <path d="M9 5L15 12L9 19" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/> </svg>',
                    labels: {
                        perPage: '{select}',
                    },
                    layout: {
                        top: '{search}',
                        bottom: '{info}{select}{pager}',
                    },
                },

                initDT(data) {
                    this.dtOptions = {
                        data: {
                            headings: this.columns.map(c => c.name),
                            data,
                        },
                        columns: [
                            {select: 0, sort: 'asc',},
                            {select: [1, 2, 3, 4], cellClass: 'break-normal'},
                            {
                                select: 5,
                                sortable: false,
                                render: data => {
                                    const i = JSON.parse(data)

                                    return `<div class="flex items-center">
                                                <button type="button" class="ltr:mr-2 rtl:ml-2" @click="onEdit(${i})">
                                                    <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.5" d="M20.8487 8.71306C22.3844 7.17735 22.3844 4.68748 20.8487 3.15178C19.313 1.61607 16.8231 1.61607 15.2874 3.15178L14.4004 4.03882C14.4125 4.0755 14.4251 4.11268 14.4382 4.15035C14.7633 5.0875 15.3768 6.31601 16.5308 7.47002C17.6848 8.62403 18.9133 9.23749 19.8505 9.56262C19.888 9.57563 19.925 9.58817 19.9615 9.60026L20.8487 8.71306Z" fill="#1C274C"/>
                                                        <path d="M14.4386 4L14.4004 4.03819C14.4125 4.07487 14.4251 4.11206 14.4382 4.14973C14.7633 5.08687 15.3768 6.31538 16.5308 7.4694C17.6848 8.62341 18.9133 9.23686 19.8505 9.56199C19.8876 9.57489 19.9243 9.58733 19.9606 9.59933L11.4001 18.1598C10.823 18.7369 10.5343 19.0255 10.2162 19.2737C9.84082 19.5665 9.43469 19.8175 9.00498 20.0223C8.6407 20.1959 8.25351 20.3249 7.47918 20.583L3.39584 21.9442C3.01478 22.0712 2.59466 21.972 2.31063 21.688C2.0266 21.4039 1.92743 20.9838 2.05445 20.6028L3.41556 16.5194C3.67368 15.7451 3.80273 15.3579 3.97634 14.9936C4.18114 14.5639 4.43213 14.1578 4.7249 13.7824C4.97307 13.4643 5.26165 13.1757 5.83874 12.5986L14.4386 4Z" fill="#1C274C"/>
                                                    </svg>
                                                </button>
                                                <button type="button" @click="onDelete(${1})">
                                                    <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path opacity="0.5" d="M11.3456 22H12.1544C14.9371 22 16.3285 22 17.2331 21.0936C18.1378 20.1873 18.2303 18.7005 18.4154 15.727L18.6821 11.4425C18.7826 9.82908 18.8328 9.02238 18.3789 8.51119C17.9251 8 17.1587 8 15.626 8H7.87405C6.34127 8 5.57488 8 5.12105 8.51119C4.66722 9.02238 4.71744 9.82908 4.81788 11.4425L5.08459 15.727C5.2697 18.7005 5.36225 20.1873 6.26689 21.0936C7.17153 22 8.56289 22 11.3456 22Z" fill="#1C274C"/>
                                                        <path d="M2.75 6.16667C2.75 5.70644 3.09538 5.33335 3.52143 5.33335L6.18567 5.3329C6.71502 5.31841 7.18202 4.95482 7.36214 4.41691C7.36688 4.40277 7.37232 4.38532 7.39185 4.32203L7.50665 3.94993C7.5769 3.72179 7.6381 3.52303 7.72375 3.34536C8.06209 2.64349 8.68808 2.1561 9.41147 2.03132C9.59457 1.99973 9.78848 1.99987 10.0111 2.00002H13.4891C13.7117 1.99987 13.9056 1.99973 14.0887 2.03132C14.8121 2.1561 15.4381 2.64349 15.7764 3.34536C15.8621 3.52303 15.9233 3.72179 15.9935 3.94993L16.1083 4.32203C16.1279 4.38532 16.1333 4.40277 16.138 4.41691C16.3182 4.95482 16.8778 5.31886 17.4071 5.33335H19.9786C20.4046 5.33335 20.75 5.70644 20.75 6.16667C20.75 6.62691 20.4046 7 19.9786 7H3.52143C3.09538 7 2.75 6.62691 2.75 6.16667Z" fill="#1C274C"/>
                                                    </svg>
                                                </button>
                                            </div>`;
                                },
                            },
                        ],
                    }

                    this.datatable.init(this.dtOptions)
                },

                init() {
                    const tomOpts = {
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    }
                    this.tomLearningArea = new TomSelect(this.$refs.tomLearningAreaEl, tomOpts);
                    this.tomStrand = new TomSelect(this.$refs.tomStrandEl, tomOpts);
                    this.tomSubStrand = new TomSelect(this.$refs.tomSubStrandEl, tomOpts);

                    this.datatable = new simpleDatatables.DataTable(this.$refs.table, this.dtOptions);

                    let cols = this.datatable.columns();

                    cols.hide(this.hideCols);
                    cols.show(this.showCols);

                    this.fetchStrands()
                    this.fetchSubStrands()
                    this.fetchIndicators()
                },

                showHideColumns(col, value) {
                    if (value) {
                        this.showCols.push(col);
                        this.hideCols = this.hideCols.filter((d) => d !== col);
                    } else {
                        this.hideCols.push(col);
                        this.showCols = this.showCols.filter((d) => d !== col);
                    }

                    let headers = this.datatable.columns();

                    headers.hide(this.hideCols);
                    headers.show(this.showCols);
                },

                fetchIndicators() {
                    if (this.form.sub_strand_id) {
                        axios.get(`/api/sub-strands/${this.form.sub_strand_id}/indicators`).then(({data}) => {
                            if (data.status) {
                                this.datatable.destroy()
                                this.initDT(data.indicators.map(i => [
                                    i.name,
                                    i.highly_competent,
                                    i.competent,
                                    i.approaching_competence,
                                    i.needs_improvement,
                                    JSON.stringify(i)
                                ]))
                            }
                        })
                    }
                },

                fetchStrands() {
                    if (this.learning_area_id) {
                        axios.get(`/api/learning-areas/${this.learning_area_id}/strands`).then(({data}) => {
                            if (data.status) {
                                this.tomStrand.clear()
                                this.tomStrand.clearOptions()

                                this.strands = data.strands.map(s => {
                                    this.tomStrand.addOption({value: s.id, text: s.name})

                                    return {
                                        ...s,
                                        selected: s.id === Number(this.strand_id)
                                    }
                                })

                                if (this.strand_id) this.tomStrand.addItem(this.strand_id, true)
                            }
                        })
                    }
                },

                fetchSubStrands() {
                    if (this.learning_area_id && this.strand_id) {
                        axios.get(`/api/strands/${this.strand_id}/sub-strands`).then(({data}) => {
                            if (data.status) {
                                this.tomSubStrand.clear()
                                this.tomSubStrand.clearOptions()

                                this.sub_strands = data.sub_strands.map(s => {
                                    this.tomSubStrand.addOption({value: s.id, text: s.name})

                                    return {
                                        ...s,
                                        selected: s.id === Number(this.form.sub_strand_id)
                                    }
                                })

                                if (this.form.sub_strand_id) this.tomSubStrand.addItem(this.form.sub_strand_id, true)
                            }
                        })
                    }
                },

                saveIndicator() {
                    this.loading = true

                    axios[this.update ? 'put' : 'post'](`/api/indicators/${this.indicator_id || ''}`, this.form)
                        .then(({data}) => {
                            if (data.status) {
                                this.showMessage(data.msg)

                                this.fetchSubStrands()
                            } else {
                                this.showMessage(data.msg, 'error')
                            }

                            this.loading = false
                            this.openModal = false
                        }).catch(err => {
                        console.error(err)
                        if (err?.response?.data?.errors) this.errors = err.response.data.errors

                        this.loading = false

                        this.showMessage(err.message, 'error')
                    })
                },

                onDelete(subStrand) {
                    window.Swal.fire({
                        title: 'Are you sure?',
                        text: `You won't be able to revert this!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, delete ${subStrand.name}!`,
                    }).then(res => res.isConfirmed && axios.delete(`/api/sub-strands/${subStrand.id}`).then(({data}) => {
                        if (data.status) {
                            this.showMessage(data.msg)

                            this.fetchSubStrands()
                        } else {
                            this.showMessage(data.msg, 'error')
                        }
                    }));
                },

                toggleModal() {
                    this.openModal = !this.openModal;
                },
            }));
        })
    </script>
@endpush
