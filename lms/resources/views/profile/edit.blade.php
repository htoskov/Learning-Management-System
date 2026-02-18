<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Профил') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Снимка на профил</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Качете снимка във следният формат (JPG/PNG/WEBP, максимален размер 2MB).</p>
                </header>

                <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                    @csrf

                    <div class="flex items-center gap-4">
                        <img
                            src="{{ auth()->user()->avatarUrl() }}"
                            alt="Profile photo"
                            class="h-14 w-14 rounded-full object-cover ring-1 ring-gray-200"
                        >

<div x-data="{ fileName: '{{ __('Няма избран файл') }}' }" class="space-y-2">

    <!-- Hidden real input -->
    <input
        id="avatar"
        type="file"
        name="avatar"
        accept="image/*"
        class="hidden"
        @change="fileName = $event.target.files[0]?.name || '{{ __('Няма избран файл') }}'"
        required
    >

    <!-- Custom button -->
    <label
        for="avatar"
        class="inline-flex items-center gap-3 cursor-pointer text-sm text-gray-700 dark:text-gray-300 px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition"
    >
        📎 {{ __('Избери файл') }}
    </label>

    <!-- File name text -->
    <div class="text-sm text-gray-500 dark:text-gray-400" x-text="fileName"></div>

    @error('avatar')
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>

                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Качи</x-primary-button>

                        @if (session('status') === 'avatar-updated')
                            <p class="text-sm text-gray-600 dark:text-gray-300">Запазено.</p>
                        @endif
                    </div>
                </form>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
