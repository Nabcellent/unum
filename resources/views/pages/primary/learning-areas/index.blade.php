@extends('layouts.app')
@section('title', 'Learning Areas')
@section('content')

    <div x-data="learningAreas" class="xl:px-40 lg:px-32">
        <button type="button" class="btn btn-warning mb-3 text-end ml-auto" @click="onCreate">
            Create Learning Area
        </button>

        <!-- modal -->
        <div class="fixed inset-0 bg-[black]/60 z-[999]  hidden" :class="openModal && '!block'">
            <div class="flex items-start justify-center min-h-screen px-4" @click.self="openModal = false">
                <div x-show="openModal" x-transition x-transition.duration.300
                     class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg">
                            <span x-text="update ? 'Edit':'Create'"></span> Learning Area
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
                        @include('components.error-alert')

                        <div class="dark:text-white-dark/70 text-base font-medium text-[#1f2937]">
                            <div class="grid grid-cols-1 gap-6">
                                <select x-ref="tomSelectGradesEl" x-model="form.classes" aria-label multiple>
                                    <option value="" selected>Select Grades</option>
                                    @foreach($grades as $grade)
                                        <option value="{{ $grade->name }}">{{ $grade->name }}</option>
                                    @endforeach
                                </select>

                                <input type="text" placeholder="Enter learning area name" class="form-input" required
                                       aria-label
                                       x-model="form.name"/>
                            </div>
                        </div>
                        <div class="flex justify-end items-center mt-8">
                            <button type="button" class="btn btn-outline-danger" @click="toggleModal">Discard</button>
                            <button type="submit" class="btn btn-primary ltr:ml-4 rtl:mr-4"
                                    :disabled="!form.name || loading" @click="saveLearningArea">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel">
            <h5 class="text-lg font-semibold dark:text-white-light">Learning Areas</h5>

            <div class="my-5">
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>No. of Strands</th>
                            <th class="!text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="l in learningAreas" :key="l.id">
                            <tr>
                                <td x-text="l.name" class="whitespace-nowrap"></td>
                                <td class="whitespace-nowrap">
                                    <a :href="`/dashboard/strands?learning-area-id=${l.id}`"
                                       x-text="l.strands_count"></a>
                                </td>
                                <td class="flex items-center justify-between">
                                    <div x-data="{ dropdownOpen: false }" class="relative">
                                        <button @click="dropdownOpen=true"
                                                class="inline-flex items-center justify-center py-1 pl-3 pr-12 text-sm font-medium transition-colors bg-white border rounded-md text-neutral-700 hover:bg-neutral-100 active:bg-white focus:bg-white focus:outline-none disabled:opacity-50 disabled:pointer-events-none">
                                            <svg class="me-2 h-5 w-5" width="24" height="24" viewBox="0 0 24 24"
                                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <g opacity="0.3">
                                                    <path
                                                        d="M4.66602 9C3.73413 9 3.26819 9 2.90065 8.84776C2.41059 8.64477 2.02124 8.25542 1.81826 7.76537C1.66602 7.39782 1.66602 6.93188 1.66602 6C1.66602 5.06812 1.66602 4.60217 1.81826 4.23463C2.02124 3.74458 2.41059 3.35523 2.90065 3.15224C3.26819 3 3.73413 3 4.66602 3H11.934C11.8905 3.07519 11.8518 3.15353 11.8183 3.23463C11.666 3.60217 11.666 4.06812 11.666 5L11.666 9H4.66602Z"
                                                        fill="#1C274C"/>
                                                    <path
                                                        d="M21.666 6C21.666 6.93188 21.666 7.39782 21.5138 7.76537C21.3108 8.25542 20.9214 8.64477 20.4314 8.84776C20.0638 9 19.5979 9 18.666 9H17.666V5C17.666 4.06812 17.666 3.60217 17.5138 3.23463C17.4802 3.15353 17.4415 3.07519 17.3981 3H18.666C19.5979 3 20.0638 3 20.4314 3.15224C20.9214 3.35523 21.3108 3.74458 21.5138 4.23463C21.666 4.60217 21.666 5.06812 21.666 6Z"
                                                        fill="#1C274C"/>
                                                </g>
                                                <g opacity="0.7">
                                                    <path
                                                        d="M17.5138 20.7654C17.666 20.3978 17.666 19.9319 17.666 19V15H18.666C19.5979 15 20.0638 15 20.4314 15.1522C20.9214 15.3552 21.3108 15.7446 21.5138 16.2346C21.666 16.6022 21.666 17.0681 21.666 18C21.666 18.9319 21.666 19.3978 21.5138 19.7654C21.3108 20.2554 20.9214 20.6448 20.4314 20.8478C20.0638 21 19.5979 21 18.666 21H17.3981C17.4415 20.9248 17.4802 20.8465 17.5138 20.7654Z"
                                                        fill="#1C274C"/>
                                                    <path
                                                        d="M11.934 21H4.66602C3.73413 21 3.26819 21 2.90065 20.8478C2.41059 20.6448 2.02124 20.2554 1.81826 19.7654C1.66602 19.3978 1.66602 18.9319 1.66602 18C1.66602 17.0681 1.66602 16.6022 1.81826 16.2346C2.02124 15.7446 2.41059 15.3552 2.90065 15.1522C3.26819 15 3.73413 15 4.66602 15H11.666V19C11.666 19.9319 11.666 20.3978 11.8183 20.7654C11.8518 20.8465 11.8905 20.9248 11.934 21Z"
                                                        fill="#1C274C"/>
                                                </g>
                                                <g opacity="0.5">
                                                    <path
                                                        d="M17.666 9H18.666C19.5979 9 20.0638 9 20.4314 9.15224C20.9214 9.35523 21.3108 9.74458 21.5138 10.2346C21.666 10.6022 21.666 11.0681 21.666 12C21.666 12.9319 21.666 13.3978 21.5138 13.7654C21.3108 14.2554 20.9214 14.6448 20.4314 14.8478C20.0638 15 19.5979 15 18.666 15H17.666V9Z"
                                                        fill="#1C274C"/>
                                                    <path
                                                        d="M11.666 9V15H4.66602C3.73413 15 3.26819 15 2.90065 14.8478C2.41059 14.6448 2.02124 14.2554 1.81826 13.7654C1.66602 13.3978 1.66602 12.9319 1.66602 12C1.66602 11.0681 1.66602 10.6022 1.81826 10.2346C2.02124 9.74458 2.41059 9.35523 2.90065 9.15224C3.26819 9 3.73413 9 4.66602 9H11.666Z"
                                                        fill="#1C274C"/>
                                                </g>
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                      d="M17.5138 3.23463C17.666 3.60218 17.666 4.06812 17.666 5L17.666 19C17.666 19.9319 17.666 20.3978 17.5138 20.7654C17.4802 20.8465 17.4415 20.9248 17.3981 21C17.1792 21.3792 16.8403 21.6784 16.4314 21.8478C16.0638 22 15.5979 22 14.666 22C13.7341 22 13.2682 22 12.9006 21.8478C12.4917 21.6784 12.1529 21.3792 11.934 21C11.8905 20.9248 11.8518 20.8465 11.8183 20.7654C11.666 20.3978 11.666 19.9319 11.666 19V5C11.666 4.06812 11.666 3.60218 11.8183 3.23463C11.8518 3.15353 11.8905 3.07519 11.934 3C12.1529 2.62082 12.4917 2.32164 12.9006 2.15224C13.2682 2 13.7341 2 14.666 2C15.5979 2 16.0638 2 16.4314 2.15224C16.8403 2.32164 17.1792 2.62082 17.3981 3C17.4415 3.07519 17.4802 3.15353 17.5138 3.23463ZM15.416 11C15.416 10.5858 15.0802 10.25 14.666 10.25C14.2518 10.25 13.916 10.5858 13.916 11L13.916 13C13.916 13.4142 14.2518 13.75 14.666 13.75C15.0802 13.75 15.416 13.4142 15.416 13L15.416 11Z"
                                                      fill="#1C274C"/>
                                            </svg>
                                            <span x-text="`${l.grades.length} Classes`"></span>
                                            <svg class="absolute right-0 w-5 h-5 mr-3"
                                                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                      d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9"/>
                                            </svg>
                                        </button>
                                        <div x-show="dropdownOpen"
                                             @click.away="dropdownOpen=false"
                                             x-transition:enter="ease-out duration-200"
                                             x-transition:enter-start="-translate-y-2"
                                             x-transition:enter-end="translate-y-0"
                                             class="absolute top-0 z-50 w-56 mt-12 -translate-x-1/2 left-1/2"
                                             x-cloak>
                                            <div
                                                class="p-1 mt-1 bg-white border rounded-md shadow-md border-neutral-200/70 text-neutral-700">
                                                <template x-for="g in l.grades">
                                                    <div
                                                        class="relative flex cursor-default select-none hover:bg-neutral-100 items-center rounded px-2 py-1.5 text-sm outline-none transition-colors data-[disabled]:pointer-events-none data-[disabled]:opacity-50">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                             viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                             stroke-width="2" stroke-linecap="round"
                                                             stroke-linejoin="round" class="w-4 h-4 mr-2">
                                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                                            <circle cx="9" cy="7" r="4"></circle>
                                                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                        </svg>
                                                        <span x-text="g.full_name"></span>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" x-tooltip="Edit" @click="onEdit(l)">
                                        <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                  d="M20.8487 8.71306C22.3844 7.17735 22.3844 4.68748 20.8487 3.15178C19.313 1.61607 16.8231 1.61607 15.2874 3.15178L14.4004 4.03882C14.4125 4.0755 14.4251 4.11268 14.4382 4.15035C14.7633 5.0875 15.3768 6.31601 16.5308 7.47002C17.6848 8.62403 18.9133 9.23749 19.8505 9.56262C19.888 9.57563 19.925 9.58817 19.9615 9.60026L20.8487 8.71306Z"
                                                  fill="#1C274C"/>
                                            <path
                                                d="M14.4386 4L14.4004 4.03819C14.4125 4.07487 14.4251 4.11206 14.4382 4.14973C14.7633 5.08687 15.3768 6.31538 16.5308 7.4694C17.6848 8.62341 18.9133 9.23686 19.8505 9.56199C19.8876 9.57489 19.9243 9.58733 19.9606 9.59933L11.4001 18.1598C10.823 18.7369 10.5343 19.0255 10.2162 19.2737C9.84082 19.5665 9.43469 19.8175 9.00498 20.0223C8.6407 20.1959 8.25351 20.3249 7.47918 20.583L3.39584 21.9442C3.01478 22.0712 2.59466 21.972 2.31063 21.688C2.0266 21.4039 1.92743 20.9838 2.05445 20.6028L3.41556 16.5194C3.67368 15.7451 3.80273 15.3579 3.97634 14.9936C4.18114 14.5639 4.43213 14.1578 4.7249 13.7824C4.97307 13.4643 5.26165 13.1757 5.83874 12.5986L14.4386 4Z"
                                                fill="#1C274C"/>
                                        </svg>
                                    </button>
                                    <button type="button" x-tooltip="Delete" @click="onDelete(l)">
                                        <svg class="h-5 w-5" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                  d="M11.3456 22H12.1544C14.9371 22 16.3285 22 17.2331 21.0936C18.1378 20.1873 18.2303 18.7005 18.4154 15.727L18.6821 11.4425C18.7826 9.82908 18.8328 9.02238 18.3789 8.51119C17.9251 8 17.1587 8 15.626 8H7.87405C6.34127 8 5.57488 8 5.12105 8.51119C4.66722 9.02238 4.71744 9.82908 4.81788 11.4425L5.08459 15.727C5.2697 18.7005 5.36225 20.1873 6.26689 21.0936C7.17153 22 8.56289 22 11.3456 22Z"
                                                  fill="#1C274C"/>
                                            <path
                                                d="M2.75 6.16667C2.75 5.70644 3.09538 5.33335 3.52143 5.33335L6.18567 5.3329C6.71502 5.31841 7.18202 4.95482 7.36214 4.41691C7.36688 4.40277 7.37232 4.38532 7.39185 4.32203L7.50665 3.94993C7.5769 3.72179 7.6381 3.52303 7.72375 3.34536C8.06209 2.64349 8.68808 2.1561 9.41147 2.03132C9.59457 1.99973 9.78848 1.99987 10.0111 2.00002H13.4891C13.7117 1.99987 13.9056 1.99973 14.0887 2.03132C14.8121 2.1561 15.4381 2.64349 15.7764 3.34536C15.8621 3.52303 15.9233 3.72179 15.9935 3.94993L16.1083 4.32203C16.1279 4.38532 16.1333 4.40277 16.138 4.41691C16.3182 4.95482 16.8778 5.31886 17.4071 5.33335H19.9786C20.4046 5.33335 20.75 5.70644 20.75 6.16667C20.75 6.62691 20.4046 7 19.9786 7H3.52143C3.09538 7 2.75 6.62691 2.75 6.16667Z"
                                                fill="#1C274C"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        </template>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data("learningAreas", (initialOpenState = false) => ({
                update: false,
                loading: false,
                errors: {},
                learningAreas: [],
                learningAreaId: null,
                openModal: initialOpenState,
                form: {
                    classes: [],
                    name: ''
                },
                tomSelectGradesInstance: null,

                init() {
                    this.tomSelectGradesInstance = new TomSelect(this.$refs.tomSelectGradesEl, {
                        plugins: {
                            remove_button: {
                                title: 'Remove this item',
                            }
                        },
                        sortField: {
                            field: "text",
                            direction: "asc"
                        }
                    });

                    this.fetchLearningAreas()
                },
                fetchLearningAreas() {
                    axios.get('/api/learning-areas').then(({data: {data, status}}) => {
                        if (status) this.learningAreas = data
                    })
                },
                onCreate() {
                    this.update = false
                    this.openModal = true
                    this.learningAreaId = null
                    this.form = {
                        classes: [],
                        name: ''
                    }

                    this.tomSelectGradesInstance.clear()
                },
                onEdit(learningArea) {
                    this.update = true
                    this.openModal = true
                    this.learningAreaId = learningArea.id
                    this.form = {
                        classes: learningArea.grades.map(s => {
                            this.tomSelectGradesInstance.addItem(s.name, true)

                            return s.name
                        }),
                        name: learningArea.name
                    }

                    window.scrollTo({top: 0});
                },
                onDelete(learningArea) {
                    window.Swal.fire({
                        title: 'Are you sure?',
                        text: `You won't be able to revert this!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, delete ${learningArea.name}!`,
                    }).then(res => res.isConfirmed && axios.delete(`/api/learning-areas/${learningArea.id}`).then(({data}) => {
                        if (data.status) {
                            this.showMessage(data.msg)

                            this.fetchLearningAreas()
                        } else {
                            this.showMessage(data.msg, 'error')
                        }
                    }));
                },
                saveLearningArea() {
                    this.loading = true

                    axios[this.update ? 'put' : 'post'](`/api/learning-areas/${this.learningAreaId || ''}`, this.form)
                        .then(({data: {status, msg}}) => {
                            if (status) {
                                this.showMessage(msg)

                                this.learningAreaId = null
                                this.form = {
                                    classes: [],
                                    name: ''
                                }

                                this.tomSelectGradesInstance.clear()

                                this.fetchLearningAreas()

                                this.openModal = false
                            } else {
                                this.showMessage(msg, 'error')
                            }

                            this.loading = false
                        }).catch(err => {
                        console.error(err)
                        this.loading = false

                        if (err?.response?.data?.errors) {
                            this.errors = err.response.data.errors
                        } else {
                            this.showMessage(err.message, 'error')
                        }
                    })
                },
                toggleModal() {
                    this.openModal = !this.openModal;
                },
            }));
        })
    </script>
@endpush
