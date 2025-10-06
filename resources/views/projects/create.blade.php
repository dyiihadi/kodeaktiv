<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Buat Proyek Baru</h2>
    </x-slot>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('projects.store') }}">
                        @csrf
                        <div>
                            <x-input-label for="name" value="Nama Proyek" />
                            <x-text-input id="name" class="block w-full mt-1" type="text" name="name"
                                required />
                        </div>
                        <div class="mt-4">
                            <x-input-label for="description" value="Deskripsi" />
                            <textarea id="description" name="description" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>Simpan Proyek</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
