                @php
                    $trophies = (int) (auth()->user()->trophies ?? 0);
                    $isComplete = $trophies >= 100;
                @endphp

<nav x-data="{ open: false }" class="absolute inset-x-0 top-0 z-50 bg-transparent">

    <!-- Top Bar: Logo (left) + User (right), no background -->
    <div class="w-full px-6 relative">
        <div class="flex justify-between h-16">

            <!-- Logo -->
            <div class="shrink-0 flex items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-logo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                </a>
            </div>

            @if(request()->routeIs('dashboard'))
            <div
                class="absolute top-1/2 -translate-y-1/2 flex items-center gap-4 transition-all duration-300"
                style="left: calc(var(--sidenav-offset, 30%) + 1.5rem);"
            >

            <div
                x-data="{
                    trophies: {{ $trophies }},
                    delta: 0,
                    showDelta: false,
                    bump: false,
                    init() {
                        window.addEventListener('lms-trophies', (e) => {
                            const t = Number(e?.detail?.trophies ?? this.trophies);
                            const d = Number(e?.detail?.delta ?? 0);

                            this.trophies = t;

                            if (d > 0) {
                                this.delta = d;
                                this.showDelta = true;
                                this.bump = true;

                                // stop bump quickly
                                setTimeout(() => { this.bump = false; }, 220);
                                // fade out +delta
                                setTimeout(() => { this.showDelta = false; }, 1100);
                            }
                        });
                    }
                }"
                class="relative flex items-center gap-2 px-4 py-2 rounded-lg bg-white/70 dark:bg-gray-800/70 backdrop-blur shadow-sm text-sm text-gray-700 dark:text-gray-200"
            >
                <span class="relative">
                    🏆
                    <span
                        x-text="trophies"
                        class="inline-block tabular-nums transition-transform duration-200"
                        :class="bump ? 'scale-110' : 'scale-100'"
                    ></span>
                    / 100

                    <!-- ✅ Green +10 pop -->
                    <span
                        x-show="showDelta"
                        x-text="'+' + delta"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-900"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-5"
                        class="pointer-events-none absolute -right-10 -top-2 text-green-600 dark:text-green-400 font-semibold"
                    ></span>
                </span>

                @if($isComplete)
                    <!-- Green checkmark -->
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 text-green-500"
                        viewBox="0 0 24 24"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M2.25 12a9.75 9.75 0 1119.5 0 9.75 9.75 0 01-19.5 0zm13.36-1.89a.75.75 0 10-1.22-.88l-3.32 4.59-1.46-1.46a.75.75 0 10-1.06 1.06l2.06 2.06a.75.75 0 001.14-.08l3.86-5.29z"
                            clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>

                <!-- 📜 Certificate Container -->
                @if($isComplete)
                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300 text-sm font-medium shadow-sm">
                        📜 {{ __('Издаден сертификат') }}
                    </div>
                @endif
            </div>
            @endif

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-transparent hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                            <div class="flex items-center gap-2">
                                <img
                                    src="{{ auth()->user()->avatarUrl() }}"
                                    alt="Avatar"
                                    class="h-8 w-8 rounded-full object-cover ring-1 ring-gray-200"
                                >
                                <div>{{ Auth::user()->name }}</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Профил') }}
                        </x-dropdown-link>

                        <x-dropdown-link :href="route('submit-work.show')">
                            {{ __('Предай проекта си') }}
                        </x-dropdown-link>

@if($isComplete)
    <x-dropdown-link :href="route('dashboard')">
        {{ __('Виж сертификата си') }}
    </x-dropdown-link>
@else
    <div
        x-data="{ showTip:false }"
        @mouseenter="showTip=true"
        @mouseleave="showTip=false"
        class="relative px-4 py-2 text-sm text-gray-400 dark:text-gray-500 flex items-center justify-between cursor-not-allowed select-none"
    >
        <span>{{ __('Виж сертификата си') }}</span>

        <!-- Lock icon -->
        <svg xmlns="http://www.w3.org/2000/svg"
            class="h-4 w-4 shrink-0"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.5 10.5V8a4.5 4.5 0 10-9 0v2.5M6.75 10.5h10.5a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-1.5 1.5H6.75A1.5 1.5 0 015.25 18v-6a1.5 1.5 0 011.5-1.5z"/>
        </svg>

        <!-- Premium Tooltip -->
        <div
            x-show="showTip"
            x-transition.opacity.scale.duration.150ms
            class="absolute right-full mr-3 top-1/2 -translate-y-1/2 whitespace-nowrap px-3 py-1.5 text-xs rounded-lg shadow-lg
                   bg-gray-900 text-white dark:bg-gray-700 z-50"
        >
            {{ __('Отключва се при успешно преминат курс.🏆 100 / 100') }}
        </div>
    </div>
@endif


                        @if($isComplete)
                            <x-dropdown-link :href="route('dashboard')">
                                {{ __('Запази среща') }}
                            </x-dropdown-link>
                        @else
<div
    x-data="{ showTip:false }"
    @mouseenter="showTip=true"
    @mouseleave="showTip=false"
    class="relative px-4 py-2 text-sm text-gray-400 dark:text-gray-500 flex items-center justify-between cursor-not-allowed select-none"
>
    <span>{{ __('Запази среща') }}</span>

    <!-- Lock icon -->
    <svg xmlns="http://www.w3.org/2000/svg"
        class="h-4 w-4 shrink-0"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="1.8">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M16.5 10.5V8a4.5 4.5 0 10-9 0v2.5M6.75 10.5h10.5a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-1.5 1.5H6.75A1.5 1.5 0 015.25 18v-6a1.5 1.5 0 011.5-1.5z"/>
    </svg>

    <!-- ✨ Premium Tooltip -->
    <div
        x-show="showTip"
        x-transition.opacity.scale.duration.150ms
        class="absolute right-full mr-3 top-1/2 -translate-y-1/2 whitespace-nowrap px-3 py-1.5 text-xs rounded-lg shadow-lg
               bg-gray-900 text-white dark:bg-gray-700 z-50"
    >
        {{ __('Отключва се при успешно преминат курс.🏆 100 / 100') }}
    </div>
</div>

                        @endif

                        <div class="border-t border-gray-200 dark:border-gray-600 my-1"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Излез') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger (mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile dropdown (no extra nav links) -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Submit Your Work') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('dashboard')">
                    {{ __('View Certificate') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('dashboard')">
                    {{ __('Book Meeting') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
