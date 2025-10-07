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
            {{ __('Buat Proyek Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm glass-panel sm:rounded-lg">
                <div class="p-6 md:p-8">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf

                        <div>
                            <x-input-label for="name" :value="__('Nama Proyek')" class="text-white" />
                            <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                                :value="old('name')" required autofocus />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Deskripsi Singkat')" class="text-white" />
                            <textarea id="description" name="description"
                                class="block w-full mt-1 text-white border-gray-300 rounded-md shadow-sm bg-white/10 focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mt-4">
                            <x-input-label for="due_date" :value="__('Tenggat Waktu (Opsional)')" class="text-white" />
                            <x-text-input id="due_date" class="block w-full mt-1" type="date" name="due_date"
                                :value="old('due_date')" />
                            <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('projects.index') }}" class="mr-4 text-sm text-gray-300 hover:text-white">
                                Batal
                            </a>

                            <x-primary-button>
                                {{ __('Simpan Proyek') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
