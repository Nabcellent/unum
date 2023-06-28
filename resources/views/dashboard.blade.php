@extends('layouts.app')
@section('title', 'Dashboard')
@section('content')

    <div class="h-full w-full lg:w-1/2" x-data="comingsoon">
        <div class="mx-auto max-w-[480px] p-5 md:p-10">
            <h4 class="mb-2 text-2xl font-bold text-primary md:text-4xl">Coming Soon</h4>
            <p class="mb-10 text-base font-bold text-white-dark">We will be here in a short while.....</p>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('comingsoon', () => ({
                timer1: null,
                demo1: {
                    days: null,
                    hours: null,
                    minutes: null,
                    seconds: null,
                },
                setTimerDemo1() {
                    let date = new Date();
                    date.setFullYear(date.getFullYear() + 1);
                    let countDownDate = date.getTime();

                    this.timer1 = setInterval(() => {
                        let now = new Date().getTime();

                        let distance = countDownDate - now;

                        this.demo1.days = Math.floor(distance / (1000 * 60 * 60 * 24));
                        this.demo1.hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                        this.demo1.minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                        this.demo1.seconds = Math.floor((distance % (1000 * 60)) / 1000);

                        if (distance < 0) {
                            clearInterval(this.timer1);
                        }
                    }, 500);
                },
            }));
        });
    </script>

@endsection
