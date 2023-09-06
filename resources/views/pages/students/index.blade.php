@extends('layouts.app')
@section('title', 'Students')
@section('content')

    <div x-data="students">
        <div class="panel">
            <div class="flex md:absolute md:top-[25px] items-center">
                <select class="form-select me-2 pe-3 z-[2] border-0 border-b-2 rounded-none" x-model="grade_id"
                        @change="fetchStudents" aria-label>
                    <option value="" selected hidden>Select Grade</option>
                    @foreach($grades as $grade)
                        <option value="{{ $grade->id }}">{{ $grade->full_name }}</option>
                    @endforeach
                </select>
                <h5 class="mb-5 text-lg font-semibold dark:text-white-light md:mb-0">Students</h5>
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
                <table x-ref="table" class="whitespace-nowrap"></table>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="{{ asset('/js/simple-datatables.js') }}"></script>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('students', () => ({
                grade_id: null,
                datatable: null,
                students: [],
                columns: [
                    {name: 'Class No.'},
                    {name: 'Adm No.'},
                    {name: 'Name'},
                    {name: 'Date of Birth'},
                    {name: 'Created'}
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
                        data:{
                            headings: this.columns.map(c => c.name),
                            data,
                        },
                        columns: [
                            {
                                select: 0,
                                sort: 'asc',
                            },
                            {
                                select: 4,
                                render: data => this.formatDate(data)
                            },
                        ],
                    }

                    this.datatable.init(this.dtOptions)
                },

                init() {
                    this.datatable = new simpleDatatables.DataTable(this.$refs.table, this.dtOptions);

                    let cols = this.datatable.columns();

                    cols.hide(this.hideCols);
                    cols.show(this.showCols);
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

                fetchStudents() {
                    if (this.grade_id) {
                        axios.get(`/api/students/${this.grade_id}`).then(({data:{status, data}}) => {
                            if (status) {
                                this.datatable.destroy()
                                this.initDT(data.map(s => [s.class_no, s.admission_no, s.name, s.dob, s.created_at]))
                            }
                        })
                    }
                },

                formatDate(date) {
                    if (date) {
                        const dt = new Date(date);
                        const month = dt.getMonth() + 1 < 10 ? '0' + (dt.getMonth() + 1) : dt.getMonth() + 1;
                        const day = dt.getDate() < 10 ? '0' + dt.getDate() : dt.getDate();

                        return day + '/' + month + '/' + dt.getFullYear();
                    }

                    return '-';
                },
            }));
        })
    </script>
@endpush
