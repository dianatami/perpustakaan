@php
    $leaderboardItems = $leaderboardSiswa ?? collect();
    $totalPeserta = $leaderboardItems->count();
    $totalPeminjamanLeaderboard = (int) $leaderboardItems->sum('total_peminjaman');
    $peminjamanTertinggi = max(1, (int) $leaderboardItems->max('total_peminjaman'));
    $juara = $leaderboardItems->first();
@endphp

<section class="relative mt-4 overflow-hidden rounded-3xl border border-slate-200/80 bg-gradient-to-br from-white/95 via-sky-50/70 to-teal-50/70 p-4 shadow-[0_18px_35px_rgba(16,23,46,0.08)] sm:p-6">
    <div class="pointer-events-none absolute -right-20 -top-20 h-52 w-52 rounded-full bg-orange-300/20 blur-2xl"></div>
    <div class="pointer-events-none absolute -bottom-24 -left-16 h-52 w-52 rounded-full bg-cyan-300/25 blur-2xl"></div>

    <div class="relative z-10 flex flex-wrap items-start justify-between gap-3">
        <div>
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Leaderboard Peminjam</p>
            <h3 class="mt-1 font-display text-lg leading-tight text-slate-900 sm:text-xl">Leaderboard Peminjam (Anggota & Guru)</h3>
            <p class="mt-1 max-w-2xl text-sm text-slate-600">Ranking diperbarui mengikuti total transaksi peminjaman buku terbaru untuk semua pengguna non-admin.</p>
            <p class="mt-2 text-xs font-semibold text-slate-500">Sinkron terakhir: {{ now()->format('d M Y H:i:s') }}</p>
        </div>

            <button
            type="button"
            id="lbx-refresh-btn"
            aria-label="Perbarui leaderboard sekarang"
            class="group inline-flex items-center gap-2 rounded-full border border-teal-300/60 bg-teal-100/70 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.1em] text-teal-900 transition hover:-translate-y-0.5 hover:bg-teal-200/80 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-teal-400/70"
        >
            <i class="bi bi-arrow-repeat lbx-meta-icon text-sm transition-transform duration-500" aria-hidden="true"></i>
                <span class="lbx-meta-text">Segarkan Data</span>
        </button>
    </div>

    <div class="relative z-10 mt-4 grid gap-3 sm:grid-cols-3">
        <article class="rounded-2xl border border-slate-200/80 bg-white/85 px-4 py-3">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-slate-500">Peserta (Anggota & Guru)</p>
            <p class="mt-1 font-display text-2xl text-slate-900">{{ $totalPeserta }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200/80 bg-white/85 px-4 py-3">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-slate-500">Total Peminjaman</p>
            <p class="mt-1 font-display text-2xl text-slate-900">{{ $totalPeminjamanLeaderboard }}</p>
        </article>
        <article class="rounded-2xl border border-slate-200/80 bg-white/85 px-4 py-3">
            <p class="text-[11px] font-extrabold uppercase tracking-[0.14em] text-slate-500">Skor Tertinggi</p>
            <p class="mt-1 font-display text-2xl text-slate-900">{{ $juara->total_peminjaman ?? 0 }}</p>
        </article>
    </div>

    @if ($juara)
        <div class="relative z-10 mt-4 flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-amber-300/70 bg-gradient-to-r from-amber-50 to-orange-50 px-4 py-3">
            <div>
                <p class="text-[11px] font-extrabold uppercase tracking-[0.12em] text-amber-800"><i class="bi bi-trophy-fill"></i> Juara Saat Ini</p>
                <p class="mt-1 text-sm font-bold text-amber-950 sm:text-base">{{ $juara->nama }}</p>
            </div>
            <span class="rounded-full border border-amber-300 bg-white/90 px-3 py-1 text-xs font-extrabold uppercase tracking-[0.1em] text-amber-900">
                {{ $juara->total_peminjaman }} peminjaman
            </span>
        </div>
    @endif

    <div class="relative z-10 mt-4 overflow-hidden rounded-2xl border border-slate-200/80 bg-white/85">
        <div class="max-h-[440px] overflow-y-auto overflow-x-auto">
            <table class="w-full min-w-[760px] border-separate border-spacing-0 text-sm text-slate-700">
                <thead class="bg-slate-100/90 text-xs uppercase tracking-[0.12em] text-slate-600">
                    <tr>
                        <th scope="col" class="sticky top-0 z-10 border-b border-slate-200 px-4 py-3 text-left font-extrabold">Rank</th>
                        <th scope="col" class="sticky top-0 z-10 border-b border-slate-200 px-4 py-3 text-left font-extrabold">Nama</th>
                        <th scope="col" class="sticky top-0 z-10 border-b border-slate-200 px-4 py-3 text-left font-extrabold">Status</th>
                        <th scope="col" class="sticky top-0 z-10 border-b border-slate-200 px-4 py-3 text-left font-extrabold">Progress</th>
                        <th scope="col" class="sticky top-0 z-10 border-b border-slate-200 px-4 py-3 text-right font-extrabold">Total</th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    @forelse ($leaderboardItems as $index => $item)
                        @php
                            $progress = (int) round(((int) $item->total_peminjaman / $peminjamanTertinggi) * 100);
                            $isTopThree = $index < 3;
                            $medalIcon = $index === 0 ? 'bi-trophy-fill' : ($index === 1 ? 'bi-award-fill' : ($index === 2 ? 'bi-star-fill' : 'bi-dot'));
                        @endphp
                        <tr class="odd:bg-white even:bg-slate-50/55 hover:bg-cyan-50/40">
                            <td class="border-b border-slate-100 px-4 py-3">
                                <div class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-extrabold {{ $isTopThree ? 'bg-gradient-to-r from-orange-500 to-amber-400 text-white shadow-sm' : 'bg-slate-100 text-slate-700' }}">
                                    <i class="bi {{ $medalIcon }}"></i>
                                    <span>#{{ $index + 1 }}</span>
                                </div>
                            </td>
                            <td class="border-b border-slate-100 px-4 py-3">
                                <p class="max-w-[220px] truncate text-sm font-bold text-slate-900">{{ $item->nama }}</p>
                            </td>
                            <td class="border-b border-slate-100 px-4 py-3">
                                <span class="inline-flex items-center rounded-full border px-3 py-1 text-[11px] font-bold uppercase tracking-[0.08em] {{ $isTopThree ? 'border-orange-300 bg-orange-50 text-orange-900' : 'border-teal-200 bg-teal-50 text-teal-900' }}">
                                    {{ $isTopThree ? 'Top Performer' : 'Aktif' }}
                                </span>
                            </td>
                            <td class="border-b border-slate-100 px-4 py-3">
                                <div class="h-2.5 w-full rounded-full bg-slate-200">
                                    <div class="h-full rounded-full {{ $isTopThree ? 'bg-gradient-to-r from-orange-500 via-amber-400 to-yellow-300' : 'bg-gradient-to-r from-sky-600 to-teal-500' }}" style="width: {{ $progress }}%"></div>
                                </div>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500">{{ $progress }}%</p>
                            </td>
                            <td class="border-b border-slate-100 px-4 py-3 text-right">
                                <span class="font-display text-base text-slate-900">{{ $item->total_peminjaman }}</span>
                                <span class="sr-only sm:not-sr-only text-xs text-slate-500"> peminjaman</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm font-semibold text-slate-500">
                                Belum ada data peminjaman untuk ditampilkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</section>

<script>
    (function () {
        const refreshButton = document.getElementById('lbx-refresh-btn');

        if (!refreshButton) {
            return;
        }

        refreshButton.addEventListener('click', function () {
            const label = refreshButton.querySelector('.lbx-meta-text');
            const icon = refreshButton.querySelector('.lbx-meta-icon');

                refreshButton.disabled = true;
            icon?.classList.add('rotate-180');

            if (label) {
                label.textContent = 'Memuat ulang...';
            }

            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('refresh', Date.now().toString());
            window.location.href = currentUrl.toString();
        });
    })();
</script>
