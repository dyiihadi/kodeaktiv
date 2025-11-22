<x-app-layout>
    <style>
        .glass-panel {
            background: rgba(31, 41, 55, 0.5);
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

                        {{-- Grid untuk Tanggal Mulai & Tenggat --}}
                        <div class="grid grid-cols-1 gap-4 mt-4 md:grid-cols-2">
                            <div>
                                <x-input-label for="start_date" :value="__('Tanggal Mulai')" class="text-white" />
                                <x-text-input id="start_date" class="block w-full mt-1" type="date" name="start_date"
                                    :value="old('start_date')" />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="due_date" :value="__('Tenggat Waktu (Deadline)')" class="text-white" />
                                <x-text-input id="due_date" class="block w-full mt-1" type="date" name="due_date"
                                    :value="old('due_date')" />
                                <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
                            </div>
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
