@php
    $maxUnlocked = (int) (auth()->user()->video_progress ?? 1);
@endphp

<x-app-layout>
    {{-- No header slot: we want the main area to be the working layout --}}

    <div class="h-screen overflow-hidden">
            <div
x-data="{
    sideOpen: true,

    init() {
        window.lmsSideOpen = this.sideOpen;
        this.$watch('sideOpen', v => window.lmsSideOpen = v);
        window.dispatchEvent(new CustomEvent('lms-sideopen', { detail: this.sideOpen }));
    },

    selectedVideo: {{ $maxUnlocked }},
    maxUnlocked: {{ $maxUnlocked }},
    justUnlocked: null,
    sparkle: false,

    applyProgress(newProgress) {
        const prev = this.maxUnlocked;
        this.maxUnlocked = newProgress ?? this.maxUnlocked;

        if (newProgress && newProgress > prev) {
            this.justUnlocked = newProgress;
            this.sparkle = true;

            setTimeout(() => { this.sparkle = false; }, 900);
            setTimeout(() => { this.justUnlocked = null; }, 1600);
        }
    }
}"
x-init="init(); window.lmsApplyProgress = (p) => applyProgress(p)"
                x-effect="document.documentElement.style.setProperty('--sidenav-offset', sideOpen ? '30%' : '6rem')"
                class="flex min-h-screen"
            >
            <!-- Side Navigation (30%) -->
            <aside
                :class="sideOpen ? 'w-[30%]' : 'w-24'"
                class="relative bg-white dark:bg-gray-800 border-r border-gray-100 dark:border-gray-700 transition-[width] duration-300 overflow-hidden"
            >
            <button
                type="button"
                @click="
                        sideOpen = !sideOpen;
                        window.dispatchEvent(new CustomEvent('lms-sideopen', { detail: sideOpen }));
                    "
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
                    Видео уроци
                </div>
                <div class="space-y-3">
                    @for ($i = 1; $i <= 8; $i++)
<div class="relative">
    <!-- Sparkle burst behind -->
    <div
        x-show="sparkle && justUnlocked === {{ $i }}"
        x-transition.opacity.duration.150ms
        class="pointer-events-none absolute inset-0 -z-10 flex items-center justify-center"
    >
        <div class="sparkle-burst"></div>
    </div>

    <button
        type="button"
        @click="
            if ({{ $i }} <= maxUnlocked) {
                selectedVideo = {{ $i }};
                window.lmsLoadVideo && window.lmsLoadVideo({{ $i }});
            }
        "
        :disabled="{{ $i }} > maxUnlocked"
        class="relative w-full px-5 py-4 rounded-lg text-left flex items-center justify-between transition overflow-hidden"
        :class="(
            {{ $i }} <= maxUnlocked
                ? (selectedVideo === {{ $i }}
                    ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white'
                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700')
                : 'bg-gray-100 dark:bg-gray-800/60 text-gray-400 dark:text-gray-500 border border-gray-100 dark:border-gray-700 cursor-not-allowed opacity-70'
        ) + (justUnlocked === {{ $i }} ? ' animate-pulse' : '')"
    >
        <span
            class="absolute left-0 top-0 h-full w-1 rounded-l-lg"
            :class="(selectedVideo === {{ $i }} && {{ $i }} <= maxUnlocked) ? 'bg-gray-900 dark:bg-white' : 'bg-transparent'"
        ></span>

        <span class="text-sm font-medium">Video {{ $i }}</span>
        <svg
            x-show="{{ $i }} > maxUnlocked"
            xmlns="http://www.w3.org/2000/svg"
            class="h-5 w-5 shrink-0"
            viewBox="0 0 24 24"
            fill="none"
            stroke="currentColor"
            stroke-width="1.8"
        >
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M16.5 10.5V8a4.5 4.5 0 10-9 0v2.5M6.75 10.5h10.5a1.5 1.5 0 011.5 1.5v6a1.5 1.5 0 01-1.5 1.5H6.75A1.5 1.5 0 015.25 18v-6a1.5 1.5 0 011.5-1.5z"/>
        </svg>
    </button>
</div>

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
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6"
                                        viewBox="0 0 24 24"
                                        fill="currentColor">
                                        <path d="M20.317 4.369a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.211.375-.444.864-.608 1.249a18.27 18.27 0 00-5.487 0c-.164-.385-.405-.874-.617-1.249a.077.077 0 00-.079-.037 19.736 19.736 0 00-4.885 1.515.07.07 0 00-.032.027C.533 9.045-.32 13.579.099 18.057a.082.082 0 00.031.056 19.9 19.9 0 005.993 3.03.078.078 0 00.084-.027c.462-.63.873-1.295 1.226-1.994a.076.076 0 00-.041-.105 13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128c.126-.094.252-.192.372-.291a.074.074 0 01.077-.01c3.927 1.793 8.18 1.793 12.061 0a.074.074 0 01.078.01c.12.099.246.197.372.291a.077.077 0 01-.006.128 12.299 12.299 0 01-1.873.892.077.077 0 00-.041.106c.36.698.771 1.363 1.225 1.993a.076.076 0 00.084.028 19.876 19.876 0 005.994-3.03.077.077 0 00.031-.056c.5-5.177-.838-9.674-3.548-13.66a.061.061 0 00-.031-.028zM8.02 15.331c-1.182 0-2.156-1.085-2.156-2.419 0-1.333.955-2.418 2.156-2.418 1.21 0 2.175 1.095 2.156 2.418 0 1.334-.955 2.419-2.156 2.419zm7.975 0c-1.182 0-2.156-1.085-2.156-2.419 0-1.333.955-2.418 2.156-2.418 1.21 0 2.175 1.095 2.156 2.418 0 1.334-.946 2.419-2.156 2.419z"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Working Area (70%) -->
            <main class="flex-1 bg-gray-50 dark:bg-gray-900 pt-10">
                <div class="p-4 sm:p-6">

@php
    // Temporary test video (public Vimeo example commonly used in docs)
    $vimeoId = 76979871;
    $currentVideo = (int) (auth()->user()->video_progress ?? 1);
    $resumeSeconds = (int) (auth()->user()->video_time_seconds ?? 0);
@endphp

<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex items-center justify-between mb-4">
            <div class="text-lg font-semibold">Video <span x-text="selectedVideo"></span></div>
        </div>

        <div class="relative w-full" style="padding-top: 56.25%;">
            <iframe
                id="vimeoPlayer"
                data-vimeo-id="{{ $vimeoId }}"
                class="absolute inset-0 w-full h-full rounded-lg"
                src="https://player.vimeo.com/video/{{ $vimeoId }}?title=0&byline=0&portrait=0&dnt=1"
                frameborder="0"
                allow="autoplay; fullscreen; picture-in-picture"
                allowfullscreen
            ></iframe>
        </div>

        <div class="mt-4 text-sm text-gray-500 dark:text-gray-400">
            Материали и допълнителни инструменти:
        </div>
    </div>
</div>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://player.vimeo.com/api/player.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const iframe = document.getElementById('vimeoPlayer');
    if (!iframe || !window.Vimeo) return;

    const player = new Vimeo.Player(iframe);

    window.lmsLoadVideo = async (videoNum) => {
        try {
            maxWatched = 0;
            lastSavedAt = 0;

            isProgrammaticSeek = true;
            await player.setCurrentTime(0);
            await player.play();
            isProgrammaticSeek = false;

            console.log('Switched to video', videoNum);
        } catch (e) {
            isProgrammaticSeek = false;
        }
    };

    const COURSE_TOTAL_VIDEOS = 8;              // your course length
    function getSelectedVideoNumber() {
        return Number(document.querySelector('[x-data]')?.__x?.$data?.selectedVideo || {{ $currentVideo }});
    }
    const RESUME_SECONDS = {{ $resumeSeconds }};

    // Heartbeat settings
    const HEARTBEAT_MS = 10000; // 12s (good default)
    const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    let duration = 0;
    let lastSavedAt = 0;

    // For "no fast-forward" best-effort:
    // track the furthest point legitimately watched
    let maxWatched = Math.max(0, RESUME_SECONDS);
    let isProgrammaticSeek = false;

    function clamp(n, min, max) { return Math.max(min, Math.min(max, n)); }

    async function postProgress(payload) {
        const res = await fetch('{{ route('progress.update') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        try { return await res.json(); } catch (e) { return null; }
    }

    async function saveProgress(seconds, completed = false) {
        const now = Date.now();
        if (!completed && (now - lastSavedAt) < HEARTBEAT_MS) return;
        lastSavedAt = now;

        const safeSeconds = clamp(Math.floor(seconds || 0), 0, Math.floor(duration || 0));

        let result = null;

        try {
            result = await postProgress({
                video_number: getSelectedVideoNumber(),
                seconds: safeSeconds,
                duration: Math.floor(duration || 0),
                completed: completed ? 1 : 0,
            });

            if (result && typeof result.trophies_awarded === 'number' && result.trophies_awarded > 0) {
                window.dispatchEvent(new CustomEvent('lms-trophies', {
                    detail: {
                        trophies: result.trophies,
                        delta: result.trophies_awarded
                    }
                }));
            }

            if (completed && window.lmsApplyProgress) {
                const newProgress = result?.video_progress;

                // If API didn’t return progress, do a safe fallback unlock (+1) for UI only
                if (typeof newProgress === 'number') {
                    window.lmsApplyProgress(newProgress);
                } else {
                    window.lmsApplyProgress(VIDEO_NUMBER + 1);
                }
            }

        } catch (e) {
            console.warn('Progress save failed', e);
        }
    }

    // Load duration + resume
    player.getDuration().then(d => { duration = d || 0; }).catch(() => {});
    player.ready().then(async () => {
        // Resume (best effort)
        if (RESUME_SECONDS > 0) {
            isProgrammaticSeek = true;
            try { await player.setCurrentTime(RESUME_SECONDS); } catch (e) {}
            isProgrammaticSeek = false;
        }
    });

    // Track watched time
    player.on('timeupdate', (data) => {
        // data: {seconds, percent, duration} :contentReference[oaicite:1]{index=1}
        if (typeof data.duration === 'number') duration = data.duration;

        const s = data.seconds || 0;

        // update max watched naturally
        if (s > maxWatched) maxWatched = s;

        // heartbeat save while playing
        saveProgress(s, false);
    });

    // Save on pause
    player.on('pause', (data) => {
        saveProgress(data.seconds || 0, false);
    });

    // Completion
    player.on('ended', () => {
        // treat ended as completed
        saveProgress(duration || maxWatched || 0, true);
    });

    // Best-effort anti fast-forward:
    // If user seeks beyond maxWatched + small buffer, snap back.
    // Note: cannot be perfect; users can still cheat in some ways.
    player.on('seeked', async (data) => { // seeked event exists :contentReference[oaicite:2]{index=2}
        if (isProgrammaticSeek) return;

        const to = data.seconds || 0;
        const buffer = 2; // allow tiny forward nudge
        if (to > (maxWatched + buffer)) {
            isProgrammaticSeek = true;
            try { await player.setCurrentTime(maxWatched); } catch (e) {}
            isProgrammaticSeek = false;
        }
    });

    // Save progress when leaving page (best effort)
    window.addEventListener('beforeunload', async () => {
        try {
            const t = await player.getCurrentTime();
            // navigator.sendBeacon could be used; MVP uses fetch best-effort
            await postProgress({
                video_number: getSelectedVideoNumber(),
                seconds: Math.floor(t || 0),
                duration: Math.floor(duration || 0),
                completed: 0,
            });
        } catch (e) {}
    });
});
</script>

                </div>
            </main>
        </div>
    </div>
</x-app-layout>

<style>
/* simple burst using box-shadow dots */
.sparkle-burst {
  width: 10px;
  height: 10px;
  border-radius: 9999px;
  opacity: 0.9;
  animation: sparkleBurst 0.9s ease-out forwards;
  box-shadow:
    0 -18px 0 0 rgba(255,255,255,0.9),
    12px -12px 0 0 rgba(255,255,255,0.75),
    18px 0 0 0 rgba(255,255,255,0.8),
    12px 12px 0 0 rgba(255,255,255,0.65),
    0 18px 0 0 rgba(255,255,255,0.75),
    -12px 12px 0 0 rgba(255,255,255,0.6),
    -18px 0 0 0 rgba(255,255,255,0.8),
    -12px -12px 0 0 rgba(255,255,255,0.65);
  filter: blur(0.2px);
}

@keyframes sparkleBurst {
  0%   { transform: scale(0.2); opacity: 0; }
  20%  { opacity: 1; }
  100% { transform: scale(1.7); opacity: 0; }
}

/* Dark mode: slightly less bright sparkles */
@media (prefers-color-scheme: dark) {
  .sparkle-burst {
    box-shadow:
      0 -18px 0 0 rgba(255,255,255,0.75),
      12px -12px 0 0 rgba(255,255,255,0.6),
      18px 0 0 0 rgba(255,255,255,0.65),
      12px 12px 0 0 rgba(255,255,255,0.55),
      0 18px 0 0 rgba(255,255,255,0.6),
      -12px 12px 0 0 rgba(255,255,255,0.5),
      -18px 0 0 0 rgba(255,255,255,0.65),
      -12px -12px 0 0 rgba(255,255,255,0.55);
  }
}
</style>
