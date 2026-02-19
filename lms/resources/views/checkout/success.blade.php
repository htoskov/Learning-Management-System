<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 flex items-center justify-center pt-24 pb-10">
        <div class="w-full max-w-xl px-4">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-8 text-center">
                    <div class="mx-auto w-12 h-12 rounded-full flex items-center justify-center
                                bg-green-50 text-green-700
                                dark:bg-green-900/30 dark:text-green-300">
                        ✓
                    </div>

                    <h1 class="mt-4 text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Payment received
                    </h1>

                    <p class="mt-2 text-gray-600 dark:text-gray-300" id="statusText">
                        Verifying payment and unlocking access…
                    </p>

                    <div class="mt-6">
                        <div class="inline-flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                            <span class="inline-block h-4 w-4 rounded-full border-2 border-gray-300 dark:border-gray-600 border-t-transparent animate-spin"></span>
                            Please keep this page open for a few seconds.
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('dashboard') }}"
                           class="px-6 py-3 rounded-xl bg-gray-900 text-white font-semibold hover:opacity-90
                                  dark:bg-white dark:text-gray-900 transition">
                            Go to Dashboard
                        </a>
                    </div>

                    <div class="mt-5 text-xs text-gray-400" id="hintText" style="display:none;">
                        Still processing. If this takes longer than 20–30 seconds, refresh or contact support.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
            (function () {
                const statusUrl = "{{ route('payment.status') }}";
                const dashboardUrl = "{{ route('dashboard') }}";

                let attempts = 0;
                const maxAttempts = 30;

                const statusText = document.getElementById('statusText');
                const hintText = document.getElementById('hintText');

                async function checkPaid() {
                    attempts++;

                    try {
                        const res = await fetch(statusUrl, {
                            method: "GET",
                            headers: { "Accept": "application/json" },
                            credentials: "same-origin",
                        });

                        if (res.ok) {
                            const data = await res.json();
                            if (data.paid) {
                                statusText.textContent = "Access granted ✅ Redirecting to your dashboard…";
                                window.location.href = dashboardUrl;
                                return;
                            }
                        }
                    } catch (e) {}

                    if (attempts >= maxAttempts) {
                        statusText.textContent = "Still verifying… please click “Go to Dashboard” in a moment.";
                        hintText.style.display = "block";
                        return;
                    }

                    setTimeout(checkPaid, 1000);
                }

                checkPaid();
            })();
    </script>
</x-app-layout>
