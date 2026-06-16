<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Daftar | Perpustakaan Sekolah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500;600&family=Unbounded:wght@500;600;700;800&family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-shelf-cloud text-shelf-ink antialiased selection:bg-shelf-gold/80 selection:text-shelf-ink">
    <div class="relative isolate min-h-screen overflow-hidden bg-aurora">
        <div class="paper-grain"></div>

        <div class="pointer-events-none absolute -left-24 top-8 h-80 w-80 rounded-full bg-shelf-gold/42 blur-3xl animate-float-soft"></div>
        <div class="pointer-events-none absolute -right-20 top-20 h-96 w-96 rounded-full bg-shelf-teal/28 blur-3xl animate-float-soft [animation-delay:1.8s]"></div>
        <header class="relative z-20">
            <div class="mx-auto flex w-full max-w-6xl items-center justify-between px-5 pb-3 pt-6 sm:px-8 lg:px-10">
                <div>
                    <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 rounded-full border border-shelf-ink/12 bg-white/72 px-4 py-2 backdrop-blur-sm transition hover:border-shelf-teal/45 hover:bg-white">
                        <span class="grid h-10 w-10 place-content-center rounded-2xl bg-linear-[150deg,#10172e,#178f78] text-white shadow-lg shadow-shelf-teal/35">PB</span>
                        <span>
                            <span class="block font-display text-sm font-semibold leading-tight">Perpustakaan Sekolah</span>
                            <span class="block text-xs text-shelf-ink/65">Portal Literasi</span>
                        </span>
                    </a>
                    <p class="mt-2 ml-4 text-2xl font-bold text-shelf-ocean">Perpustakaan SMKN 1 Tirtamulya</p>
                </div>

                <nav class="flex items-center gap-2">
                    <a href="{{ route('landing') }}" class="rounded-full border border-shelf-ink/15 bg-white/75 px-4 py-2 text-sm font-semibold text-shelf-ink/85 transition hover:-translate-y-0.5 hover:border-shelf-teal/45 hover:text-shelf-teal">Landing</a>
                    <a href="{{ route('tampilan.login') }}" class="rounded-full bg-shelf-ink px-4 py-2 text-sm font-semibold text-white shadow-lg shadow-shelf-ink/30 transition hover:-translate-y-0.5 hover:bg-shelf-ocean">Masuk</a>
                </nav>
            </div>
        </header>

        <main class="relative z-10 mx-auto flex w-full max-w-6xl items-center px-5 pb-10 pt-2 sm:px-8 lg:px-10 lg:pb-16">
            <section class="grid w-full items-stretch gap-6 lg:grid-cols-[0.95fr_1.05fr]">
                <aside class="reveal-item glass-card relative hidden overflow-hidden rounded-[2rem] p-8 lg:block" data-reveal>
                    <div class="absolute -right-16 -top-16 h-44 w-44 rounded-full border border-white/90"></div>
                    <p class="font-mono inline-flex items-center gap-2 rounded-full border border-shelf-ocean/30 bg-white/80 px-3 py-1 text-[11px] uppercase tracking-[0.16em] text-shelf-ink/76">
                        <span class="h-1.5 w-1.5 rounded-full bg-shelf-ocean"></span>
                        New Member Access
                    </p>

                    <h1 class="mt-5 font-display text-4xl leading-tight text-shelf-ink">Buat akun dan mulai eksplorasi koleksi terbaik.</h1>
                    <p class="mt-4 max-w-md text-sm leading-relaxed text-shelf-ink/76">
                        Registrasi hanya beberapa langkah. Setelah itu kamu bisa mencari buku, meminjam, dan mengelola profil belajar dengan tampilan yang nyaman.
                    </p>

                    <div class="mt-8 space-y-3">
                        <article class="book-card rounded-2xl p-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/58">Fast Onboarding</p>
                            <p class="mt-1 text-sm font-semibold text-shelf-ink">Proses daftar singkat dengan field inti saja.</p>
                        </article>
                        <article class="book-card rounded-2xl p-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/58">Personal Space</p>
                            <p class="mt-1 text-sm font-semibold text-shelf-ink">Profil anggota siap dipersonalisasi setelah login.</p>
                        </article>
                        <article class="book-card rounded-2xl p-4">
                            <p class="font-mono text-[10px] uppercase tracking-[0.14em] text-shelf-ink/58">Read Consistently</p>
                            <p class="mt-1 text-sm font-semibold text-shelf-ink">Dukung kebiasaan baca harian yang lebih terukur.</p>
                        </article>
                    </div>
                </aside>

                <div class="reveal-item glass-card relative overflow-hidden rounded-[2rem] p-6 sm:p-8" data-reveal style="transition-delay:120ms;">
                    <div class="absolute -right-12 top-4 h-24 w-24 rounded-full border border-white/90"></div>

                    <div class="relative">
                        <p class="font-mono text-xs uppercase tracking-[0.16em] text-shelf-ink/62">Create Account</p>
                        <h2 class="mt-2 font-display text-3xl text-shelf-ink">{{ $judul ?? 'Daftar Akun Baru' }}</h2>
                        <p class="mt-1 text-sm text-shelf-ink/68">Isi data berikut untuk mulai menggunakan sistem perpustakaan.</p>
                    </div>

                    @if (session()->has('error'))
                        <div class="relative mt-5 rounded-2xl border border-red-500/25 bg-red-50/90 px-4 py-3 text-sm text-red-700">
                            <p class="font-semibold">{{ session('error') }}</p>
                        </div>
                    @endif

                    @if (session('status'))
                        <div class="relative mt-5 rounded-2xl border border-emerald-500/30 bg-emerald-50/90 px-4 py-3 text-sm text-emerald-700">
                            <p class="font-semibold">{{ session('message') }}</p>
                        </div>
                    @endif

                    <form action="{{ route('tampilan.register.process') }}" method="POST" class="relative mt-6 space-y-4">
                        @csrf

                        <div>
                            <label for="nama" class="font-mono text-[11px] uppercase tracking-[0.14em] text-shelf-ink/66">Nama Lengkap</label>
                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                value="{{ old('nama') }}"
                                placeholder="Masukkan nama lengkap"
                                required
                                class="mt-2 w-full rounded-2xl border border-shelf-ink/15 bg-white/86 px-4 py-3 text-sm text-shelf-ink outline-none transition placeholder:text-shelf-ink/45 focus:border-shelf-teal focus:ring-4 focus:ring-shelf-teal/15 @error('nama') border-red-500/70 focus:border-red-500 focus:ring-red-500/15 @enderror"
                            >
                            @error('nama')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

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
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input
                                    type="checkbox"
                                    name="has_nip"
                                    id="has_nip"
                                    value="1"
                                    {{ old('has_nip') ? 'checked' : '' }}
                                    onchange="toggleNipField()"
                                    class="h-4 w-4 rounded border-shelf-ink/25 bg-white text-shelf-teal focus:ring-2 focus:ring-shelf-teal/50 cursor-pointer"
                                >
                                <span class="font-mono text-[11px] uppercase tracking-[0.14em] text-shelf-ink/66">Saya memiliki NIP / NISN</span>
                            </label>
                        </div>

                        <div id="nip_field" class="{{ old('has_nip') ? '' : 'hidden' }} transition-all duration-300">
                            <label for="nip" class="font-mono text-[11px] uppercase tracking-[0.14em] text-shelf-ink/66">NIP / NISN</label>
                            <input
                                type="text"
                                name="nip"
                                id="nip"
                                value="{{ old('nip') }}"
                                placeholder="197104122006041001 atau 10 digit NISN"
                                class="mt-2 w-full rounded-2xl border border-shelf-ink/15 bg-white/86 px-4 py-3 text-sm text-shelf-ink outline-none transition placeholder:text-shelf-ink/45 focus:border-shelf-teal focus:ring-4 focus:ring-shelf-teal/15 @error('nip') border-red-500/70 focus:border-red-500 focus:ring-red-500/15 @enderror"
                            >
                            <p class="mt-1 text-[11px] text-shelf-ink/55">Gunakan NIP 18 digit untuk guru atau NISN 10 digit untuk siswa. Jika diisi NIP, akun akan dianggap sebagai guru.</p>
                            @error('nip')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="role_field" class="{{ old('has_nip') ? 'hidden' : '' }} transition-all duration-300">
                            <label class="font-mono text-[11px] uppercase tracking-[0.14em] text-shelf-ink/66">Daftar Sebagai</label>
                            <div class="mt-3 space-y-3">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input
                                        type="radio"
                                        name="role"
                                        value="{{ \App\Models\User::ROLE_ANGGOTA }}"
                                        {{ old('role', \App\Models\User::ROLE_ANGGOTA) == \App\Models\User::ROLE_ANGGOTA ? 'checked' : '' }}
                                        class="h-4 w-4 border-shelf-ink/25 bg-white text-shelf-teal focus:ring-2 focus:ring-shelf-teal/50 cursor-pointer"
                                    >
                                    <span class="text-sm text-shelf-ink">Anggota (Siswa)</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <input
                                        type="radio"
                                        name="role"
                                        value="{{ \App\Models\User::ROLE_GURU }}"
                                        {{ old('role') == \App\Models\User::ROLE_GURU ? 'checked' : '' }}
                                        class="h-4 w-4 border-shelf-ink/25 bg-white text-shelf-teal focus:ring-2 focus:ring-shelf-teal/50 cursor-pointer"
                                    >
                                    <span class="text-sm text-shelf-ink">Guru</span>
                                </label>
                            </div>
                            @error('role')
                                <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
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

                        <div>
                            <label for="hp" class="font-mono text-[11px] uppercase tracking-[0.14em] text-shelf-ink/66">No Handphone</label>
                            <input
                                type="text"
                                name="hp"
                                id="hp"
                                value="{{ old('hp') }}"
                                placeholder="08xxxxxxxxxx"
                                required
                                class="mt-2 w-full rounded-2xl border border-shelf-ink/15 bg-white/86 px-4 py-3 text-sm text-shelf-ink outline-none transition placeholder:text-shelf-ink/45 focus:border-shelf-teal focus:ring-4 focus:ring-shelf-teal/15 @error('hp') border-red-500/70 focus:border-red-500 focus:ring-red-500/15 @enderror"
                            >
                            @error('hp')
                                <p class="mt-1 text-xs font-semibold text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-2xl bg-linear-[130deg,#10172e,#178f78] px-5 py-3 text-sm font-semibold text-white shadow-xl shadow-shelf-ink/30 transition hover:-translate-y-0.5 hover:shadow-2xl hover:shadow-shelf-teal/35">
                            Buat Akun
                            <span aria-hidden="true">-&gt;</span>
                        </button>
                    </form>

                    <div class="relative mt-6 border-t border-shelf-ink/10 pt-5 text-sm text-shelf-ink/70">
                        Sudah punya akun?
                        <a href="{{ route('tampilan.login') }}" class="font-semibold text-shelf-teal transition hover:text-shelf-ocean">Masuk di sini</a>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <script>
        function toggleNipField() {
            const checkbox = document.getElementById('has_nip');
            const nipField = document.getElementById('nip_field');
            const roleField = document.getElementById('role_field');
            const nipInput = document.getElementById('nip');
            
            if (checkbox.checked) {
                // Checkbox dicentang: tampilkan NIP field, sembunyikan role field
                nipField.classList.remove('hidden');
                roleField.classList.add('hidden');
            } else {
                // Checkbox tidak dicentang: sembunyikan NIP field, tampilkan role field
                nipField.classList.add('hidden');
                roleField.classList.remove('hidden');
                nipInput.value = '';
            }
        }
    </script>
</body>
</html>