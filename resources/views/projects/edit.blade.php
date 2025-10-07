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
        <h2 class="text-xl font-semibold leading-tight text-white">
            {{ __('Edit Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm glass-panel sm:rounded-lg">
                <div class="p-6 text-white md:p-8">
                    <form method="POST" action="{{ route('projects.update', $project) }}">
                        @csrf
                        @method('PATCH')

                        <div>
                            <x-input-label for="name" :value="__('Nama Proyek')" class="text-white" />
                            <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                                :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" class="text-white" />
                            <textarea id="description" name="description"
                                class="block w-full mt-1 text-white border-gray-300 rounded-md shadow-sm bg-white/10 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $project->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('projects.show', $project) }}"
                                class="mr-4 text-sm text-gray-300 hover:text-white">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="p-6 pt-0 border-t md:p-8 border-red-500/30">
                    <h3 class="text-lg font-bold text-red-500">Hapus Proyek</h3>
                    <p class="mt-1 text-sm text-gray-300">
                        Setelah proyek dihapus, semua data terkait akan terhapus permanen. Aksi ini tidak dapat
                        dibatalkan.
                    </p>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <x-danger-button type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus proyek ini secara permanen?')">
                            Hapus Proyek
                        </x-danger-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
