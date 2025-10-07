<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Proyek') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('projects.update', $project) }}">
                        @csrf
                        @method('PATCH')
                        <div>
                            <x-input-label for="name" :value="__('Nama Proyek')" />
                            <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                                :value="old('name', $project->name)" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" />
                            <textarea id="description" name="description"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $project->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('projects.show', $project) }}"
                                class="mr-4 text-sm text-gray-600 hover:text-gray-900">
                                Batal
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>

                <div class="p-6 pt-0 border-t border-red-300">
                    <h3 class="text-lg font-bold text-red-700">Hapus Proyek</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Setelah proyek dihapus, semua data terkait seperti tugas dan file akan terhapus
                        permanen. Aksi ini tidak dapat dibatalkan.
                    </p>
                    <form method="POST" action="{{ route('projects.destroy', $project) }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 font-bold text-white bg-red-600 rounded hover:bg-red-700"
                            onclick="return confirm('Apakah Anda yakin ingin menghapus proyek ini secara permanen?')">
                            Hapus Proyek
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
