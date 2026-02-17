<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <header>
                    <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Profile Photo</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-300">Upload a profile picture (JPG/PNG/WEBP, max 2MB).</p>
                </header>

                <form method="post" action="{{ route('profile.avatar') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                    @csrf

                    <div class="flex items-center gap-4">
                        <img
                            src="{{ auth()->user()->avatarUrl() }}"
                            alt="Profile photo"
                            class="h-14 w-14 rounded-full object-cover ring-1 ring-gray-200"
                        >

                        <div>
                            <input
                                type="file"
                                name="avatar"
                                accept="image/*"
                                class="block w-full text-sm text-gray-700 dark:text-gray-300 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-gray-700 hover:file:bg-gray-200 dark:file:bg-gray-700 dark:file:text-gray-100 dark:hover:file:bg-gray-600"
                                required
                            >
                            @error('avatar')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Upload</x-primary-button>

                        @if (session('status') === 'avatar-updated')
                            <p class="text-sm text-gray-600 dark:text-gray-300">Saved.</p>
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
