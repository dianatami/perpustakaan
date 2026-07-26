@php
    $guruItems = $leaderboardGuru ?? collect();
    $siswaItems = $leaderboardSiswa ?? collect();
    $user = auth()->user();
    $isAdmin = $user && $user->role == \App\Models\User::ROLE_ADMIN;
    $isGuru = $user && $user->role == \App\Models\User::ROLE_GURU;
    $isSiswa = $user && $user->role == \App\Models\User::ROLE_ANGGOTA;
    
    // Adjust layout columns based on what's shown
    $showGuru = $isAdmin || $isGuru;
    $showSiswa = $isAdmin || $isSiswa;
    $gridCols = ($showGuru && $showSiswa) ? 'lg:grid-cols-2 lg:divide-x' : 'lg:grid-cols-1';
@endphp

<section class="relative mt-6 rounded-3xl border border-slate-200/80 bg-white shadow-[0_18px_35px_rgba(16,23,46,0.05)] overflow-hidden">
    <div class="border-b border-slate-100 bg-slate-50/50 px-5 py-4 sm:px-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h3 class="font-display text-lg font-bold text-slate-900">Leaderboard Peminjaman Bulan Ini</h3>
                <p class="mt-1 text-sm text-slate-500">Statistik prestasi membaca pengguna aktif ({{ now()->translatedFormat('F Y') }})</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 divide-y divide-slate-100 {{ $gridCols }} lg:divide-y-0">
        <!-- Bagian Guru -->
        @if($showGuru)
        <div class="p-5 sm:p-6">
            @if($isAdmin)
            <div class="mb-5 flex flex-wrap gap-4 rounded-2xl bg-indigo-50/50 p-4 border border-indigo-100/50">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-500">Total Guru Aktif</p>
                    <p class="mt-1 text-xl font-bold text-indigo-900">{{ $totalGuruAktif ?? 0 }}</p>
                </div>
                <div class="w-px bg-indigo-200/50"></div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-indigo-500">Total Peminjaman Guru</p>
                    <p class="mt-1 text-xl font-bold text-indigo-900">{{ $totalPeminjamanGuruLb ?? 0 }}</p>
                </div>
            </div>
            @endif
            
            <h4 class="mb-4 text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-mortarboard-fill text-indigo-500"></i> Top 10 Guru
            </h4>

            <div class="overflow-x-auto rounded-xl border border-slate-200/60">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-3 border-b border-slate-200/60 w-16">Rank</th>
                            <th class="px-4 py-3 border-b border-slate-200/60">Nama Guru</th>
                            <th class="px-4 py-3 border-b border-slate-200/60 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($guruItems as $index => $item)
                        @php
                            $isMe = $user && $user->id === $item->id;
                            $rowBg = $isMe ? 'bg-indigo-50/80' : 'hover:bg-slate-50/50';
                        @endphp
                        <tr class="{{ $rowBg }} transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full {{ $index < 3 ? 'bg-amber-100 text-amber-700 font-bold' : 'bg-slate-100 text-slate-600' }} text-xs">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium {{ $isMe ? 'text-indigo-900' : 'text-slate-900' }}">
                                {{ $item->nama }}
                                @if($isMe)
                                    <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-indigo-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-indigo-700">
                                        <i class="bi bi-star-fill text-amber-500"></i> Saya
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-right font-bold {{ $isMe ? 'text-indigo-700' : 'text-indigo-600' }}">{{ $item->total_peminjaman }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-sm text-slate-400">Belum ada data peminjaman guru bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($isGuru && isset($myRankGuru) && !$guruItems->contains('id', $user->id))
            <div class="mt-4 overflow-hidden rounded-xl border border-indigo-200 bg-indigo-50/50">
                <div class="bg-indigo-100/50 px-4 py-2">
                    <p class="text-xs font-extrabold uppercase tracking-wider text-indigo-600">Peringkat Anda</p>
                </div>
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-indigo-200 font-bold text-indigo-800 text-sm">
                            {{ $myRankGuru }}
                        </span>
                        <span class="font-bold text-indigo-900">{{ $user->nama }}</span>
                    </div>
                    <span class="font-bold text-indigo-700">{{ $myTotalPeminjamanGuru ?? 0 }} Peminjaman</span>
                </div>
            </div>
            @endif
        </div>
        @endif

        <!-- Bagian Siswa -->
        @if($showSiswa)
        <div class="p-5 sm:p-6">
            @if($isAdmin)
            <div class="mb-5 flex flex-wrap gap-4 rounded-2xl bg-teal-50/50 p-4 border border-teal-100/50">
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Total Siswa Aktif</p>
                    <p class="mt-1 text-xl font-bold text-teal-900">{{ $totalSiswaAktif ?? 0 }}</p>
                </div>
                <div class="w-px bg-teal-200/50"></div>
                <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-wider text-teal-600">Total Peminjaman Siswa</p>
                    <p class="mt-1 text-xl font-bold text-teal-900">{{ $totalPeminjamanSiswaLb ?? 0 }}</p>
                </div>
            </div>
            @endif

            <h4 class="mb-4 text-sm font-bold text-slate-800 flex items-center gap-2">
                <i class="bi bi-people-fill text-teal-500"></i> Top 10 Siswa
            </h4>

            <div class="overflow-x-auto rounded-xl border border-slate-200/60">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50/80 text-xs font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-4 py-3 border-b border-slate-200/60 w-16">Rank</th>
                            <th class="px-4 py-3 border-b border-slate-200/60">Nama Siswa</th>
                            <th class="px-4 py-3 border-b border-slate-200/60">Kelas (NISN)</th>
                            <th class="px-4 py-3 border-b border-slate-200/60 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 bg-white">
                        @forelse ($siswaItems as $index => $item)
                        @php
                            $isMe = $user && $user->id === $item->id;
                            $rowBg = $isMe ? 'bg-teal-50/80' : 'hover:bg-slate-50/50';
                        @endphp
                        <tr class="{{ $rowBg }} transition-colors">
                            <td class="px-4 py-3">
                                <span class="inline-flex h-6 w-6 items-center justify-center rounded-full {{ $index < 3 ? 'bg-amber-100 text-amber-700 font-bold' : 'bg-slate-100 text-slate-600' }} text-xs">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-4 py-3 font-medium {{ $isMe ? 'text-teal-900' : 'text-slate-900' }}">
                                {{ $item->nama }}
                                @if($isMe)
                                    <span class="ml-2 inline-flex items-center gap-1 rounded-full bg-teal-100 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-teal-700">
                                        <i class="bi bi-star-fill text-amber-500"></i> Saya
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-xs {{ $isMe ? 'text-teal-700' : 'text-slate-500' }}">{{ $item->nisn ?: '-' }}</td>
                            <td class="px-4 py-3 text-right font-bold {{ $isMe ? 'text-teal-700' : 'text-teal-600' }}">{{ $item->total_peminjaman }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-sm text-slate-400">Belum ada data peminjaman siswa bulan ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($isSiswa && isset($myRankSiswa) && !$siswaItems->contains('id', $user->id))
            <div class="mt-4 overflow-hidden rounded-xl border border-teal-200 bg-teal-50/50">
                <div class="bg-teal-100/50 px-4 py-2">
                    <p class="text-xs font-extrabold uppercase tracking-wider text-teal-600">Peringkat Anda</p>
                </div>
                <div class="flex items-center justify-between px-4 py-3">
                    <div class="flex items-center gap-3">
                        <span class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-teal-200 font-bold text-teal-800 text-sm">
                            {{ $myRankSiswa }}
                        </span>
                        <span class="font-bold text-teal-900">{{ $user->nama }}</span>
                    </div>
                    <span class="font-bold text-teal-700">{{ $myTotalPeminjamanSiswa ?? 0 }} Peminjaman</span>
                </div>
            </div>
            @endif
        </div>
        @endif
    </div>
</section>
