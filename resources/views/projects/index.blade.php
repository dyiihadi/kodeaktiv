<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Daftar Proyek</h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-end mb-4">
                        <a href="{{ route('projects.create') }}"
                            class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                            Buat Proyek Baru
                        </a>
                    </div>
                    @forelse ($projects as $project)
                        <a href="{{ route('projects.show', $project) }}"
                            class="block p-4 mb-4 transition border rounded-lg bg-gray-50 hover:bg-gray-100">
                            <h3 class="text-lg font-bold">{{ $project->name }}</h3>
                            <p class="mt-2 text-gray-600">{{ $project->description }}</p>
                        </a>
                    @empty
                        <p>Kamu belum memiliki proyek.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
