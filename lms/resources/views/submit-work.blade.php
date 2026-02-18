<x-app-layout>
    <div class="py-10 pt-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @php
                $isUnlocked = $trophies >= 80;
                $status = $submission->status ?? 'draft';
            @endphp

            <!-- Errors -->
            @if($errors->has('gate'))
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 text-red-700 dark:text-red-200">
                    {{ $errors->first('gate') }}
                </div>
            @endif
<!-- Form -->
<div class="relative">

    <!-- Blur wrapper -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-6 transition
        {{ !$isUnlocked ? 'blur-sm select-none pointer-events-none opacity-70' : '' }}
    ">
        <form method="POST" action="{{ route('submit-work.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Кратко описание (какво направи и кои AI инструменти използва)
                </label>

                <textarea
                    name="note"
                    rows="6"
                    @disabled(!$isUnlocked || $status === 'approved')
                    class="mt-2 w-full rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                >{{ old('note', $submission->note ?? '') }}</textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                    Линк (по желание)
                </label>

                <input
                    type="url"
                    name="link"
                    @disabled(!$isUnlocked || $status === 'approved')
                    class="mt-2 w-full rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                    value="{{ old('link') }}"
                >
            </div>

            <!-- FILE INPUT -->
            <div x-data="{ fileNames: [] }" class="space-y-2">

                <input
                    id="projectFiles"
                    type="file"
                    name="files[]"
                    multiple
                    class="hidden"
                    @disabled(!$isUnlocked || $status === 'approved')
                    @change="fileNames = Array.from($event.target.files).map(f => f.name)"
                >

                <label
                    for="projectFiles"
                    class="inline-flex items-center gap-3 cursor-pointer text-sm px-4 py-2 rounded-lg
                        bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600"
                >
                    📎 Избери файлове
                </label>

                <div class="text-sm text-gray-500 dark:text-gray-400">
                    <template x-if="fileNames.length === 0">
                        <div>Няма избрани файлове</div>
                    </template>

                    <template x-if="fileNames.length > 0">
                        <ul class="list-disc pl-5 space-y-1">
                            <template x-for="n in fileNames" :key="n">
                                <li x-text="n"></li>
                            </template>
                        </ul>
                    </template>
                </div>
            </div>

            @if($submission && $submission->files->count())
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">
                        Качени файлове:
                    </div>

                    @foreach($submission->files as $f)
                        <div class="flex justify-between text-sm">
                            <span>{{ $f->original_name }}</span>
                            <a href="{{ asset('storage/'.$f->path) }}" target="_blank" class="underline">Отвори</a>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="flex items-center gap-4">
                <button
                    type="submit"
                    @disabled(!$isUnlocked || $status === 'approved')
                    class="px-4 py-2 rounded-lg text-sm font-semibold bg-gray-900 text-white hover:bg-gray-800 disabled:opacity-50"
                >
                    Изпрати за преглед
                </button>
            </div>

        </form>
    </div> <!-- END BLUR WRAPPER -->

    <!-- OVERLAY -->
    @if(!$isUnlocked)
        <div class="absolute inset-0 flex items-center justify-center z-10 pointer-events-none">
            <div class="px-8 py-6 rounded-xl bg-white/80 dark:bg-gray-900/80 backdrop-blur shadow-lg text-center max-w-md animate-[fadeIn_.25s_ease]">

                <div class="text-xl font-semibold text-gray-900 dark:text-white">
                    Още малко! 🔒
                </div>

                <p class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                    За да предадеш проекта си трябва да достигнеш
                    <strong>80 / 100 трофея</strong>.
                </p>

                <!-- PROGRESS BAR -->
                <div class="mt-5 w-full">
                    <div class="h-2 rounded-full bg-gray-200 dark:bg-gray-700 overflow-hidden">
                        <div
                            class="h-full bg-gray-900 dark:bg-white transition-all duration-500"
                            style="width: {{ min(100, $trophies) }}%;"
                        ></div>
                    </div>

                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        Прогрес:🏆 {{ $trophies }} / 100
                    </div>
                </div>
            <a
                href="{{ route('dashboard') }}"
                class="mt-5 inline-flex items-center justify-center px-4 py-2 text-sm font-medium rounded-lg
                    bg-gray-900 text-white hover:bg-gray-800 transition pointer-events-auto"
            >
                ← Върни се обратно
            </a>
            </div>
        </div>
    @endif

</div>


</x-app-layout>
<style>
@keyframes fadeIn {
  from { transform: scale(.96); opacity:0; }
  to { transform: scale(1); opacity:1; }
}
</style>