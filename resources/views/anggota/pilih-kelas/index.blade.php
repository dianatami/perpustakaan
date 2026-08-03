<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pilih Kelas | Perpustakaan SMKN 1 Tirtamulya</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500;600&family=Unbounded:wght@500;600;700;800&family=Urbanist:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-shelf-cloud text-shelf-ink antialiased selection:bg-shelf-gold/80 selection:text-shelf-ink overflow-x-hidden min-h-screen">
    @include('partials.particles')

    <div class="relative isolate min-h-screen overflow-hidden bg-aurora flex flex-col justify-between">
        <div class="paper-grain"></div>

        <!-- Ambient blur Orbs -->
        <div class="pointer-events-none absolute -left-20 top-12 h-72 w-72 rounded-full bg-shelf-gold/35 blur-3xl animate-float-soft"></div>
        <div class="pointer-events-none absolute -right-16 top-24 h-80 w-80 rounded-full bg-shelf-teal/25 blur-3xl animate-float-soft [animation-delay:1.6s]"></div>
        <div class="pointer-events-none absolute left-1/3 bottom-10 h-64 w-64 rounded-full bg-shelf-ocean/20 blur-3xl animate-float-soft [animation-delay:2.4s]"></div>

        <!-- Header Bar -->
        <header class="relative z-20 w-full">
            <div class="mx-auto flex w-full max-w-5xl items-center justify-between px-4 py-4 sm:px-8 sm:py-6">
                <a href="{{ route('landing') }}" class="inline-flex items-center gap-3 rounded-full border border-shelf-ink/12 bg-white/75 px-3.5 py-1.5 backdrop-blur-md transition hover:border-shelf-teal/45 hover:bg-white sm:px-4 sm:py-2">
                    <img src="{{ asset('images/logo.png') }}" alt="Logo SMKN 1 Tirtamulya" class="h-8 w-8 object-contain drop-shadow sm:h-9 sm:w-9">
                    <span>
                        <span class="block font-display text-xs font-semibold leading-tight sm:text-sm">Perpustakaan Sekolah</span>
                        <span class="block text-[10px] text-shelf-ink/65 sm:text-xs">SMKN 1 Tirtamulya</span>
                    </span>
                </a>

                <form action="{{ route('tampilan.logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-1.5 rounded-full border border-shelf-ink/15 bg-white/80 px-3 py-1.5 text-xs font-semibold text-shelf-ink/80 transition hover:bg-red-50 hover:text-red-600 sm:px-4 sm:py-2 sm:text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </header>

        <!-- Main Card Area -->
        <main class="relative z-10 mx-auto my-auto w-full max-w-3xl px-4 py-6 sm:px-8 lg:py-10">
            <div class="glass-card relative overflow-hidden rounded-3xl p-5 sm:p-8 md:p-10 shadow-2xl border border-white/80">
                <div class="absolute -right-10 -top-10 h-32 w-32 rounded-full border border-white/60 pointer-events-none"></div>

                <!-- Header Info -->
                <div class="text-center sm:text-left">
                    <div class="inline-flex items-center gap-2 rounded-full border border-shelf-teal/30 bg-shelf-teal/10 px-3 py-1 font-mono text-[11px] uppercase tracking-wider text-shelf-teal">
                        <span class="h-2 w-2 rounded-full bg-shelf-teal animate-pulse"></span>
                        Langkah Terakhir • Lengkapi Profil Siswa
                    </div>
                    <h1 class="mt-3 font-display text-2xl font-bold text-shelf-ink sm:text-3xl lg:text-4xl leading-tight">
                        Halo, <span class="text-shelf-ocean">{{ $user->nama ?? 'Siswa' }}</span>! 👋
                    </h1>
                    <p class="mt-2 text-xs sm:text-sm leading-relaxed text-shelf-ink/75">
                        Silakan pilih kelas tempat Anda belajar di SMKN 1 Tirtamulya untuk membuka akses penuh ke perpustakaan.
                    </p>
                </div>

                <!-- Alert Messages -->
                @if (session('info'))
                    <div class="mt-5 rounded-2xl border border-shelf-teal/30 bg-shelf-teal/10 p-3.5 text-xs sm:text-sm text-shelf-ink/90 flex items-start gap-2.5">
                        <svg class="w-5 h-5 text-shelf-teal shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mt-5 rounded-2xl border border-red-500/30 bg-red-50/90 p-4 text-xs sm:text-sm text-red-700">
                        <p class="font-semibold mb-1">Terdapat kesalahan pengisian:</p>
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Class Selection Form -->
                <form action="{{ route('anggota.pilih-kelas.store') }}" method="POST" class="mt-6 space-y-6 sm:space-y-8" id="form-pilih-kelas">
                    @csrf

                    <!-- 1. Pilih Tingkat Kelas -->
                    <div class="space-y-2.5">
                        <label class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-shelf-ink/70">
                            1. Tingkat Kelas <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-2.5 sm:gap-4">
                            @foreach ($tingkatList as $t)
                                <label class="option-card relative flex cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-shelf-ink/12 bg-white/80 py-3.5 px-2 text-center backdrop-blur-sm transition-all duration-200 hover:border-shelf-teal/50 hover:bg-white">
                                    <input type="radio" name="tingkat" value="{{ $t }}" class="sr-only" {{ old('tingkat') == $t ? 'checked' : ($t == 'X' ? 'checked' : '') }} required>
                                    <span class="font-display text-base font-bold text-shelf-ink transition-colors sm:text-lg">Tingkat {{ $t }}</span>
                                    <span class="font-mono text-[10px] text-shelf-ink/50 mt-0.5">Kelas {{ $t == 'X' ? '10' : ($t == 'XI' ? '11' : '12') }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 2. Pilih Jurusan / Program Keahlian -->
                    <div class="space-y-2.5">
                        <label class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-shelf-ink/70">
                            2. Jurusan / Program Keahlian <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-2 gap-2.5 sm:grid-cols-3 sm:gap-3">
                            @foreach ($jurusanList as $kode => $info)
                                <label class="option-card relative flex cursor-pointer flex-col justify-between rounded-2xl border-2 border-shelf-ink/12 bg-white/80 p-3.5 backdrop-blur-sm transition-all duration-200 hover:border-shelf-teal/50 hover:bg-white" data-max-rombel="{{ $info['max_rombel'] }}">
                                    <input type="radio" name="jurusan" value="{{ $kode }}" class="sr-only" {{ old('jurusan') == $kode ? 'checked' : ($loop->first ? 'checked' : '') }} required>
                                    <div class="flex items-center justify-between w-full">
                                        <span class="font-display text-base font-extrabold text-shelf-ink">{{ $kode }}</span>
                                        <span class="radio-indicator w-4 h-4 rounded-full border-2 border-shelf-ink/30 flex items-center justify-center text-white transition-all">
                                            <svg class="w-2.5 h-2.5 stroke-current" fill="none" viewBox="0 0 24 24" stroke-width="3"><path d="M5 13l4 4L19 7"></path></svg>
                                        </span>
                                    </div>
                                    <span class="mt-2 text-[11px] font-medium leading-tight text-shelf-ink/75">{{ $info['nama'] }}</span>
                                    <span class="mt-1 font-mono text-[9px] text-shelf-ink/50 uppercase tracking-wider">{{ $info['max_rombel'] }} Kelas</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- 3. Pilih Nama Kelas -->
                    <div class="space-y-2.5">
                        <label class="block font-mono text-[11px] font-semibold uppercase tracking-wider text-shelf-ink/70">
                            3. Nama Kelas <span class="text-red-500">*</span>
                        </label>
                        <div class="flex items-center gap-2.5 sm:gap-3" id="rombel-container">
                            @foreach (['1', '2', '3'] as $no)
                                <label class="option-card rombel-option-card flex-1 relative flex cursor-pointer items-center justify-center rounded-2xl border-2 border-shelf-ink/12 bg-white/80 py-3 px-3 text-center transition-all duration-200 hover:border-shelf-teal/50 hover:bg-white" data-rombel="{{ $no }}">
                                    <input type="radio" name="nomor_kelas" value="{{ $no }}" class="sr-only" {{ old('nomor_kelas', '1') == $no ? 'checked' : '' }} required>
                                    <span class="rombel-text font-display text-sm sm:text-base font-bold text-shelf-ink">TJKT {{ $no }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Live Dynamic Preview Banner -->
                    <div class="rounded-2xl border border-shelf-teal/40 bg-gradient-to-r from-shelf-teal/15 via-white/90 to-shelf-gold/15 p-4 sm:p-5 backdrop-blur-md text-center shadow-lg">
                        <p class="font-mono text-[11px] uppercase tracking-wider text-shelf-ink/60">Konfirmasi Kelas Yang Dipilih</p>
                        <div class="mt-1 flex items-center justify-center gap-2">
                            <span class="font-display text-2xl font-extrabold text-shelf-ink sm:text-3xl tracking-wide" id="preview-kelas">X TJKT 1</span>
                        </div>
                        <p class="mt-1 text-[11px] text-shelf-ink/70">Pastikan pilihan kelas Anda sudah benar sebelum menekan tombol simpan.</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-2">
                        <button type="submit" class="group relative flex w-full items-center justify-center gap-2 rounded-2xl bg-shelf-ink py-3.5 px-6 font-display text-sm font-semibold text-white shadow-xl shadow-shelf-ink/25 transition-all duration-200 hover:-translate-y-0.5 hover:bg-shelf-ocean focus:outline-none focus:ring-4 focus:ring-shelf-teal/30 sm:text-base">
                            <span>Simpan Kelas & Lanjutkan</span>
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-20 py-4 text-center text-xs text-shelf-ink/60">
            <p>&copy; {{ date('Y') }} Perpustakaan SMKN 1 Tirtamulya. All rights reserved.</p>
        </footer>
    </div>

    <!-- Reactive JS styling, Rombel filter & label update, & live text preview -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('form-pilih-kelas');
            const previewEl = document.getElementById('preview-kelas');

            function updateSelectionStyles() {
                const selectedJurusanInput = form.querySelector('input[name="jurusan"]:checked');
                const selectedJurusanLabel = selectedJurusanInput?.closest('label');
                const selectedJurusanCode = selectedJurusanInput?.value || 'TJKT';
                const maxRombel = parseInt(selectedJurusanLabel?.getAttribute('data-max-rombel') || '2', 10);

                // Update text & visibility for class option buttons (e.g. TJKT 1, TJKT 2, TM 3)
                const rombelCards = form.querySelectorAll('.rombel-option-card');
                rombelCards.forEach(card => {
                    const rVal = parseInt(card.getAttribute('data-rombel'), 10);
                    const textSpan = card.querySelector('.rombel-text');
                    
                    if (textSpan) {
                        textSpan.textContent = `${selectedJurusanCode} ${rVal}`;
                    }

                    if (rVal > maxRombel) {
                        card.style.display = 'none';
                        const input = card.querySelector('input');
                        if (input.checked) {
                            const firstRombelInput = form.querySelector('input[name="nomor_kelas"][value="1"]');
                            if (firstRombelInput) firstRombelInput.checked = true;
                        }
                    } else {
                        card.style.display = 'flex';
                    }
                });

                // Style all radio groups
                const radioGroups = ['tingkat', 'jurusan', 'nomor_kelas'];
                
                radioGroups.forEach(name => {
                    const inputs = form.querySelectorAll(`input[name="${name}"]`);
                    inputs.forEach(input => {
                        const label = input.closest('label');
                        if (!label || label.style.display === 'none') return;
                        
                        const indicator = label.querySelector('.radio-indicator');
                        
                        if (input.checked) {
                            label.classList.add('border-shelf-teal', 'bg-white', 'shadow-md', 'ring-2', 'ring-shelf-teal/40');
                            label.classList.remove('border-shelf-ink/12', 'bg-white/80');
                            if (indicator) {
                                indicator.classList.add('border-shelf-teal', 'bg-shelf-teal');
                                indicator.classList.remove('border-shelf-ink/30');
                            }
                        } else {
                            label.classList.remove('border-shelf-teal', 'bg-white', 'shadow-md', 'ring-2', 'ring-shelf-teal/40');
                            label.classList.add('border-shelf-ink/12', 'bg-white/80');
                            if (indicator) {
                                indicator.classList.remove('border-shelf-teal', 'bg-shelf-teal');
                                indicator.classList.add('border-shelf-ink/30');
                            }
                        }
                    });
                });

                // Update live text preview
                const selectedTingkat = form.querySelector('input[name="tingkat"]:checked')?.value || 'X';
                const selectedJurusan = form.querySelector('input[name="jurusan"]:checked')?.value || 'TJKT';
                const selectedRombel = form.querySelector('input[name="nomor_kelas"]:checked')?.value || '1';

                previewEl.textContent = `${selectedTingkat} ${selectedJurusan} ${selectedRombel}`;
            }

            form.addEventListener('change', updateSelectionStyles);
            updateSelectionStyles();
        });
    </script>
</body>
</html>
