<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Detail Proyek: {{ $project->name }}
            </h2>

            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}"
                    class="px-4 py-2 text-sm font-bold text-white bg-yellow-500 rounded hover:bg-yellow-600">
                    Edit Proyek
                </a>
            @endcan
        </div>
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
                                <li class="flex items-center justify-between">
                                    <span>{{ $member->name }}</span>

                                    @can('removeMember', $project)
                                        <form action="{{ route('projects.members.destroy', [$project, $member]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type-="submit" class="text-xs text-red-500 hover:text-red-700"
                                                onclick="return confirm('Anda yakin ingin menghapus anggota ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </li>
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

                    <div class="mt-8" x-data="{
                        isModalOpen: false,
                        isEditing: false,
                        selectedTask: null,
                        tasks: {{ json_encode($tasks->flatten()) }}
                    }" x-init="$watch('tasks', value => console.log(value))">
                        <div class="p-6 mb-6 bg-white rounded-lg shadow-sm">
                            <h3 class="mb-4 text-lg font-bold">File Proyek</h3>

                            <form action="{{ route('projects.files.store', $project) }}" method="POST"
                                enctype="multipart/form-data" class="mb-4">
                                @csrf
                                <div class="flex gap-2">
                                    <input type="file" name="file"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-violet-50 file:text-violet-700 hover:file:bg-violet-100"
                                        required>
                                    <x-primary-button>Unggah</x-primary-button>
                                </div>
                                <x-input-error :messages="$errors->get('file')" class="mt-2" />
                            </form>

                            <div class="space-y-2">
                                @forelse ($project->files as $file)
                                    <div class="p-3 border rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <a href="{{ Storage::url($file->path) }}" target="_blank"
                                                    class="font-semibold text-blue-600 hover:underline">{{ $file->original_name }}</a>
                                                <p class="text-sm text-gray-500">Diunggah oleh
                                                    {{ $file->uploader->name }} ({{ round($file->size / 1024, 2) }}
                                                    KB)</p>
                                            </div>
                                        </div>
                                        <div class="pt-4 mt-4 border-t">
                                            <h4 class="mb-2 text-sm font-semibold">Diskusi File</h4>
                                            <form action="{{ route('files.comments.store', $file) }}" method="POST"
                                                class="mb-3">
                                                @csrf
                                                <div class="flex gap-2">
                                                    <x-text-input name="body" class="w-full text-sm"
                                                        placeholder="Tulis komentar..." required />
                                                    <x-primary-button class="text-xs">Kirim</x-primary-button>
                                                </div>
                                            </form>
                                            <div class="space-y-2">
                                                @forelse ($file->comments as $comment)
                                                    <div class="text-sm">
                                                        <span
                                                            class="font-semibold">{{ $comment->author->name }}:</span>
                                                        <span class="text-gray-700">{{ $comment->body }}</span>
                                                    </div>
                                                @empty
                                                    <p class="text-sm text-gray-500">Belum ada komentar.</p>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-gray-500">Belum ada file yang diunggah.</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3" id="kanban-board"
                            data-project-id="{{ $project->id }}">

                            <div class="p-4 bg-gray-100 rounded-lg">
                                <h3 class="mb-4 text-lg font-bold">To Do (Tugas Baru)</h3>
                                <div class="space-y-4 kanban-column" data-status="To Do">
                                    @foreach ($tasks['To Do'] ?? [] as $task)
                                        <button
                                            @click="isModalOpen = true; selectedTask = tasks.find(task => task.id === {{ $task->id }})"
                                            class="w-full p-4 text-left bg-white border rounded-lg shadow hover:bg-gray-50"
                                            data-task-id="{{ $task->id }}">
                                            <p>{{ $task->title }}</p>
                                        </button>
                                    @endforeach
                                </div>
                                <div class="mt-4">
                                    <form method="POST" action="{{ route('tasks.store') }}"> @csrf
                                        <input type="hidden" name="project_id" value="{{ $project->id }}">

                                        <x-text-input class="block w-full text-sm" type="text" name="title"
                                            placeholder="+ Tambah tugas baru" required />
                                    </form>
                                </div>
                            </div>

                            <div class="p-4 bg-gray-100 rounded-lg">
                                <h3 class="mb-4 text-lg font-bold">In Progress (Dikerjakan)</h3>
                                <div class="space-y-4 kanban-column" data-status="In Progress">
                                    @foreach ($tasks['In Progress'] ?? [] as $task)
                                        <button
                                            @click="isModalOpen = true; selectedTask = tasks.find(task => task.id === {{ $task->id }})"
                                            class="w-full p-4 text-left bg-white border rounded-lg shadow hover:bg-gray-50"
                                            data-task-id="{{ $task->id }}">
                                            <p>{{ $task->title }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            <div class="p-4 bg-gray-100 rounded-lg">
                                <h3 class="mb-4 text-lg font-bold">Done (Selesai)</h3>
                                <div class="space-y-4 kanban-column" data-status="Done">
                                    @foreach ($tasks['Done'] ?? [] as $task)
                                        <button
                                            @click="isModalOpen = true; selectedTask = tasks.find(task => task.id === {{ $task->id }})"
                                            class="w-full p-4 text-left bg-white border rounded-lg shadow hover:bg-gray-50"
                                            data-task-id="{{ $task->id }}">
                                            <p>{{ $task->title }}</p>
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div x-show="isModalOpen" class="fixed inset-0 z-40 bg-black bg-opacity-50"
                            @click="isModalOpen = false"></div>

                        <div x-show="isModalOpen" x-transition
                            class="fixed inset-0 z-50 flex items-center justify-center p-4" x-cloak>
                            <div @click.outside="isModalOpen = false; isEditing = false"
                                class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[90vh] flex flex-col">
                                <template x-if="selectedTask">
                                    <div>
                                        <div x-show="!isEditing">
                                            <div class="flex items-center justify-between p-4 border-b">
                                                <h2 class="text-xl font-bold" x-text="selectedTask.title"></h2>
                                                @can('update', $project)
                                                    <button @click="isEditing = true"
                                                        class="text-sm text-blue-600 hover:underline">Edit</button>
                                                @endcan
                                            </div>
                                            <div class="p-6 overflow-y-auto">
                                                <p class="mb-6 text-gray-600"
                                                    x-text="selectedTask.description || 'Tidak ada deskripsi.'"></p>

                                                <h4 class="mb-2 text-sm font-semibold">Diskusi Tugas</h4>
                                                <form method="POST"
                                                    :action="'/tasks/' + selectedTask.id + '/comments'">
                                                    @csrf
                                                    <textarea name="body" class="w-full border-gray-300 rounded-md shadow-sm" rows="2"
                                                        placeholder="Tulis komentar..." required></textarea>
                                                    <x-primary-button class="mt-2 text-xs">Kirim
                                                        Komentar</x-primary-button>
                                                </form>
                                                <div class="mt-4 space-y-3">
                                                    <template x-for="comment in selectedTask.comments"
                                                        :key="comment.id">
                                                        <div class="text-sm">
                                                            <span class="font-semibold"
                                                                x-text="comment.author.name + ':'"></span>
                                                            <span class="text-gray-700" x-text="comment.body"></span>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>
                                        </div>

                                        <div x-show="isEditing">
                                            <form method="POST" :action="'/tasks/' + selectedTask.id">
                                                @csrf
                                                @method('PATCH')
                                                <div class="p-4 border-b">
                                                    <x-text-input name="title" x-model="selectedTask.title"
                                                        class="w-full text-xl font-bold" />
                                                </div>
                                                <div class="p-6 overflow-y-auto">
                                                    <textarea name="description" x-model="selectedTask.description" class="w-full border-gray-300 rounded-md shadow-sm"
                                                        rows="4" placeholder="Tambahkan deskripsi..."></textarea>
                                                    <div class="flex justify-end gap-2 mt-4">
                                                        <button type="button" @click="isEditing = false"
                                                            class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100">Batal</button>
                                                        <x-primary-button>Simpan Perubahan</x-primary-button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="flex items-center justify-between p-4 border-t bg-gray-50">
                                            <div>
                                                @can('delete', $project)
                                                    <form method="POST" :action="'/tasks/' + selectedTask.id"
                                                        onsubmit="return confirm('Anda yakin ingin menghapus tugas ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="text-sm text-red-600 hover:underline">Hapus Tugas
                                                            Ini</button>
                                                    </form>
                                                @endcan
                                            </div>
                                            <button @click="isModalOpen = false; isEditing = false"
                                                class="px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-100">Tutup</button>
                                        </div>
                                    </div>
                                </template>
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
