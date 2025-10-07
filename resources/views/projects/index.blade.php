<x-app-layout>
    <style>
        .glass-panel {
            background: rgba(31, 41, 55, 0.5);
            /* bg-gray-800 with opacity */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: white;
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                {{ __('Proyek Saya') }}
            </h2>
            <a href="{{ route('projects.create') }}"
                class="inline-block px-4 py-2 text-sm font-bold text-white transition duration-300 bg-indigo-600 rounded-lg hover:bg-indigo-700">
                + Buat Proyek Baru
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded"
                    role="alert">
                    {{ session('status') }}
                </div>
            @endif

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse ($projects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                        class="block p-6 transition-transform duration-300 transform glass-panel rounded-2xl hover:-translate-y-1">
                        <div class="flex items-start justify-between">
                            <h3 class="text-lg font-bold text-white">{{ $project->name }}</h3>
                            @if ($project->owner->id !== Auth::id())
                                <span
                                    class="px-2 py-1 text-xs text-indigo-200 rounded-full bg-indigo-500/50">Dibagikan</span>
                            @endif
                        </div>
                        <p class="mt-2 text-sm text-gray-300">{{ Str::limit($project->description, 100) }}</p>
                        <p class="mt-4 text-xs text-gray-400">Pemilik: {{ $project->owner->name }}</p>
                    </a>
                @empty
                    <div class="py-16 text-center lg:col-span-3 glass-panel rounded-2xl">
                        <h3 class="text-xl font-semibold text-white">Anda belum memiliki proyek.</h3>
                        <p class="mt-2 text-gray-300">Mulai kolaborasi dengan membuat proyek pertama Anda.</p>
                        <a href="{{ route('projects.create') }}"
                            class="inline-block px-6 py-3 mt-6 font-bold text-white transition duration-300 bg-indigo-600 rounded-lg hover:bg-indigo-700">
                            Buat Proyek Sekarang
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
