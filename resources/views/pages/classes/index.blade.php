@extends('layouts.app')
@section('title', 'Classes')
@section('content')

    <div x-data="classes" class="xl:px-32 lg:px-20">
        <div class="panel mb-3">
            <div class="flex justify-between items-start">
                <h5 class="text-lg font-semibold dark:text-white-light mb-5">
                    <span x-text="update ? 'Edit':'Create'"></span> Class
                </h5>
                <span class="cursor-pointer" x-show="update" x-tooltip="Create" @click="onCreate">
                    <svg class="h-7 w-7" width="24" height="24" viewBox="0 0 24 24" fill="none"
                         xmlns="http://www.w3.org/2000/svg">
                        <path opacity="0.5"
                              d="M22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12Z"
                              fill="#1C274C"/>
                        <path
                            d="M12.75 9C12.75 8.58579 12.4142 8.25 12 8.25C11.5858 8.25 11.25 8.58579 11.25 9L11.25 11.25H9C8.58579 11.25 8.25 11.5858 8.25 12C8.25 12.4142 8.58579 12.75 9 12.75H11.25V15C11.25 15.4142 11.5858 15.75 12 15.75C12.4142 15.75 12.75 15.4142 12.75 15L12.75 12.75H15C15.4142 12.75 15.75 12.4142 15.75 12C15.75 11.5858 15.4142 11.25 15 11.25H12.75V9Z"
                            fill="#1C274C"/>
                    </svg>
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <select class="form-select" x-model="form.stream_id" aria-label>
                    <option value="" selected>Select Stream</option>
                    @foreach($streams as $stream)
                        <option value="{{ $stream->id }}">{{ $stream->name }}</option>
                    @endforeach
                </select>

                <input type="text" placeholder="Enter class name" class="form-input" required aria-label
                       x-model="form.name"/>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary mt-6">Submit</button>
            </div>
        </div>

        <div class="panel">
            <h5 class="text-lg font-semibold dark:text-white-light">Classes</h5>

            <div class="my-5">
                <div class="table-responsive">
                    <table>
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Stream</th>
                            <th>Level</th>
                            <th>No. of Students</th>
                            <th>No. of Subjects/Learning Areas</th>
                            <th class="!text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="g in grades" :key="g.id">
                            <tr>
                                <td x-text="g.name" class="whitespace-nowrap"></td>
                                <td x-text="g.stream?.name ?? '-'" class="whitespace-nowrap"></td>
                                <td x-text="g.level ?? '-'" class="whitespace-nowrap"></td>
                                <td x-text="g.students_count" class="whitespace-nowrap"></td>
                                <td x-text="g.level==='primary'?g.learning_areas_count:g.subjects_count"
                                    class="whitespace-nowrap"></td>
                                <td class="flex items-center justify-evenly">
                                    <button type="button" x-tooltip="Assign Subjects/Learning Areas"
                                            @click="onAssignUnits(g)">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                             xmlns="http://www.w3.org/2000/svg">
                                            <path opacity="0.5"
                                                  d="M4.72718 2.73332C5.03258 2.42535 5.46135 2.22456 6.27103 2.11478C7.10452 2.00177 8.2092 2 9.7931 2H14.2069C15.7908 2 16.8955 2.00177 17.729 2.11478C18.5387 2.22456 18.9674 2.42535 19.2728 2.73332C19.5782 3.0413 19.7773 3.47368 19.8862 4.2902C19.9982 5.13073 20 6.24474 20 7.84202L20 18H7.42598C6.34236 18 5.96352 18.0057 5.67321 18.0681C5.15982 18.1785 4.71351 18.4151 4.38811 18.7347C4.27837 18.8425 4.22351 18.8964 4.09696 19.2397C4.02435 19.4367 4 19.5687 4 19.7003V7.84202C4 6.24474 4.00176 5.13073 4.11382 4.2902C4.22268 3.47368 4.42179 3.0413 4.72718 2.73332Z"
                                                  fill="#1C274D"/>
                                            <path
                                                d="M20 18H7.42598C6.34236 18 5.96352 18.0057 5.67321 18.0681C5.15982 18.1785 4.71351 18.4151 4.38811 18.7347C4.27837 18.8425 4.22351 18.8964 4.09696 19.2397C3.97041 19.5831 3.99045 19.7288 4.03053 20.02C4.03761 20.0714 4.04522 20.1216 4.05343 20.1706C4.16271 20.8228 4.36259 21.1682 4.66916 21.4142C4.97573 21.6602 5.40616 21.8206 6.21896 21.9083C7.05566 21.9986 8.1646 22 9.75461 22H14.1854C15.7754 22 16.8844 21.9986 17.7211 21.9083C18.5339 21.8206 18.9643 21.6602 19.2709 21.4142C19.4705 21.254 19.6249 21.0517 19.7385 20.75H8C7.58579 20.75 7.25 20.4142 7.25 20C7.25 19.5858 7.58579 19.25 8 19.25H19.9754C19.9926 18.8868 19.9982 18.4741 20 18Z"
                                                fill="#1C274D"/>
                                            <path
                                                d="M7.25 7C7.25 6.58579 7.58579 6.25 8 6.25H16C16.4142 6.25 16.75 6.58579 16.75 7C16.75 7.41421 16.4142 7.75 16 7.75H8C7.58579 7.75 7.25 7.41421 7.25 7Z"
                                                fill="#1C274D"/>
                                            <path
                                                d="M8 9.75C7.58579 9.75 7.25 10.0858 7.25 10.5C7.25 10.9142 7.58579 11.25 8 11.25H13C13.4142 11.25 13.75 10.9142 13.75 10.5C13.75 10.0858 13.4142 9.75 13 9.75H8Z"
                                                fill="#1C274D"/>
                                        </svg>
                                    </button>
                                    <button type="button" x-tooltip="Edit" @click="onEdit(g)">
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
                                    <button type="button" x-tooltip="Delete">
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

        <div class="fixed inset-0 bg-[black]/60 z-[999] hidden overflow-y-auto" :class="showModal && '!block'">
            <div class="flex items-center justify-center min-h-screen px-4" @click.self="showModal = false">
                <div x-show="showModal" x-transition x-transition.duration.300
                     class="panel border-0 p-0 rounded-lg overflow-hidden w-full max-w-lg my-8">
                    <div class="flex bg-[#fbfbfb] dark:bg-[#121c2c] items-center justify-between px-5 py-3">
                        <h5 class="font-bold text-lg"
                            x-text="`Assign ${grade?.level === 'primary' ? 'Learning Areas' : 'Subjects'} to ${grade?.name}`"></h5>
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

                        <div
                            class="flex flex-col rounded-md border border-[#e0e6ed] dark:border-[#1b2e4b] max-h-72 overflow-y-auto">
                            <template x-for="(u, i) in units" :key="u.id">
                                <div
                                    class="flex space-x-4 rtl:space-x-reverse border-b border-[#e0e6ed] dark:border-[#1b2e4b] px-4 py-2.5 hover:bg-[#eee] dark:hover:bg-[#eee]/10">
                                    <input :id="`unit-check-${i}`" type="checkbox" class="form-checkbox" :value="u.id"
                                           x-model="assignUnitsForm[grade?.level==='primary'?'learning_areas':'subjects']"/>
                                    <label :for="`unit-check-${i}`" class="mb-0 cursor-pointer" x-text="u.name"></label>
                                </div>
                            </template>
                        </div>
                        <div class="flex justify-end items-center mt-8">
                            <button type="button" class="btn btn-outline-danger" @click="toggleModal">Discard</button>
                            <button type="button" class="btn btn-primary ltr:ml-4 rtl:mr-4" @click="assignUnits">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data("classes", () => ({
                showModal: false,
                update: false,
                errors: {},
                form: {
                    stream_id: null,
                    name: ''
                },
                assignUnitsForm: {
                    subjects: [],
                    learning_areas: []
                },
                grades: [],
                units: [],
                grade: null,

                init() {
                    axios.get('/api/grades').then(({data: {data, status}}) => {
                        if (status) {
                            this.grades = data
                        }
                    })
                },

                onAssignUnits(g) {
                    this.grade = g

                    axios.get(`/api/grades/${g.id}/${g.level === 'primary' ? 'learning-areas' : 'subjects'}`)
                        .then(({data: {data, status}}) => {
                            if (status) {
                                if(data) {
                                    this.assignUnitsForm[g.level === 'primary' ? 'learning_areas' : 'subjects'] = data.map(d => d.id)
                                }

                                axios.get(`/api/${g.level === 'primary' ? 'learning-areas' : 'subjects'}`)
                                    .then(({data: {data, status}}) => {
                                        if (status) this.units = data
                                    })
                            }
                        })

                    this.toggleModal()
                },

                assignUnits() {
                    if (this.grade.id) {
                        axios.put(`/api/grades/${this.grade.id}/${this.grade.level === 'primary' ? 'learning-areas' : 'subjects'}`, this.assignUnitsForm)
                            .then(({data: {msg, status}}) => {
                                if (status) {
                                    this.showMessage(msg)

                                    this.showModal = false

                                    this.init()
                                }
                            })
                            .catch(err => {
                                if (err?.response?.data?.errors) this.errors = err.response.data.errors
                            })
                    }
                },

                onEdit(grade) {
                    this.update = true
                    this.form = grade
                },

                onCreate() {
                    this.update = false
                    this.form = {
                        stream_id: null,
                        name: ''
                    }
                },

                toggleModal() {
                    this.showModal = !this.showModal;
                },
            }));
        })
    </script>
@endpush
