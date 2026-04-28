<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Masuk | Perpustakaan Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500;600&family=Unbounded:wght@500;600;700;800&family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-shelf-cloud text-shelf-ink antialiased selection:bg-shelf-gold/80 selection:text-shelf-ink">
    <div class="relative isolate min-h-screen overflow-hidden bg-aurora">
        <div class="paper-grain"></div>

        <div class="pointer-events-none absolute -left-20 top-12 h-72 w-72 rounded-full bg-shelf-gold/40 blur-3xl animate-float-soft"></div>
        <div class="pointer-events-none absolute -right-16 top-18 h-80 w-80 rounded-full bg-shelf-teal/26 blur-3xl animate-float-soft [animation-delay:1.6s]"></div>
        <div class="pointer-events-none absolute left-[8%] top-[36%] h-3 w-3 rounded-full bg-shelf-coral/75 animate-pulse"></div>
        <div class="pointer-events-none absolute left-[12%] top-[64%] h-2.5 w-2.5 rounded-full bg-shelf-ocean/60 animate-pulse [animation-delay:.3s]"></div>
        <div class="pointer-events-none absolute right-[10%] top-[30%] h-3 w-3 rounded-full bg-shelf-teal/70 animate-pulse [animation-delay:.8s]"></div>
        <div class="pointer-events-none absolute right-[18%] top-[70%] h-2 w-2 rounded-full bg-shelf-gold/80 animate-pulse [animation-delay:1.2s]"></div>

        <header class="relative z-20">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 pb-3 pt-6 sm:px-8 lg:px-10">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 rounded-full border border-shelf-ink/12 bg-white/72 px-4 py-2 backdrop-blur-sm transition hover:border-shelf-teal/45 hover:bg-white">
                    <span class="grid h-10 w-10 place-content-center rounded-2xl bg-linear-[150deg,#10172e,#178f78] text-white shadow-lg shadow-shelf-teal/35">PB</span>
                    <span>
                        <span class="block font-display text-sm font-semibold leading-tight">Perpustakaan Sekolah</span>
                        <span class="block text-xs text-shelf-ink/65">Portal Literasi</span>
                    </span>
                </a>

                <nav class="flex items-center gap-2">
                    <a href="{{ route('landing') }}" class="rounded-full border border-shelf-ink/15 bg-white/75 px-4 py-2 text-sm font-semibold text-shelf-ink/85 transition hover:-translate-y-0.5 hover:border-shelf-teal/45 hover:text-shelf-teal">Landing</a>
                    @if (Route::has('tampilan.register'))
                        <a href="{{ route('tampilan.register') }}" class="rounded-full bg-shelf-ink px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-shelf-ink/30 transition hover:-translate-y-0.5 hover:bg-shelf-ocean">Daftar</a>
                    @endif
                </nav>
            </div>
        </header>

        <main class="relative z-10 mx-auto flex w-full max-w-6xl items-center px-5 pb-10 pt-2 sm:px-8 lg:px-10 lg:pb-16">
            <section class="grid w-full items-stretch gap-6 lg:grid-cols-[1.05fr_0.95fr]">
                <aside class="reveal-item glass-card relative hidden overflow-hidden rounded-[2rem] p-8 lg:block" data-reveal>
                    <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full border border-white/90"></div>
                    <div class="absolute left-8 top-10 h-10 w-10 rounded-full bg-shelf-coral/75"></div>
                    <div class="absolute right-10 top-24 h-3 w-3 rounded-full bg-shelf-gold/85 animate-pulse"></div>

                    <p class="font-mono inline-flex items-center gap-2 rounded-full border border-shelf-coral/30 bg-white/80 px-3 py-1 text-[11px] uppercase tracking-[0.16em] text-shelf-ink/76">
                        <span class="h-1.5 w-1.5 rounded-full bg-shelf-coral"></span>
                        Auth Access
                    </p>

                    <h1 class="mt-5 font-display text-4xl leading-tight text-shelf-ink">Masuk ke ruang baca digital yang lebih hidup.</h1>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-shelf-ink/76">
                        Temukan buku, kelola peminjaman, dan lanjutkan aktivitas literasi dari dashboard yang disesuaikan dengan peranmu.
                    </p>

                    <div class="mt-8 space-y-3">
                        <article class="book-card rounded-2xl p-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/58">Quick Access</p>
                            <p class="mt-1 text-sm font-semibold text-shelf-ink">Langsung ke fitur terpenting setelah login.</p>
                        </article>
                        <article class="book-card rounded-2xl p-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/58">Reading Pulse</p>
                            <p class="mt-1 text-sm font-semibold text-shelf-ink">Aktivitas peminjaman selalu terpantau realtime.</p>
                        </article>
                    </div>
                </aside>

                <div class="reveal-item glass-card relative overflow-hidden rounded-[2rem] p-6 sm:p-8" data-reveal style="transition-delay:120ms;">
                    <div class="absolute -right-12 top-4 h-24 w-24 rounded-full border border-white/90"></div>
                    <div class="absolute left-3 top-3 h-2.5 w-2.5 rounded-full bg-shelf-gold/85 animate-pulse"></div>
                    <div class="absolute right-10 top-16 h-2 w-2 rounded-full bg-shelf-coral/80 animate-pulse [animation-delay:.4s]"></div>

                    <div class="relative">
                        <p class="font-mono text-xs uppercase tracking-[0.16em] text-shelf-ink/62">Welcome Back</p>
                        <h2 class="mt-2 font-display text-3xl text-shelf-ink">Masuk Akun</h2>
                        <p class="mt-1 text-sm text-shelf-ink/68">Silakan login untuk melanjutkan perjalanan literasi.</p>
                    </div>

                    @if (session()->has('error'))
                        <div class="relative mt-5 rounded-2xl border border-red-500/25 bg-red-50/90 px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold">{{ session('error') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('tampilan.login.process') }}" method="POST" class="relative mt-6 space-y-4">
                        @csrf

                        <div>
                            <label for="email" class="font-mono text-[11px] uppercase tracking-[0.14em] text-shelf-ink/66">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                value="{{ old('email') }}"
                                placeholder="nama@email.com"
                                required
                                class="mt-2 w-full rounded-2xl border border-shelf-ink/15 bg-white/86 px-4 py-3 text-sm text-shelf-ink outline-none transition placeholder:text-shelf-ink/45 focus:border-shelf-teal focus:ring-4 focus:ring-shelf-teal/15 @error('email') border-red-500/70 focus:border-red-500 focus:ring-red-500/15 @enderror"
                            >
                            @error('email')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="password" class="font-mono text-[11px] uppercase tracking-[0.14em] text-shelf-ink/66">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="password"
                                placeholder="Masukkan password"
                                required
                                class="mt-2 w-full rounded-2xl border border-shelf-ink/15 bg-white/86 px-4 py-3 text-sm text-shelf-ink outline-none transition placeholder:text-shelf-ink/45 focus:border-shelf-teal focus:ring-4 focus:ring-shelf-teal/15 @error('password') border-red-500/70 focus:border-red-500 focus:ring-red-500/15 @enderror"
                            >
                            @error('password')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-linear-[130deg,#10172e,#178f78] px-5 py-3 text-sm font-semibold text-white shadow-xl shadow-shelf-ink/30 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-shelf-teal/35">
                            Masuk Dashboard
                            <span aria-hidden="true">-&gt;</span>
                        </button>
                    </form>

                    <div class="relative mt-6 border-t border-shelf-ink/10 pt-5 text-sm text-shelf-ink/70">
                        Belum punya akun?
                        <a href="{{ route('tampilan.register') }}" class="font-semibold text-shelf-teal transition hover:text-shelf-ocean">Daftar sekarang</a>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
</html>