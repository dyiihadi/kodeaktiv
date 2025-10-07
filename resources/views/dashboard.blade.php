<x-app-layout>
    <style>
        .main-background {
            background-color: #111827;
            /* bg-gray-900 */
            position: relative;
            z-index: 0;
        }

        .glass-panel {
            background: rgba(31, 41, 55, 0.5);
            /* bg-gray-800 with opacity */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }

        #particles-js-dashboard {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Animasi untuk orb/planet */
        @keyframes rotate-orb {
            0% {
                transform: rotateY(0deg) rotateX(10deg);
            }

            100% {
                transform: rotateY(360deg) rotateX(10deg);
            }
        }

        .orb {
            width: 200px;
            height: 200px;
            background: radial-gradient(circle at 50% 120%, #818cf8, #4f46e5 80%);
            border-radius: 50%;
            box-shadow: 0 0 50px #4f46e5, inset 0 0 20px rgba(255, 255, 255, 0.3);
            animation: rotate-orb 40s linear infinite;
            transform-style: preserve-3d;
        }

        /* Timeline */
        .timeline-item::before {
            content: '';
            position: absolute;
            left: 11px;
            top: 28px;
            bottom: 0;
            width: 2px;
            background: rgba(255, 255, 255, 0.2);
        }

        .timeline-item:last-child::before {
            display: none;
        }

        .timeline-dot {
            position: absolute;
            left: 0;
            top: 24px;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #4f46e5;
            /* indigo-600 */
            border: 2px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-white">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="main-background">
        <div id="particles-js-dashboard"></div>
        <div class="relative py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="mb-8 text-white">
                    <h1 class="text-4xl font-bold">Halo, {{ Auth::user()->name }}!</h1>
                    <p class="text-lg text-gray-300">Selamat datang di pusat komando proyek Anda.</p>
                </div>

                <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">
                    <div class="space-y-8 lg:col-span-1">
                        <div class="p-6 text-center glass-panel rounded-2xl">
                            <h3 class="font-semibold text-gray-300">Total Proyek Aktif</h3>
                            <p x-data="{ count: 0 }" x-init="let num = {{ $totalProjects }};
                            let step = num / 50;
                            let current = 0;
                            let interval = setInterval(() => {
                                current += step;
                                if (current >= num) {
                                    current = num;
                                    clearInterval(interval);
                                }
                                count = Math.round(current);
                            }, 20);" x-text="count"
                                class="font-black text-white text-7xl"></p>
                        </div>
                        <div class="p-6 text-center glass-panel rounded-2xl">
                            <h3 class="font-semibold text-gray-300">Tugas Telah Selesai</h3>
                            <p x-data="{ count: 0 }" x-init="let num = {{ $tasksDone }};
                            let step = num / 50 || 1;
                            let current = 0;
                            let interval = setInterval(() => {
                                current += step;
                                if (current >= num) {
                                    current = num;
                                    clearInterval(interval);
                                }
                                count = Math.round(current);
                            }, 20);" x-text="count"
                                class="font-black text-white text-7xl"></p>
                        </div>
                    </div>

                    <div class="flex flex-col items-center justify-center space-y-8 lg:col-span-1">
                        <div class="orb"></div>
                        <a href="{{ route('projects.create') }}"
                            class="block w-full p-6 text-lg font-bold text-center transition duration-300 glass-panel rounded-2xl hover:bg-gray-700/50">
                            + Buat Proyek Baru
                        </a>
                    </div>

                    <div class="p-6 lg:col-span-1 glass-panel rounded-2xl">
                        <h3 class="mb-4 text-lg font-bold">Aktivitas Terbaru</h3>
                        <div class="relative space-y-6">
                            @forelse ($recentActivities as $activity)
                                <div class="pl-10 timeline-item">
                                    <div class="timeline-dot">
                                        @if ($activity instanceof \App\Models\Task)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                                                </path>
                                            </svg>
                                        @else
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-white"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                                                </path>
                                            </svg>
                                        @endif
                                    </div>
                                    @if ($activity instanceof \App\Models\Task)
                                        <p class="text-sm font-semibold">Tugas baru ditambahkan</p>
                                        <p class="text-xs text-gray-300">"{{ Str::limit($activity->title, 30) }}" di
                                            proyek {{ $activity->project->name }}</p>
                                    @else
                                        <p class="text-sm font-semibold">Komentar baru oleh
                                            {{ $activity->author->name }}</p>
                                        <p class="text-xs text-gray-300">di tugas
                                            "{{ Str::limit($activity->task->title, 20) }}"</p>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-400">{{ $activity->created_at->diffForHumans() }}
                                    </p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-300">Belum ada aktivitas terbaru.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika elemen ada sebelum inisialisasi
            if (document.getElementById('particles-js-dashboard')) {
                particlesJS("particles-js-dashboard", {
                    "particles": {
                        "number": {
                            "value": 80,
                            "density": {
                                "enable": true,
                                "value_area": 800
                            }
                        },
                        "color": {
                            "value": "#4F46E5"
                        },
                        "shape": {
                            "type": "circle"
                        },
                        "opacity": {
                            "value": 0.5,
                            "random": false
                        },
                        "size": {
                            "value": 3,
                            "random": true
                        },
                        "line_linked": {
                            "enable": true,
                            "distance": 150,
                            "color": "#ffffff",
                            "opacity": 0.1,
                            "width": 1
                        },
                        "move": {
                            "enable": true,
                            "speed": 2,
                            "direction": "none",
                            "random": false,
                            "straight": false,
                            "out_mode": "out",
                            "bounce": false
                        }
                    },
                    "interactivity": {
                        "detect_on": "canvas",
                        "events": {
                            "onhover": {
                                "enable": true,
                                "mode": "repulse"
                            },
                            "onclick": {
                                "enable": true,
                                "mode": "push"
                            },
                            "resize": true
                        },
                        "modes": {
                            "repulse": {
                                "distance": 100,
                                "duration": 0.4
                            },
                            "push": {
                                "particles_nb": 4
                            }
                        }
                    },
                    "retina_detect": true
                });
            }
        });
    </script>
</x-app-layout>
