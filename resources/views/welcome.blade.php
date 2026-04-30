@php
    $marqueeGenres = [
        'Sains Populer',
        'Teknologi',
        'Sejarah Nusantara',
        'Sastra Indonesia',
        'Biografi Tokoh',
        'Psikologi Remaja',
        'Karya Referensi',
        'Pengembangan Diri',
    ];

    $showcaseCards = [
        [
            'tag' => 'Smart Discovery',
            'title' => 'Pencarian multi-filter yang tidak membuat siswa tersesat di katalog.',
            'value' => '0.8s',
            'note' => 'Waktu respons rata-rata',
        ],
        [
            'tag' => 'Borrow Tracking',
            'title' => 'Riwayat peminjaman rapi dan dapat ditinjau ulang oleh tiap peran.',
            'value' => '24/7',
            'note' => 'Aktivitas terpantau',
        ],
        [
            'tag' => 'Role Focus',
            'title' => 'Setiap panel menonjolkan fungsi yang relevan tanpa distraksi.',
            'value' => '4',
            'note' => 'Portal terintegrasi',
        ],
    ];

    $roleBlocks = [
        [
            'title' => 'Admin',
            'description' => 'Kontrol koleksi, kategori, peminjaman, dan status akun dalam satu alur kerja.',
            'accent' => 'bg-shelf-teal/15 text-shelf-teal',
        ],
        [
            'title' => 'Guru',
            'description' => 'Memantau pola baca peserta didik dan mengarahkan rekomendasi buku kelas.',
            'accent' => 'bg-shelf-ocean/15 text-shelf-ocean',
        ],
        [
            'title' => 'Anggota',
            'description' => 'Menjelajah buku favorit, memperbarui profil, dan memeriksa status peminjaman.',
            'accent' => 'bg-shelf-coral/15 text-shelf-coral',
        ],
        [
            'title' => 'Kepala Sekolah',
            'description' => 'Akses ringkasan literasi untuk evaluasi kebijakan dan keputusan strategis.',
            'accent' => 'bg-shelf-gold/25 text-shelf-ink',
        ],
    ];

    $journeyPoints = [
        'Masuk sesuai peran dan langsung melihat dashboard yang relevan.',
        'Telusuri buku dengan filter kategori, judul, dan metadata yang jelas.',
        'Kelola peminjaman, pengembalian, dan riwayat tanpa berpindah alur.',
        'Gunakan insight aktivitas untuk meningkatkan budaya baca sekolah.',
    ];
@endphp

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Perpustakaan Sekolah | Ruang Baca Generasi Baru</title>
    <meta name="description" content="Landing page perpustakaan digital sekolah dengan desain premium, alur jelas, dan pengalaman baca modern.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500;600&family=Unbounded:wght@500;600;700;800&family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-shelf-cloud text-shelf-ink antialiased selection:bg-shelf-gold/85 selection:text-shelf-ink">
    <div class="relative isolate min-h-screen overflow-x-clip bg-aurora">
        <div class="paper-grain"></div>
        <div class="pointer-events-none absolute -left-28 top-20 h-80 w-80 rounded-full bg-shelf-gold/45 blur-3xl animate-float-soft"></div>
        <div class="pointer-events-none absolute -right-20 top-8 h-96 w-96 rounded-full bg-shelf-teal/28 blur-3xl animate-float-soft [animation-delay:1.8s]"></div>
        <div class="pointer-events-none absolute right-1/3 top-[38rem] h-72 w-72 rounded-full bg-shelf-ocean/25 blur-3xl animate-float-soft [animation-delay:3.2s]"></div>

        <header class="relative z-20">
            <div class="mx-auto flex w-full max-w-7xl items-center justify-between px-5 pb-3 pt-6 sm:px-8 lg:px-10">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 rounded-full border border-shelf-ink/12 bg-white/70 px-4 py-2 backdrop-blur-sm transition hover:border-shelf-teal/45 hover:bg-white">
                    <span class="grid h-10 w-10 place-content-center rounded-2xl bg-linear-[148deg,#10172e,#178f78] text-white shadow-lg shadow-shelf-teal/35">PB</span>
                    <span>
                        <span class="block font-display text-sm font-semibold leading-tight">Perpustakaan Sekolah</span>
                        <span class="block text-xs text-shelf-ink/68">Ruang baca yang bergerak</span>
                    </span>
                </a>

                <nav class="hidden items-center gap-1 rounded-full border border-shelf-ink/12 bg-white/70 p-1 text-sm font-semibold backdrop-blur-sm md:flex">
                    <a href="#fitur" class="rounded-full px-4 py-2 text-shelf-ink/75 transition hover:bg-shelf-mist hover:text-shelf-ocean">Fitur</a>
                    <a href="#alur" class="rounded-full px-4 py-2 text-shelf-ink/75 transition hover:bg-shelf-mist hover:text-shelf-ocean">Alur</a>
                    <a href="#peran" class="rounded-full px-4 py-2 text-shelf-ink/75 transition hover:bg-shelf-mist hover:text-shelf-ocean">Portal</a>
                </nav>

                <div class="flex items-center gap-2">
                    @if (Auth::check())
                        <a href="{{ route('dashboard') }}" class="rounded-full border border-shelf-ink/22 bg-white/86 px-4 py-2 text-sm font-semibold text-shelf-ink transition hover:-translate-y-0.5 hover:border-shelf-teal/45 hover:text-shelf-teal">Dashboard</a>
                    @else
                        <a href="{{ route('tampilan.login') }}" class="rounded-full border border-shelf-ink/22 bg-white/86 px-4 py-2 text-sm font-semibold text-shelf-ink transition hover:-translate-y-0.5 hover:border-shelf-teal/45 hover:text-shelf-teal">Masuk</a>
                        @if (Route::has('tampilan.register'))
                            <a href="{{ route('tampilan.register') }}" class="rounded-full bg-shelf-ink px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-shelf-ink/35 transition hover:-translate-y-0.5 hover:bg-shelf-ocean">Daftar</a>
                        @endif
                    @endif
                </div>
            </div>
        </header>

        <main class="relative z-10 mx-auto w-full max-w-7xl px-5 pb-16 sm:px-8 lg:px-10 lg:pb-24">
            <section class="grid items-center gap-10 pt-4 lg:grid-cols-[1.1fr_0.9fr] lg:gap-12 lg:pt-8">
                <div class="reveal-item" data-reveal>
                    <p class="font-mono inline-flex items-center gap-2 rounded-full border border-shelf-coral/35 bg-white/75 px-4 py-2 text-[11px] font-semibold uppercase tracking-[0.16em] text-shelf-ink/82 backdrop-blur-sm">
                        <span class="h-1.5 w-1.5 rounded-full bg-shelf-coral"></span>
                        Perpustakaan Digital Sekolah
                    </p>

                    <h1 class="mt-6 font-display font-semibold leading-[1.03] text-shelf-ink [font-size:clamp(2.2rem,7vw,5.9rem)]">
                        Tampil modern,
                        <span class="ink-gradient">belajar makin fokus</span>
                        setiap hari.
                    </h1>

                    <p class="mt-6 max-w-2xl text-lg leading-relaxed text-shelf-ink/76">
                        Dari pencarian buku sampai pelacakan peminjaman, semua dirancang seperti pengalaman produk premium: cepat, bersih, dan mudah dipahami oleh siswa, guru, admin, sampai kepala sekolah.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-3">
                        @if (Auth::check())
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 rounded-full bg-shelf-teal px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-shelf-teal/35 transition hover:-translate-y-0.5 hover:bg-shelf-ocean">
                                Buka Dashboard
                                <span aria-hidden="true">-&gt;</span>
                            </a>
                        @else
                            <a href="{{ route('tampilan.login') }}" class="inline-flex items-center gap-2 rounded-full bg-shelf-teal px-6 py-3 text-sm font-semibold text-white shadow-xl shadow-shelf-teal/35 transition hover:-translate-y-0.5 hover:bg-shelf-ocean">
                                Masuk ke Dashboard
                                <span aria-hidden="true">-&gt;</span>
                            </a>
                            @if (Route::has('tampilan.register'))
                                <a href="{{ route('tampilan.register') }}" class="inline-flex items-center gap-2 rounded-full border border-shelf-ink/22 bg-white/82 px-6 py-3 text-sm font-semibold text-shelf-ink backdrop-blur-sm transition hover:-translate-y-0.5 hover:border-shelf-coral/45">Buat Akun Baru</a>
                            @endif
                        @endif
                    </div>

                    <div class="mt-8 grid max-w-2xl grid-cols-1 gap-3 sm:grid-cols-3">
                        <div class="glass-card rounded-2xl p-4">
                            <p class="text-xs uppercase tracking-[0.12em] text-shelf-ink/62">Portal Aktif</p>
                            <p class="mt-1 font-display text-2xl text-shelf-ink">4 Role</p>
                        </div>
                        <div class="glass-card rounded-2xl p-4">
                            <p class="text-xs uppercase tracking-[0.12em] text-shelf-ink/62">Status Sistem</p>
                            <p class="mt-1 font-display text-2xl text-shelf-ink">Realtime</p>
                        </div>
                        <div class="glass-card rounded-2xl p-4">
                            <p class="text-xs uppercase tracking-[0.12em] text-shelf-ink/62">Skala Koleksi</p>
                            <p class="mt-1 font-display text-2xl text-shelf-ink">18K+</p>
                        </div>
                    </div>
                </div>

                <div class="reveal-item relative" data-reveal style="transition-delay: 140ms;">
                    <div class="glass-card relative overflow-hidden rounded-[2rem] p-6 sm:p-8">
                        <div class="absolute -right-20 -top-20 h-56 w-56 rounded-full border border-white/90"></div>
                        <div class="absolute right-6 top-6 h-14 w-14 rounded-full bg-shelf-gold/85"></div>
                        <div class="absolute left-6 top-20 h-10 w-10 rounded-full bg-shelf-coral/80"></div>

                        <div class="relative flex items-center justify-between rounded-2xl bg-white/75 px-4 py-3 text-sm font-semibold text-shelf-ink/84">
                            <span>Reading Pulse Board</span>
                            <span class="rounded-full bg-shelf-teal px-3 py-1 text-xs text-white">Live</span>
                        </div>

                        <div class="relative mt-6 h-[360px]">
                            <div class="absolute left-1/2 top-1/2 h-64 w-64 -translate-x-1/2 -translate-y-1/2 rounded-full border border-shelf-ink/16 animate-orbit-slow"></div>
                            <div class="absolute left-1/2 top-1/2 h-48 w-48 -translate-x-1/2 -translate-y-1/2 rounded-full border border-shelf-teal/30 animate-orbit-slow [animation-direction:reverse] [animation-duration:16s]"></div>
                            <div class="absolute left-1/2 top-1/2 grid h-28 w-28 -translate-x-1/2 -translate-y-1/2 place-content-center rounded-3xl bg-shelf-ink text-center text-white shadow-2xl shadow-shelf-ink/35">
                                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-white/74">Queue</p>
                                <p class="font-display text-3xl leading-none">24</p>
                            </div>

                            <article class="book-card absolute left-3 top-8 w-[68%] rounded-2xl p-4 [transform:rotate(-7deg)]">
                                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/56">Collection Growth</p>
                                <p class="mt-1 text-sm font-semibold text-shelf-ink">Tambah 152 buku baru bulan ini</p>
                            </article>

                            <article class="book-card absolute right-3 top-28 w-[64%] rounded-2xl p-4 [transform:rotate(6deg)]">
                                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/56">Borrowing Trend</p>
                                <p class="mt-1 text-sm font-semibold text-shelf-ink">Peminjaman naik 27% minggu ini</p>
                            </article>

                            <article class="book-card absolute bottom-8 left-8 w-[66%] rounded-2xl p-4 [transform:rotate(-3deg)]">
                                <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/56">Active Members</p>
                                <p class="mt-1 text-sm font-semibold text-shelf-ink">1.248 akun aktif membaca rutin</p>
                            </article>
                        </div>
                    </div>
                </div>
            </section>

            <section class="reveal-item mt-12 overflow-hidden rounded-2xl border border-shelf-ink/10 bg-white/70 py-3 backdrop-blur-sm" data-reveal style="transition-delay: 210ms;">
                <div class="marquee-track">
                    @foreach (array_merge($marqueeGenres, $marqueeGenres) as $genre)
                        <span class="mx-2 inline-flex items-center gap-2 rounded-full border border-shelf-ink/12 bg-white px-4 py-2 text-sm font-semibold text-shelf-ink/80">
                            <span class="h-1.5 w-1.5 rounded-full bg-shelf-coral"></span>
                            {{ $genre }}
                        </span>
                    @endforeach
                </div>
            </section>

            <section id="fitur" class="mt-14 sm:mt-16 lg:mt-20">
                <div class="reveal-item mb-7" data-reveal>
                    <p class="font-mono text-xs uppercase tracking-[0.16em] text-shelf-ink/62">Signature Features</p>
                    <h2 class="mt-2 font-display text-4xl leading-tight text-shelf-ink sm:text-5xl">Dirancang bukan sekadar cantik, tapi benar-benar terasa cepat.</h2>
                </div>

                <div class="grid gap-4 md:grid-cols-3">
                    @foreach ($showcaseCards as $index => $card)
                        <article class="reveal-item glass-card group relative overflow-hidden rounded-3xl p-6 transition duration-300 hover:-translate-y-1.5 hover:shadow-2xl" data-reveal style="transition-delay: {{ 120 + ($index * 90) }}ms;">
                            <div class="absolute -right-12 -top-12 h-28 w-28 rounded-full bg-shelf-gold/28 blur-2xl"></div>
                            <p class="font-mono inline-flex rounded-full border border-shelf-ink/12 bg-white/80 px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.14em] text-shelf-ink/72">{{ $card['tag'] }}</p>
                            <p class="mt-4 text-sm leading-relaxed text-shelf-ink/78">{{ $card['title'] }}</p>
                            <div class="mt-6 flex items-end justify-between gap-3">
                                <p class="font-display text-4xl text-shelf-ink">{{ $card['value'] }}</p>
                                <p class="text-right text-xs text-shelf-ink/62">{{ $card['note'] }}</p>
                            </div>
                            <div class="pointer-events-none absolute inset-0 opacity-0 transition duration-500 group-hover:opacity-100 [background:linear-gradient(120deg,rgba(255,255,255,0)_35%,rgba(255,255,255,.75)_50%,rgba(255,255,255,0)_65%)] [background-size:200%_100%] [animation:marquee_1.8s_linear_infinite]"></div>
                        </article>
                    @endforeach
                </div>
            </section>

            <section id="alur" class="mt-14 grid gap-6 lg:mt-18 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="reveal-item rounded-[2rem] border border-shelf-ink/12 bg-white/72 p-6 backdrop-blur-sm sm:p-8" data-reveal>
                    <p class="font-mono text-xs uppercase tracking-[0.16em] text-shelf-ink/62">Flow</p>
                    <h3 class="mt-2 font-display text-3xl leading-tight text-shelf-ink sm:text-4xl">Alur yang terstruktur untuk semua pengguna.</h3>

                    <ol class="mt-7 space-y-4">
                        @foreach ($journeyPoints as $step => $point)
                            <li class="relative rounded-2xl border border-shelf-ink/12 bg-white/80 p-4 pl-14">
                                <span class="absolute left-4 top-1/2 grid h-8 w-8 -translate-y-1/2 place-content-center rounded-full bg-shelf-ink text-sm font-semibold text-white">{{ $step + 1 }}</span>
                                <p class="text-sm leading-relaxed text-shelf-ink/78">{{ $point }}</p>
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div id="peran" class="grid gap-3 sm:grid-cols-2">
                    @foreach ($roleBlocks as $index => $role)
                        <article class="reveal-item rounded-2xl border border-shelf-ink/12 bg-linear-[140deg,#ffffff,#f8fcff] p-5 shadow-sm shadow-shelf-ink/7 transition hover:-translate-y-1 hover:border-shelf-teal/42" data-reveal style="transition-delay: {{ 150 + ($index * 80) }}ms;">
                            <span class="inline-flex rounded-full px-3 py-1 text-xs font-semibold {{ $role['accent'] }}">{{ $role['title'] }}</span>
                            <p class="mt-3 text-sm leading-relaxed text-shelf-ink/78">{{ $role['description'] }}</p>
                        </article>
                    @endforeach
                </div>
            </section>

            <section class="reveal-item mt-14 rounded-[2rem] bg-linear-[120deg,#10172e_0%,#1d4f78_45%,#178f78_100%] px-6 py-9 text-white shadow-2xl shadow-shelf-ink/35 sm:px-10 sm:py-11" data-reveal>
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="font-mono text-xs uppercase tracking-[0.16em] text-white/68">Launch Better Reading Culture</p>
                        <h3 class="mt-2 font-display text-3xl leading-tight sm:text-4xl">Mulai dari tampilan yang menarik, lanjut ke kebiasaan baca yang konsisten.</h3>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('tampilan.login') }}" class="rounded-full bg-shelf-gold px-5 py-3 text-sm font-semibold text-shelf-ink transition hover:-translate-y-0.5 hover:bg-white">Masuk Sekarang</a>
                        @if (Route::has('tampilan.register'))
                            <a href="{{ route('tampilan.register') }}" class="rounded-full border border-white/38 px-5 py-3 text-sm font-semibold text-white transition hover:-translate-y-0.5 hover:bg-white/12">Daftar Akun Baru</a>
                        @endif
                    </div>
                </div>
            </section>
        </main>

        <footer class="relative z-10 border-t border-shelf-ink/10 px-5 py-4 text-center text-xs text-shelf-ink/64 sm:px-8 lg:px-10">
            &copy; {{ date('Y') }} Perpustakaan Sekolah. Crafted for focused reading experiences.
        </footer>
    </div>
</body>
</html>