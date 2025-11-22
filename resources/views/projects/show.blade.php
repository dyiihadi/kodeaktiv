<x-app-layout>
    <style>
        .glass-panel {
            background: rgba(31, 41, 55, 0.5);
            /* bg-gray-800 with opacity */
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-white">
                Detail Proyek: {{ $project->name }}
            </h2>

            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}"
                    class="px-4 py-2 text-sm font-bold text-white bg-yellow-600 rounded-lg hover:bg-yellow-700">
                    Edit Proyek
                </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="relative px-4 py-3 mb-4 text-green-300 border rounded bg-green-500/20 border-green-500/30"
                    role="alert">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Bagian Detail Proyek --}}
            <div class="p-6 mb-8 text-white glass-panel sm:rounded-lg">
                <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                    <div>
                        <h3 class="text-lg font-bold">Deskripsi Proyek</h3>
                        <p class="mt-1 text-gray-300">{{ $project->description ?: 'Tidak ada deskripsi.' }}</p>

                        <div class="grid grid-cols-2 gap-4 mt-4">
                            @if ($project->start_date)
                                <div>
                                    <span class="text-sm font-semibold text-gray-400">Tanggal Mulai:</span>
                                    <div class="text-sm font-bold text-white">
                                        {{ $project->start_date->format('d F Y') }}
                                    </div>
                                </div>
                            @endif

                            @if ($project->due_date)
                                <div>
                                    <span class="text-sm font-semibold text-gray-400">Tenggat Waktu:</span>
                                    <div
                                        class="text-sm font-bold {{ $project->due_date->isPast() ? 'text-red-400' : 'text-yellow-500' }}">
                                        {{ $project->due_date->format('d F Y') }}
                                        <span class="text-xs font-normal text-gray-300">
                                            ({{ $project->due_date->diffForHumans() }})
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 text-xs text-gray-500">
                            <p>Dibuat pada: {{ $project->created_at->format('d F Y, H:i') }}</p>
                            <p>Pembaruan terakhir: {{ $project->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <div class="p-4 rounded-lg bg-white/5">
                        <h3 class="mb-2 text-lg font-bold">Anggota Tim</h3>
                        <ul class="mb-4 space-y-2">
                            <li>
                                <span class="font-semibold">{{ $project->owner->name }}</span>
                                <span
                                    class="px-2 py-1 ml-2 text-xs text-green-200 rounded-full bg-green-500/50">Pemilik</span>
                            </li>
                            @foreach ($project->members as $member)
                                <li class="flex items-center justify-between">
                                    <span>{{ $member->name }}</span>
                                    @can('removeMember', $project)
                                        <form action="{{ route('projects.members.destroy', [$project, $member]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-300"
                                                onclick="return confirm('Anda yakin ingin menghapus anggota ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </li>
                            @endforeach
                        </ul>
                        @can('update', $project)
                            <form action="{{ route('projects.members.store', $project) }}" method="POST">
                                @csrf
                                <div class="flex gap-2">
                                    <x-text-input id="email" class="block w-full" type="email" name="email"
                                        placeholder="Email anggota baru" required />
                                    <x-primary-button>Undang</x-primary-button>
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </form>
                        @endcan
                    </div>
                </div>

                {{-- Bagian File Proyek --}}
                <div class="pt-6 mt-6 border-t border-white/10">
                    <h3 class="mb-4 text-lg font-bold">File Proyek</h3>
                    <form action="{{ route('projects.files.store', $project) }}" method="POST"
                        enctype="multipart/form-data" class="mb-4">
                        @csrf
                        <div class="flex gap-2">
                            <input type="file" name="file"
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-500/20 file:text-indigo-300 hover:file:bg-indigo-500/40"
                                required>
                            <x-primary-button>Unggah</x-primary-button>
                        </div>
                        <x-input-error :messages="$errors->get('file')" class="mt-2" />
                    </form>
                    <div class="space-y-3">
                        @forelse ($project->files as $file)
                            <div class="p-3 border rounded-lg border-white/10 bg-white/5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <a href="{{ Storage::url($file->path) }}" target="_blank"
                                            class="font-semibold text-indigo-400 hover:underline">{{ $file->original_name }}</a>
                                        <p class="text-sm text-gray-400">Diunggah oleh {{ $file->uploader->name }} pada
                                            {{ $file->created_at->format('d M Y, H:i') }}</p>
                                    </div>
                                    @can('delete', $file)
                                        <form action="{{ route('projects.files.destroy', $file) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-xs text-red-400 hover:text-red-300"
                                                onclick="return confirm('Anda yakin ingin menghapus file ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                                {{-- Diskusi File --}}
                                <div class="pt-3 mt-3 border-t border-white/10">
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
                                        @foreach ($file->comments as $comment)
                                            <div class="text-sm">
                                                <div class="flex items-center justify-between">
                                                    <span class="font-semibold text-gray-300">
                                                        {{ $comment->author->name }}:
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $comment->created_at->diffForHumans() }}
                                                    </span>
                                                </div>
                                                <p class="pl-2 text-gray-400">{{ $comment->body }}</p>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400">Belum ada file yang diunggah.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- KANBAN BOARD & TASK MODAL --}}
            <div x-data="{
                isModalOpen: false,
                isEditing: false,
                selectedTask: null,
                tasks: {{ json_encode($tasks->flatten()) }},
                timeAgo(dateString) {
                    const date = new Date(dateString);
                    const seconds = Math.floor((new Date() - date) / 1000);
                    let interval = seconds / 31536000;
                    if (interval > 1) return Math.floor(interval) + ' tahun lalu';
                    interval = seconds / 2592000;
                    if (interval > 1) return Math.floor(interval) + ' bulan lalu';
                    interval = seconds / 86400;
                    if (interval > 1) return Math.floor(interval) + ' hari lalu';
                    interval = seconds / 3600;
                    if (interval > 1) return Math.floor(interval) + ' jam lalu';
                    interval = seconds / 60;
                    if (interval > 1) return Math.floor(interval) + ' menit lalu';
                    return 'Baru saja';
                }
            }">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3" id="kanban-board">
                    {{-- Kolom To Do --}}
                    <div class="p-4 rounded-lg glass-panel">
                        <h3 class="mb-4 text-lg font-bold text-white">To Do</h3>
                        <div class="space-y-4 kanban-column min-h-[100px]" data-status="To Do">
                            @foreach ($tasks['To Do'] ?? [] as $task)
                                <button
                                    @click="isModalOpen = true; selectedTask = tasks.find(t => t.id === {{ $task->id }})"
                                    class="w-full p-4 text-left border rounded-lg shadow-md bg-white/5 border-white/10 hover:bg-white/10"
                                    data-task-id="{{ $task->id }}">
                                    <p class="text-white">{{ $task->title }}</p>
                                    {{-- Menampilkan Rentang Tanggal di Card --}}
                                    @if ($task->start_date || $task->due_date)
                                        <div class="mt-2 space-y-1">
                                            @if ($task->start_date)
                                                <div class="flex items-center text-xs text-indigo-300">
                                                    <span class="mr-1">Mulai:</span>
                                                    <span>{{ $task->start_date->format('d M') }}</span>
                                                </div>
                                            @endif
                                            @if ($task->due_date)
                                                <div
                                                    class="flex items-center text-xs {{ $task->due_date->isPast() ? 'text-red-400' : 'text-gray-400' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $task->due_date->format('d M') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                        {{-- Form Tambah Tugas (Updated) --}}
                        <div class="mt-4">
                            <form method="POST" action="{{ route('tasks.store') }}" class="space-y-2">
                                @csrf
                                <input type="hidden" name="project_id" value="{{ $project->id }}">
                                <x-text-input class="block w-full text-sm" type="text" name="title"
                                    placeholder="Judul tugas..." required />

                                {{-- Input Tanggal Start & End Ringkas --}}
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative">
                                        <label class="text-[10px] text-gray-400 block mb-1">Mulai</label>
                                        <x-text-input class="block w-full px-2 py-1 text-xs" type="date"
                                            name="start_date" />
                                    </div>
                                    <div class="relative">
                                        <label class="text-[10px] text-gray-400 block mb-1">Selesai</label>
                                        <x-text-input class="block w-full px-2 py-1 text-xs" type="date"
                                            name="due_date" />
                                    </div>
                                </div>

                                <x-primary-button class="justify-center w-full py-2 mt-2 text-xs">+
                                    Tambah</x-primary-button>
                            </form>
                        </div>
                    </div>

                    {{-- Kolom In Progress --}}
                    <div class="p-4 rounded-lg glass-panel">
                        <h3 class="mb-4 text-lg font-bold text-white">In Progress</h3>
                        <div class="space-y-4 kanban-column min-h-[100px]" data-status="In Progress">
                            @foreach ($tasks['In Progress'] ?? [] as $task)
                                <button
                                    @click="isModalOpen = true; selectedTask = tasks.find(t => t.id === {{ $task->id }})"
                                    class="w-full p-4 text-left border rounded-lg shadow-md bg-white/5 border-white/10 hover:bg-white/10"
                                    data-task-id="{{ $task->id }}">
                                    <p class="text-white">{{ $task->title }}</p>
                                    @if ($task->start_date || $task->due_date)
                                        <div class="mt-2 space-y-1">
                                            @if ($task->start_date)
                                                <div class="flex items-center text-xs text-indigo-300">
                                                    <span class="mr-1">Mulai:</span>
                                                    <span>{{ $task->start_date->format('d M') }}</span>
                                                </div>
                                            @endif
                                            @if ($task->due_date)
                                                <div
                                                    class="flex items-center text-xs {{ $task->due_date->isPast() ? 'text-red-400' : 'text-gray-400' }}">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $task->due_date->format('d M') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kolom Done --}}
                    <div class="p-4 rounded-lg glass-panel">
                        <h3 class="mb-4 text-lg font-bold text-white">Done</h3>
                        <div class="space-y-4 kanban-column min-h-[100px]" data-status="Done">
                            @foreach ($tasks['Done'] ?? [] as $task)
                                <button
                                    @click="isModalOpen = true; selectedTask = tasks.find(t => t.id === {{ $task->id }})"
                                    class="w-full p-4 text-left border rounded-lg shadow-md bg-white/5 border-white/10 hover:bg-white/10"
                                    data-task-id="{{ $task->id }}">
                                    <p class="text-gray-400 line-through">{{ $task->title }}</p>
                                    @if ($task->start_date || $task->due_date)
                                        <div class="mt-2 space-y-1">
                                            @if ($task->start_date)
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <span class="mr-1">Mulai:</span>
                                                    <span>{{ $task->start_date->format('d M') }}</span>
                                                </div>
                                            @endif
                                            @if ($task->due_date)
                                                <div class="flex items-center text-xs text-gray-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 mr-1"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                    <span>{{ $task->due_date->format('d M') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- MODAL DETAIL & EDIT TASK --}}
                <div x-show="isModalOpen" class="fixed inset-0 z-40 bg-black bg-opacity-50"
                    @click="isModalOpen = false" x-cloak></div>
                <div x-show="isModalOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4"
                    x-cloak>
                    <div @click.outside="isModalOpen = false; isEditing = false"
                        class="glass-panel w-full max-w-2xl max-h-[90vh] flex flex-col sm:rounded-lg">
                        <template x-if="selectedTask">
                            <div>
                                {{-- MODE VIEW --}}
                                <div x-show="!isEditing">
                                    <div class="flex items-center justify-between p-4 border-b border-white/10">
                                        <h2 class="text-xl font-bold text-white" x-text="selectedTask.title"></h2>
                                        @can('update', $project)
                                            <button @click="isEditing = true"
                                                class="text-sm text-indigo-400 hover:underline">Edit</button>
                                        @endcan
                                    </div>
                                    <div class="p-6 overflow-y-auto">
                                        <p class="mb-6 text-gray-300"
                                            x-text="selectedTask.description || 'Tidak ada deskripsi.'"></p>

                                        {{-- Info Tanggal di Modal --}}
                                        <div class="grid grid-cols-2 gap-4 mb-6">
                                            <template x-if="selectedTask.start_date">
                                                <div>
                                                    <span class="text-sm font-semibold text-gray-300">Tanggal
                                                        Mulai:</span>
                                                    <div class="text-sm font-bold text-white"
                                                        x-text="new Date(selectedTask.start_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })">
                                                    </div>
                                                </div>
                                            </template>
                                            <template x-if="selectedTask.due_date">
                                                <div>
                                                    <span class="text-sm font-semibold text-gray-300">Tenggat
                                                        Waktu:</span>
                                                    <div class="text-sm font-bold"
                                                        :class="{
                                                            'text-red-400': new Date(selectedTask.due_date) <
                                                                new Date() &&
                                                                selectedTask.status !== 'Done',
                                                            'text-yellow-500': new Date(selectedTask.due_date) >=
                                                                new Date() && selectedTask.status !== 'Done',
                                                            'text-green-400': selectedTask.status === 'Done'
                                                        }"
                                                        x-text="new Date(selectedTask.due_date).toLocaleDateString('id-ID', { year: 'numeric', month: 'long', day: 'numeric' })">
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <h4 class="mb-2 text-sm font-semibold text-white">Diskusi Tugas</h4>
                                        <form method="POST" :action="'/tasks/' + selectedTask.id + '/comments'">
                                            @csrf
                                            <textarea name="body" class="w-full text-gray-200 border-gray-500 rounded-md shadow-sm bg-white/5" rows="2"
                                                placeholder="Tulis komentar..." required></textarea>
                                            <x-primary-button class="mt-2 text-xs">Kirim Komentar</x-primary-button>
                                        </form>
                                        <div class="mt-4 space-y-3">
                                            <template x-for="comment in selectedTask.comments" :key="comment.id">
                                                <div class="text-sm">
                                                    <div class="flex items-center justify-between">
                                                        <span class="font-semibold text-gray-200"
                                                            x-text="comment.author.name + ':'"></span>
                                                        <span class="text-xs text-gray-500"
                                                            x-text="timeAgo(comment.created_at)"></span>
                                                    </div>
                                                    <p class="pl-2 text-gray-300" x-text="comment.body"></p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>

                                {{-- MODE EDIT --}}
                                <div x-show="isEditing">
                                    <form method="POST" :action="'/tasks/' + selectedTask.id">
                                        @csrf
                                        @method('PATCH')
                                        <div class="p-4 border-b border-white/10">
                                            <x-text-input name="title" x-model="selectedTask.title"
                                                class="w-full text-xl font-bold" />
                                        </div>
                                        <div class="p-6 overflow-y-auto">
                                            <textarea name="description" x-model="selectedTask.description"
                                                class="w-full text-gray-200 border-gray-500 rounded-md shadow-sm bg-white/5" rows="4"
                                                placeholder="Tambahkan deskripsi..."></textarea>

                                            <div class="grid grid-cols-2 gap-4 mt-4">
                                                <div>
                                                    <x-input-label for="task_start_date" :value="__('Tanggal Mulai')"
                                                        class="text-white" />
                                                    {{-- Binding value untuk date input (substring untuk ambil YYYY-MM-DD) --}}
                                                    <x-text-input id="task_start_date" type="date"
                                                        name="start_date" class="block w-full mt-1"
                                                        ::value="selectedTask.start_date ? selectedTask.start_date.substring(0,
                                                            10) : ''" />
                                                </div>
                                                <div>
                                                    <x-input-label for="task_due_date" :value="__('Tenggat Waktu')"
                                                        class="text-white" />
                                                    <x-text-input id="task_due_date" type="date" name="due_date"
                                                        class="block w-full mt-1" ::value="selectedTask.due_date ? selectedTask.due_date.substring(0, 10) :
                                                            ''" />
                                                </div>
                                            </div>

                                            <div class="flex justify-end gap-2 mt-4">
                                                <button type="button" @click="isEditing = false"
                                                    class="px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-white/10">Batal</button>
                                                <x-primary-button>Simpan Perubahan</x-primary-button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

                                <div
                                    class="flex items-center justify-between p-4 border-t bg-black/20 border-white/10">
                                    <div>
                                        @can('delete', $project)
                                            <form method="POST" :action="'/tasks/' + selectedTask.id"
                                                onsubmit="return confirm('Anda yakin ingin menghapus tugas ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-400 hover:underline">Hapus
                                                    Tugas Ini</button>
                                            </form>
                                        @endcan
                                    </div>
                                    <button @click="isModalOpen = false; isEditing = false"
                                        class="px-4 py-2 text-sm text-gray-300 rounded-lg hover:bg-white/10">Tutup</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
