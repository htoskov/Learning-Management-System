<x-app-layout>

    <div class="pt-24 pb-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 mb-24">

{{-- Top stats --}}

{{-- Row 1: Users --}}
<div class="grid grid-cols-3 gap-4">
    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="text-sm text-gray-500 dark:text-gray-400">Registered users</div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $registeredUsers }}
        </div>
    </div>

    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="text-sm text-gray-500 dark:text-gray-400">Paid users</div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $paidUsers }}
        </div>
    </div>

    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="text-sm text-gray-500 dark:text-gray-400">Unpaid users</div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $unpaidUsers }}
        </div>
    </div>
</div>

{{-- Row 2: Revenue --}}
<div class="grid grid-cols-2 gap-4">
    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="text-sm text-gray-500 dark:text-gray-400">Total sales</div>
        <div class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
            {{ $totalSales }}
        </div>
    </div>

    <div class="p-5 bg-white dark:bg-gray-800 rounded-xl shadow">
        <div class="text-sm text-gray-500 dark:text-gray-400">Money generated</div>
        <div class="text-2xl font-semibold tabular-nums text-gray-900 dark:text-gray-100">
            {{ number_format(((int)$moneyGenerated) / 100, 2, ',', ' ') }} EUR
        </div>
    </div>
</div>


            {{-- User stats selector --}}
            <div class="p-6 pb-16 bg-white dark:bg-gray-800 rounded-xl shadow space-y-6">

                <div class="flex items-center justify-between flex-wrap gap-3">
                    <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        User statistics
                    </div>

                    <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2 flex-wrap">
                        <input
                            type="text"
                            name="q"
                            value="{{ $q }}"
                            placeholder="Search name or email..."
                            class="w-64 max-w-full rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        />

                        <select
                            name="user_id"
                            class="w-72 max-w-full rounded-lg border-gray-200 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                        >
                            @foreach($usersList as $u)
                                <option value="{{ $u->id }}" @selected((int)$selectedUserId === (int)$u->id)>
                                    {{ $u->name }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>

                        <button
                            class="px-4 py-2 rounded-lg bg-gray-900 text-white dark:bg-white dark:text-gray-900"
                            type="submit"
                        >
                            View
                        </button>
                    </form>
                </div>

                @if(!$selectedUser)
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        No user selected.
                    </div>
                @else
                    @php
                        $paid = (bool) ($selectedPayment->is_paid ?? false);
                        $spent = (int) ($selectedPayment->amount_total ?? 0);
                        $paidAt = $selectedPayment->paid_at ?? null;

                        // You don't have a "last_active" column in the schema you posted.
                        // Best effort: show users.updated_at as proxy.
                        $lastActive = $selectedUser->updated_at ?? null;
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div class="p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Profile</div>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
                                <div><span class="text-gray-500">Name:</span> <span class="font-medium">{{ $selectedUser->name }}</span></div>
                                <div><span class="text-gray-500">Email:</span> <span class="font-medium">{{ $selectedUser->email }}</span></div>
                                <div>
                                    <span class="text-gray-500">Paid:</span>
                                    @if($paid)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-green-100 text-green-700 dark:bg-green-900/30 dark:text-green-300">YES</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300">NO</span>
                                    @endif
                                </div>
                                <div><span class="text-gray-500">Last active (proxy):</span> <span class="font-medium">{{ $lastActive }}</span></div>
                            </div>
                        </div>

                        <div class="p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Course progress</div>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
                                <div><span class="text-gray-500">Video progress:</span> <span class="font-medium">{{ (int)($selectedUser->video_progress ?? 1) }}</span></div>
                                <div><span class="text-gray-500">Trophies:</span> <span class="font-medium">🏆 {{ (int)($selectedUser->trophies ?? 0) }} / 100</span></div>
                            <div class="pt-3">
                                <button
                                    type="button"
                                    disabled
                                    class="w-full px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-600 dark:text-gray-300 cursor-not-allowed opacity-70"
                                    title="Coming soon"
                                >
                                    🏅 Issue certificate (soon)
                                </button>
                            </div>
                            </div>
                        </div>

                        <div class="p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                            <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">Payments</div>
                            <div class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
                                <div>
                                    <span class="text-gray-500">Spent:</span>
                                    <span class="font-medium tabular-nums text-gray-900 dark:text-gray-100">
                                        {{ number_format(((int)$spent) / 100, 2, ',', ' ') }} EUR
                                    </span>
                                </div>
                                <div><span class="text-gray-500">Paid at:</span> <span class="font-medium">{{ $paidAt ?? '—' }}</span></div>
                                <div><span class="text-gray-500">Type:</span> <span class="font-medium">{{ $selectedPayment->type ?? '—' }}</span></div>
                                <div><span class="text-gray-500">Status:</span> <span class="font-medium">{{ $selectedPayment->status ?? '—' }}</span></div>
                            </div>
                        </div>
                    </div>

                    {{-- Submission review --}}
                    <div class="mt-4 p-5 rounded-xl border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between flex-wrap gap-2 mb-3">
                            <div class="text-sm text-gray-500 dark:text-gray-400">Project submission</div>
                            <div class="text-xs text-gray-400 dark:text-gray-500">
                                Latest submission for this user
                            </div>
                        </div>

                        @if(!$selectedSubmission)
                            <div class="text-sm text-gray-500 dark:text-gray-400">No submission found.</div>
                        @else
                            @php
                                $links = $selectedSubmission->links_json ?? [];
                                if (is_string($links)) {
                                    // In case it's stored as JSON string in DB and not casted
                                    $decoded = json_decode($links, true);
                                    $links = is_array($decoded) ? $decoded : [];
                                }
                            @endphp

                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                <div class="lg:col-span-2">
                                    <div class="text-sm font-medium mb-2">Note</div>
                                    <div class="text-sm text-gray-700 dark:text-gray-200 whitespace-pre-wrap">
                                        {{ $selectedSubmission->note ?? '—' }}
                                    </div>

                                    <div class="mt-4">
                                        <div class="text-sm font-medium mb-2">Links</div>
                                        @if(empty($links))
                                            <div class="text-sm text-gray-500 dark:text-gray-400">—</div>
                                        @else
                                            <ul class="list-disc pl-5 space-y-1 text-sm">
                                                @foreach($links as $url)
                                                    <li>
                                                        <a href="{{ $url }}" target="_blank" class="underline hover:opacity-80">
                                                            {{ $url }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <div class="text-sm font-medium mb-2">Files</div>

                                    @if(($selectedSubmission->files?->count() ?? 0) === 0)
                                        <div class="text-sm text-gray-500 dark:text-gray-400">—</div>
                                    @else
                                        <div class="space-y-2 text-sm text-gray-700 dark:text-gray-200">
                                            @foreach($selectedSubmission->files as $f)
                                                <div class="p-3 rounded-lg bg-gray-50 dark:bg-gray-900 border border-gray-100 dark:border-gray-700">
                                                    <div class="font-medium">
                                                        {{ $f->original_name ?? 'file' }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $f->type }} • {{ $f->mime }} • {{ number_format((int)$f->size/1024, 1) }} KB
                                                    </div>

                                                    @php
                                                        $url = \Illuminate\Support\Facades\Storage::disk('public')->url($f->path);
                                                    @endphp

                                                    <a class="inline-block mt-2 underline" href="{{ $url }}" target="_blank">
                                                        Open / Review
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

        </div>
    </div>
    <div class="h-24"></div>
</x-app-layout>
