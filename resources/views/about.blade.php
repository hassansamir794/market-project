@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    @php
        use Carbon\Carbon;

        $now = Carbon::now('Asia/Baghdad');

        $openTime = Carbon::createFromTime(9, 0, 0, 'Asia/Baghdad');   // 9:00 AM
        $closeTime = Carbon::createFromTime(1, 0, 0, 'Asia/Baghdad');  // 1:00 AM (next day)

        // Handle overnight opening
        $isOpen = $now->between($openTime, Carbon::createFromTime(23, 59, 59, 'Asia/Baghdad'))
                  || $now->between(Carbon::createFromTime(0, 0, 0, 'Asia/Baghdad'), $closeTime);
    @endphp

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Info -->
        <div class="glass-card p-6">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight">
                About Our Market
            </h1>

            <p class="mt-4 text-gray-700 leading-relaxed">
                Welcome to <span class="font-semibold">Market</span>.
                We provide quality products with clear pricing and a simple shopping experience.
            </p>

            <!-- Address + Contact -->
            <div class="mt-6 glass-card p-4 bg-white/70">
                <div class="text-sm font-semibold text-gray-900">📍 Address</div>
                <div class="mt-1 text-gray-700">
                    Rasan Market<br>
                    (Direct street to Barzan)
                </div>

                <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    <div class="glass-card p-3 bg-white/70">
                        <div class="font-semibold">📞 Phone</div>
                        <div class="text-gray-700">+964 750 447 39 64</div>
                    </div>
                    <div class="glass-card p-3 bg-white/70">
                        <div class="font-semibold">✉️ Email</div>
                        <div class="text-gray-700">RasanMarket@market.com</div>
                    </div>
                </div>

                <a target="_blank"
                   class="mt-4 inline-flex items-center justify-center rounded-xl bg-black text-white px-4 py-2 font-semibold hover:opacity-90 transition"
                   href="https://www.google.com/maps?q=36.85261225541263,44.130020767406336">
                    Open in Google Maps
                </a>
            </div>

            <!-- Opening Hours -->
            <div class="mt-6 glass-card p-4 bg-white/70">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold">🕒 Opening Hours</h2>

                    @if($isOpen)
                        <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-sm font-semibold text-green-800">
                            <span class="h-2 w-2 rounded-full bg-green-600"></span>
                            Open now
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-sm font-semibold text-red-800">
                            <span class="h-2 w-2 rounded-full bg-red-600"></span>
                            Closed
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                    @foreach([
                        'Saturday',
                        'Sunday',
                        'Monday',
                        'Tuesday',
                        'Wednesday',
                        'Thursday',
                        'Friday'
                    ] as $day)
                        <div class="flex items-center justify-between glass-card p-3 bg-white/70">
                            <span class="font-medium">{{ $day }}</span>
                            <span class="text-gray-700 font-semibold">
                                9:00 AM – 1:00 AM
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="glass-card overflow-hidden">
            <div class="p-6 border-b border-white/30 bg-white/70">
                <h2 class="text-lg font-semibold">Our Location</h2>
                <p class="mt-1 text-sm text-gray-600">
                    Find us on the map below.
                </p>
            </div>

            <iframe
                src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d298.2079171405957!2d44.130020767406336!3d36.85261225541263!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2siq!4v1770290184040!5m2!1sen!2siq"
                width="100%"
                height="420"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
@endsection
