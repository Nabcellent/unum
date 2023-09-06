@push('links')
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
@endpush

<div x-data="term_settings">
    <div class="mb-5 rounded-md border border-[#ebedf2] bg-white p-4 dark:border-[#191e3a] dark:bg-[#0e1726]">
        <h6 class="mb-5 text-lg font-bold">General Information</h6>
        <div class="grid flex-1 grid-cols-1 gap-5 sm:grid-cols-2 md:grid-cols-3">
            <div>
                <label for="profession">Current Term</label>
                <input id="profession" type="number" placeholder="3" class="form-input" x-model="form.current"/>
            </div>
            <div>
                <label for="current_exam">Current Exam</label>
                <select id="current_exam" class="form-select text-white-dark" x-model="form.current_exam">
                    @foreach($exams as $exam)
                        <option
                            value="{{$exam->name}}" @selected($exam->name === $term->current_exam)>{{ $exam->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="name">No. of school days in the CAT</label>
                <input id="name" type="number" placeholder="37" class="form-input" x-model="form.cat_days"/>
            </div>
        </div>
    </div>
    <div class="mb-5 rounded-md border border-[#ebedf2] bg-white p-4 dark:border-[#191e3a] dark:bg-[#0e1726]">
        <h6 class="mb-5 text-lg font-bold">Dates</h6>
        <div class="grid flex-1 grid-cols-1 gap-5 sm:grid-cols-2">
            <div>
                <label for="profession">Next Term Date</label>
                <input id="profession" type="date" placeholder="YYYY-MM-DD" class="form-input flatpickr"
                       x-model="form.next_term_date"/>
            </div>
            <div>
                <label for="name">Report Exam Date</label>
                <input id="name" type="date" placeholder="YYYY-MM-DD" class="form-input flatpickr"
                       x-model="form.report_exam_date"/>
            </div>
            <div class="mt-3 sm:col-span-2 flex justify-end">
                <button type="button" class="btn btn-primary" @click="saveSettings" :disabled="loading">
                    <i class="fa-solid fa-spinner fa-spin-pulse ltr:mr-2 rtl:ml-2" x-show="loading"></i>
                    <i class="fa-solid fa-floppy-disk ltr:mr-2 rtl:ml-2" x-show="!loading"></i>
                    Save Settings
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/flatpickr.js') }}"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('term_settings', () => ({
                loading: false,
                form: {
                    current: {{ $term->current }},
                    cat_days: {{ $term->cat_days }},
                    current_exam: '{{ $term->current_exam }}',
                    report_exam_date: '{{ $term->report_exam_date?->toDateString() }}',
                    next_term_date: '{{ $term->next_term_date?->toDateString() }}',
                },

                init() {
                    flatpickr('.flatpickr', {
                        dateFormat: 'Y-m-d',
                        defaultDate: this.form.report_exam_date,
                    })
                },

                saveSettings() {
                    this.loading = true

                    axios.put('/dashboard/settings/term', this.form).then(({data: {status, msg}}) => {
                        if (status) this.showMessage(msg)

                        this.loading = false
                    }).catch(err => {
                        this.loading = false
                        console.error(err)
                    })
                }
            }))
        })
    </script>
@endpush
