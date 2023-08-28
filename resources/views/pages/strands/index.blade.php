@extends('layouts.app')
@section('title', 'Strands')
@section('content')

    <div x-data="strands" class="xl:px-40 lg:px-32">
        <div class="panel mb-3">
            <div class="flex justify-between items-start">
                <h5 class="text-lg font-semibold dark:text-white-light mb-5">
                    <span x-text="update ? 'Edit':'Create'"></span> Strand
                </h5>
                <span class="cursor-pointer" x-show="update" x-tooltip="Create Strand" @click="onCreate">
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
                <select x-ref="tomSelectGradesEl" x-model="form.learning_area_id" aria-label>
                    <option value="" selected>Select Learning Area</option>
                    @foreach($learningAreas as $lA)
                        <option value="{{ $lA->id }}">{{ $lA->name }}</option>
                    @endforeach
                </select>

                <input type="text" placeholder="Enter strand name" class="form-input" required aria-label
                       x-model="form.name"/>
            </div>

            <div class="flex justify-end">
                <button type="submit" class="btn btn-primary mt-6" @click="saveStrand"
                        :disabled="!form.name || loading">Submit
                </button>
            </div>
        </div>

        <div class="panel">
            <div class="flex items-center">
                <select class="form-select me-2 pe-3 z-[2] border-0 border-b-2 rounded-none !w-auto"
                        x-model="learning_area_id"
                        @change="fetchStrands" aria-label>
                    <option value="" selected hidden>Select</option>
                    @foreach($learningAreas as $lA)
                        <option value="{{ $lA->id }}" @selected($lA->id === $learningAreaId)>{{ $lA->name }}</option>
                    @endforeach
                </select>
                <h5 class="mb-5 text-lg font-semibold dark:text-white-light md:mb-0">Strands</h5>
            </div>

            <div class="my-5">
                <div class="table-responsive">
                    <table class="table-hover">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>No. of Sub Strands</th>
                            <th class="!text-center">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <template x-for="s in strands" :key="s.id">
                            <tr>
                                <td x-text="s.name" class="whitespace-nowrap"></td>
                                <td class="whitespace-nowrap">
                                    <a :href="`/dashboard/sub-strands?strand-id=${s.id}`" x-text="s.sub_strands_count"></a>
                                </td>
                                <td class="flex items-center justify-between">
                                    <button type="button" x-tooltip="Edit" @click="onEdit(s)">
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
                                    <button type="button" x-tooltip="Delete" @click="onDelete(s)">
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
            Alpine.data("strands", () => ({
                update: false,
                loading: false,
                learning_area_id: {{ $learningAreaId }},
                strands: [],
                strandId: null,
                form: {
                    learning_area_id: '',
                    name: ''
                },
                tomSelectLearningArea: null,

                init() {
                    this.tomSelectLearningArea = new TomSelect(this.$refs.tomSelectGradesEl, {
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

                    this.fetchStrands()
                },
                fetchStrands() {
                    if(this.learning_area_id) {
                        axios.get(`/api/learning-areas/${this.learning_area_id}/strands`).then(({data}) => {
                            if (data.status) this.strands = data.strands
                        })
                    }
                },
                onCreate() {
                    this.update = false
                    this.strandId = null
                    this.form = {
                        learning_area_id: '',
                        name: ''
                    }

                    this.tomSelectLearningArea.clear()
                },
                onEdit(strand) {
                    this.update = true
                    this.strandId = strand.id
                    this.form = {
                        learning_area_id: strand.learning_area_id,
                        name: strand.name
                    }

                    this.tomSelectLearningArea.addItem(strand.learning_area_id, true)

                    window.scrollTo({top: 0});
                },
                onDelete(strand) {
                    window.Swal.fire({
                        title: 'Are you sure?',
                        text: `You won't be able to revert this!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: `Yes, delete ${strand.name}!`,
                    }).then(res => res.isConfirmed && axios.delete(`/api/strands/${strand.id}`).then(({data}) => {
                        if (data.status) {
                            this.showMessage(data.msg)

                            this.fetchStrands()
                        } else {
                            this.showMessage(data.msg, 'error')
                        }
                    }));
                },
                saveStrand() {
                    this.loading = true

                    axios[this.update ? 'put' : 'post'](`/api/strands/${this.strandId || ''}`, this.form)
                        .then(({data}) => {
                            if (data.status) {
                                this.showMessage(data.msg)

                                this.fetchStrands()
                            } else {
                                this.showMessage(data.msg, 'error')
                            }

                            this.loading = false
                        }).catch(err => {
                        console.error(err)

                        this.loading = false

                        this.showMessage(err.message, 'error')
                    })
                }
            }));
        })
    </script>
@endpush
