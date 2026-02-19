<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center pt-24 pb-10">
        <div class="w-full max-w-xl px-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8">
                    <div class="flex items-start justify-between gap-4">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                Get Lifetime Access
                            </h1>
                            <p class="mt-2 text-gray-600 dark:text-gray-300">
                                You’re registered, but you don’t have access yet. Complete payment to unlock the course.
                            </p>
                        </div>

                        <div class="shrink-0 px-3 py-1.5 rounded-full text-xs font-semibold
                                    bg-gray-100 text-gray-700
                                    dark:bg-gray-700 dark:text-gray-200">
                            Lifetime
                        </div>
                    </div>

                    <div class="mt-6 grid gap-3">
                        <div class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-200">
                            <span class="text-green-600 dark:text-green-400">✓</span>
                            Full course access
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-200">
                            <span class="text-green-600 dark:text-green-400">✓</span>
                            Certificate after completion
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-700 dark:text-gray-200">
                            <span class="text-green-600 dark:text-green-400">✓</span>
                            Progress tracking + trophies
                        </div>
                    </div>

                    <div class="mt-8">
                        <form method="POST" action="{{ route('checkout.create') }}"
                              onsubmit="this.querySelector('button').disabled=true; this.querySelector('button').innerText='Redirecting to Stripe…';">
                            @csrf

                            <button type="submit"
                                class="w-full py-3.5 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90
                                       dark:bg-white dark:text-gray-900 transition">
                                Pay Now
                            </button>
                        </form>

                        <div class="mt-4 text-xs text-gray-500 dark:text-gray-400 text-center">
                            Secure payment powered by Stripe.
                        </div>
                    </div>
                </div>

                <div class="px-8 py-4 bg-gray-50 dark:bg-gray-900/40 border-t border-gray-100 dark:border-gray-700">
                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        If you already paid and still see this page, refresh once or open the dashboard.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
