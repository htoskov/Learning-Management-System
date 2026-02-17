@php
    $maxUnlocked = (int) (auth()->user()->video_progress ?? 1);
@endphp

<x-app-layout>
    {{-- No header slot: we want the main area to be the working layout --}}

    <div class="min-h-screen">
        <div
            x-data="{
                sideOpen: true,
                selectedVideo: {{ $maxUnlocked }},
                maxUnlocked: {{ $maxUnlocked }},
            }"
            class="flex min-h-screen"
        >
            <!-- Side Navigation (30%) -->
            <aside
                :class="sideOpen ? 'w-[30%]' : 'w-24'"
                class="relative bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 transition-[width] duration-300 overflow-hidden"
            >
            <button
                type="button"
                @click="sideOpen = !sideOpen"
                class="absolute top-1/2 -right-3 -translate-y-1/2 z-10 h-8 w-8 shadow flex items-center justify-center text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                aria-label="Toggle sidebar"
            >
                <!-- When open: show left arrow -->
                <svg x-show="sideOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>

                <!-- When closed: show right arrow -->
                <svg x-show="!sideOpen" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
                <div class="h-full flex flex-col">
            <!-- Tutorials (start below top nav, hide when collapsed) -->
            <div x-show="sideOpen" x-transition class="px-6 pt-16 pb-6">
                <div class="text-xs font-semibold tracking-wider text-gray-500 dark:text-gray-400 mb-3 uppercase">
                    Tutorials
                </div>
                <div class="space-y-3">
                    @for ($i = 1; $i <= 8; $i++)
                        <button
                            type="button"
                            @click="if ({{ $i }} <= maxUnlocked) selectedVideo = {{ $i }}"
                            :disabled="{{ $i }} > maxUnlocked"
                            class="relative w-full px-5 py-4 rounded-lg text-left flex items-center justify-between transition overflow-hidden"
                            :class="{{ $i }} <= maxUnlocked
                                ? (selectedVideo === {{ $i }}
                                    ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
                                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700')
                                : 'bg-gray-100 dark:bg-gray-800/60 text-gray-400 dark:text-gray-500 border border-gray-100 dark:border-gray-700 cursor-not-allowed opacity-70'"
                        >
                            <!-- Left accent bar (only if unlocked + selected) -->
                            <span
                                class="absolute left-0 top-0 h-full w-1 rounded-l-lg"
                                :class="(selectedVideo === {{ $i }} && {{ $i }} <= maxUnlocked) ? 'bg-gray-900 dark:bg-white' : 'bg-transparent'"
                            ></span>

                            <span class="text-sm font-medium">Video {{ $i }}</span>

                            @if ($i > $maxUnlocked)
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5 shrink-0"
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.5 10.5V8a4.5 4.5 0 10-9 0v2.5M6.75 10.5h10.5a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-1.5 1.5H6.75A1.5 1.5 0 015.25 18v-6a1.5 1.5 0 011.5-1.5z"/>
                                </svg>
                            @endif
                        </button>
                    @endfor
                </div>
            </div>
                    <!-- Spacer -->
                    <div class="flex-1"></div>

                    <!-- Sidenav bottom -->
                    <div class="p-6 border-t border-gray-100 dark:border-gray-700">
                        <div x-show="sideOpen" class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400">

                            <!-- Left: Copyright -->
                            <div>
                                © LMS Systems 2026
                            </div>

                            <!-- Right: Social Icons -->
                            <div class="flex items-center gap-4">
                                <!-- Instagram -->
                                <a href="#" class="hover:text-gray-900 dark:hover:text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M7 2C4.2 2 2 4.2 2 7v10c0 2.8 2.2 5 5 5h10c2.8 0 5-2.2 5-5V7c0-2.8-2.2-5-5-5H7zm5 5a5 5 0 110 10 5 5 0 010-10zm6.5-.9a1.1 1.1 0 11-2.2 0 1.1 1.1 0 012.2 0z"/>
                                    </svg>
                                </a>

                                <!-- TikTok -->
                                <a href="#" class="hover:text-gray-900 dark:hover:text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M16 2c.3 2.1 1.7 3.7 3.8 4v3.1c-1.3 0-2.5-.4-3.6-1v6.3a5.4 5.4 0 11-4.6-5.3v3.2a2.2 2.2 0 102.4 2.2V2h2z"/>
                                    </svg>
                                </a>

                                <!-- Discord -->
                                <a href="#" class="hover:text-gray-900 dark:hover:text-white transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M20 4a16 16 0 00-4-1l-.2.4a14 14 0 00-7.6 0L8 3a16 16 0 00-4 1C1.5 8.2 1 12.2 1.2 16.1a16 16 0 004.8 2.4l1-1.6a10 10 0 01-1.6-.8l.3-.2c3 1.4 6.3 1.4 9.3 0l.3.2c-.5.3-1 .6-1.6.8l1 1.6a16 16 0 004.8-2.4C23 12.2 22.5 8.2 20 4zM9.5 13.5a1.5 1.5 0 110-3 1.5 1.5 0 010 3zm5 0a1.5 1.5 0 110-3 1.5 1.5 0 010 3z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Working Area (70%) -->
            <main class="flex-1 bg-gray-50 dark:bg-gray-900 pt-16">
                <div class="p-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("You're logged in!") }}
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</x-app-layout>
