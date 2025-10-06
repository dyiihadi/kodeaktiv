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

                    <div class="p-4 mb-6 rounded-lg bg-gray-50">
                        <h3 class="mb-4 text-lg font-bold">Anggota Tim</h3>
                        <ul class="mb-4 space-y-2">
                            <li>
                                <span class="font-semibold">{{ $project->owner->name }}</span>
                                <span
                                    class="px-2 py-1 ml-2 text-xs text-green-800 bg-green-200 rounded-full">Pemilik</span>
                            </li>
                            @foreach ($project->members as $member)
                                <li>{{ $member->name }}</li>
                            @endforeach
                        </ul>

                        <form action="{{ route('projects.members.store', $project) }}" method="POST">
                            @csrf
                            <div class="flex gap-2">
                                <x-text-input id="email" class="block w-full" type="email" name="email"
                                    placeholder="Email anggota baru" required />
                                <x-primary-button>Undang</x-primary-button>
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </form>
                    </div>

                    @if (session('status'))
                        <div class="relative px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded"
                            role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="mt-8">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3" id="kanban-board"
                            data-project-id="{{ $project->id }}">

                            <div class="p-4 bg-gray-100 rounded-lg">
                                <h3 class="mb-4 text-lg font-bold">To Do (Tugas Baru)</h3>
                                <div class="space-y-4 kanban-column" data-status="To Do">
                                    @foreach ($tasks['To Do'] ?? [] as $task)
                                        <div class="p-4 bg-white border rounded-lg shadow"
                                            data-task-id="{{ $task->id }}">
                                            <p>{{ $task->title }}</p>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <form method="POST" action="{{ route('tasks.store', $project) }}">
                                        @csrf
                                        <x-text-input class="block w-full text-sm" type="text" name="title"
                                            placeholder="+ Tambah tugas baru" required />
                                    </form>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-100 rounded-lg">
                                <h3 class="mb-4 text-lg font-bold">In Progress (Dikerjakan)</h3>
                                <div class="space-y-4 kanban-column" data-status="In Progress">
                                    @foreach ($tasks['In Progress'] ?? [] as $task)
                                        <div class="p-4 bg-white border rounded-lg shadow"
                                            data-task-id="{{ $task->id }}">
                                            <p>{{ $task->title }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-4 bg-gray-100 rounded-lg">
                                <h3 class="mb-4 text-lg font-bold">Done (Selesai)</h3>
                                <div class="space-y-4 kanban-column" data-status="Done">
                                    @foreach ($tasks['Done'] ?? [] as $task)
                                        <div class="p-4 bg-white border rounded-lg shadow"
                                            data-task-id="{{ $task->id }}">
                                            <p class="text-gray-500 line-through">{{ $task->title }}</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
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
