<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Detail Proyek: {{ $project->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold">Deskripsi Proyek</h3>
                    <p class="mt-2 text-gray-600">{{ $project->description ?: 'Tidak ada deskripsi.' }}</p>

                    @if ($project->due_date)
                        <p class="mt-4 text-sm text-gray-500">
                            Tenggat Waktu: {{ \Carbon\Carbon::parse($project->due_date)->format('d F Y') }}
                        </p>
                    @endif

                    <hr class="my-6">

                    <h3 class="mb-4 text-lg font-bold">Tambah Tugas Baru</h3>
                    <form method="POST" action="{{ route('tasks.store', $project) }}">
                        @csrf
                        <div>
                            <x-input-label for="title" value="Judul Tugas" />
                            <x-text-input id="title" class="block w-full mt-1" type="text" name="title"
                                :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>
                                {{ __('Tambah Tugas') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6 overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="mb-4 text-lg font-bold">Daftar Tugas</h3>
                    <div class="mt-4 space-y-4">
                        @forelse ($project->tasks as $task)
                            <div class="flex items-center justify-between p-4 border rounded-lg">
                                <div>
                                    <p class="font-semibold">{{ $task->title }}</p>
                                    <span
                                        class="px-2 py-1 text-sm text-white bg-blue-500 rounded-full">{{ $task->status }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">Belum ada tugas di proyek ini.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
