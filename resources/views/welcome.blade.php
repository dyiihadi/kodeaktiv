<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>KodeAktiv - Kolaborasi Proyek Koding</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,800,900&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            background-color: #111827;
            /* bg-gray-900 */
        }

        #particles-js {
            position: absolute;
            width: 100%;
            height: 100%;
            z-index: 1;
        }

        .content-container {
            position: relative;
            z-index: 2;
        }

        .glass-panel {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Custom cursor untuk efek mengetik */
        .typed-cursor {
            color: #4f46e5;
            /* text-indigo-500 */
            font-size: 4rem;
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div id="particles-js"></div>

    <div class="relative flex flex-col items-center justify-center min-h-screen text-white content-container">

        <header class="absolute top-0 left-0 right-0 p-6">
            <nav class="flex items-center justify-between w-full mx-auto max-w-7xl">
                <a href="/" class="flex items-center space-x-2">
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <span class="text-2xl font-bold">KodeAktiv</span>
                </a>
                <div>
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/projects') }}"
                                class="font-semibold transition duration-300 hover:text-gray-300">Proyek Saya</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="font-semibold transition duration-300 hover:text-gray-300">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="inline-block px-5 py-2 ml-6 text-sm font-semibold transition duration-300 bg-indigo-600 rounded-lg hover:bg-indigo-700">Register</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </nav>
        </header>

        <main class="text-center">
            <div class="max-w-4xl p-10 mx-4 shadow-2xl glass-panel md:p-16 rounded-3xl">
                <h1 class="mb-4 text-4xl font-black tracking-tighter md:text-6xl">
                    Manajemen Proyek untuk <br /> <span id="typed-element"></span>
                </h1>
                <p class="max-w-2xl mx-auto text-lg text-gray-300">
                    Platform kolaboratif berbasis web yang dirancang khusus untuk membantu tim developer tetap
                    terorganisir, fokus, dan produktif.
                </p>
                <div class="mt-10">
                    <a href="{{ route('register') }}"
                        class="px-10 py-4 text-lg font-bold text-white transition duration-300 transform bg-indigo-600 rounded-xl hover:bg-indigo-700 hover:scale-105">
                        Mulai Proyek Pertamamu
                    </a>
                </div>
            </div>
        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>

    <script>
        // Inisialisasi Typed.js
        var typed = new Typed('#typed-element', {
            strings: ['Developer.', 'Mahasiswa.', 'Tim Anda.'],
            typeSpeed: 80,
            backSpeed: 50,
            loop: true,
            smartBackspace: true,
        });

        // Inisialisasi particles.js
        particlesJS("particles-js", {
            "particles": {
                "number": {
                    "value": 80,
                    "density": {
                        "enable": true,
                        "value_area": 800
                    }
                },
                "color": {
                    "value": "#4F46E5" // Warna indigo
                },
                "shape": {
                    "type": "circle",
                },
                "opacity": {
                    "value": 0.5,
                    "random": false,
                },
                "size": {
                    "value": 3,
                    "random": true,
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
                    "bounce": false,
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
                    },
                }
            },
            "retina_detect": true
        });
    </script>

</body>

</html>
