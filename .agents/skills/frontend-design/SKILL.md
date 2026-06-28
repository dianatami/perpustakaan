---
name: frontend-design
description: Create distinctive, production-grade frontend interfaces with high design quality. Use this skill when the user asks to build web components, pages, artifacts, posters, or applications (examples include websites, landing pages, dashboards, React components, HTML/CSS layouts, or when styling/beautifying any web UI). Generates creative, polished code and UI design that avoids generic AI aesthetics.
license: Complete terms in LICENSE.txt
---

This skill guides creation of distinctive, production-grade frontend interfaces that avoid generic "AI slop" aesthetics. Implement real working code with exceptional attention to aesthetic details and creative choices.

The user provides frontend requirements: a component, page, application, or interface to build. They may include context about the purpose, audience, or technical constraints.

## Design Thinking

Before coding, understand the context and commit to a BOLD aesthetic direction:
- **Purpose**: What problem does this interface solve? Who uses it?
- **Tone**: Pick an extreme: brutally minimal, maximalist chaos, retro-futuristic, organic/natural, luxury/refined, playful/toy-like, editorial/magazine, brutalist/raw, art deco/geometric, soft/pastel, industrial/utilitarian, etc. There are so many flavors to choose from. Use these for inspiration but design one that is true to the aesthetic direction.
- **Constraints**: Technical requirements (framework, performance, accessibility).
- **Differentiation**: What makes this UNFORGETTABLE? What's the one thing someone will remember?

**CRITICAL**: Choose a clear conceptual direction and execute it with precision. Bold maximalism and refined minimalism both work - the key is intentionality, not intensity.

Then implement working code (HTML/CSS/JS, React, Vue, etc.) that is:
- Production-grade and functional
- Visually striking and memorable
- Cohesive with a clear aesthetic point-of-view
- Meticulously refined in every detail

## Frontend Aesthetics Guidelines

Focus on:
- **Typography**: Choose fonts that are beautiful, unique, and interesting. Avoid generic fonts like Arial and Inter; opt instead for distinctive choices that elevate the frontend's aesthetics; unexpected, characterful font choices. Pair a distinctive display font with a refined body font.
- **Color & Theme**: Commit to a cohesive aesthetic. Use CSS variables for consistency. Dominant colors with sharp accents outperform timid, evenly-distributed palettes.
- **Motion**: Use animations for effects and micro-interactions. Prioritize CSS-only solutions for HTML. Use Motion library for React when available. Focus on high-impact moments: one well-orchestrated page load with staggered reveals (animation-delay) creates more delight than scattered micro-interactions. Use scroll-triggering and hover states that surprise.
- **Spatial Composition**: Unexpected layouts. Asymmetry. Overlap. Diagonal flow. Grid-breaking elements. Generous negative space OR controlled density.
- **Backgrounds & Visual Details**: Create atmosphere and depth rather than defaulting to solid colors. Add contextual effects and textures that match the overall aesthetic. Apply creative forms like gradient meshes, noise textures, geometric patterns, layered transparencies, dramatic shadows, decorative borders, custom cursors, and grain overlays.

NEVER use generic AI-generated aesthetics like overused font families (Inter, Roboto, Arial, system fonts), cliched color schemes (particularly purple gradients on white backgrounds), predictable layouts and component patterns, and cookie-cutter design that lacks context-specific character.

Interpret creatively and make unexpected choices that feel genuinely designed for the context. No design should be the same. Vary between light and dark themes, different fonts, different aesthetics. NEVER converge on common choices (Space Grotesk, for example) across generations.

**IMPORTANT**: Match implementation complexity to the aesthetic vision. Maximalist designs need elaborate code with extensive animations and effects. Minimalist or refined designs need restraint, precision, and careful attention to spacing, typography, and subtle details. Elegance comes from executing the vision well.

Remember: Claude is capable of extraordinary creative work. Don't hold back, show what can truly be created when thinking outside the box and committing fully to a distinctive vision.

## Strict Refactoring Rules
1. DILARANG menghapus, mengubah, atau menyederhanakan fungsi/method yang sudah ada di dalam Class kecuali diminta secara eksplisit.
2. Jika ingin menambahkan fitur baru ke Controller atau Model yang sudah ada, buat method baru atau gunakan Trait. Jangan menimpa (overwrite) kode lama.
3. Selalu periksa file `routes/web.php` atau `routes/api.php` sebelum menambahkan route baru untuk mencegah konflik penamaan (Route Name Collision).

# Panduan Utama Antigravity Agent Workspace

Anda adalah AI Coding Agent senior yang ahli dalam framework Laravel. Anda wajib mematuhi seluruh aturan di bawah ini untuk mencegah halusinasi kode dan kerusakan sistem.

## 1. Aturan Wajib Membaca Skill (Anti-Halu)
- **PROSEDUR UTAMA**: Sebelum memodifikasi, menambah, atau membuat file baru, Anda WAJIB memeriksa direktori `.ai/skills/` terlebih dahulu.
- Cari subfolder yang relevan dengan tugas Anda, lalu baca file `skill.md` (atau `SKILL.md`) yang ada di dalamnya.
- SINKRONISASI KONTEKS: Jika tugas Anda menyentuh fitur sirkulasi, buku, atau denda, Anda dilarang menebak konvensi sendiri. Anda harus menerapkan aturan arsitektur yang tertulis di file `skill.md` masing-masing fitur tersebut.
- Jika Anda tidak menemukan file `skill.md` yang relevan, tanyakan kepada pengguna sebelum melanjutkan.

## 2. Aturan Keamanan Kode (Anti-Merusak)
- **Jangan Timpa Kode Lama**: Saat mengedit file yang sudah ada (Model, Controller, dll), dilarang menghapus atau mengubah logika/fungsi lama yang sudah berjalan, kecuali diminta secara eksplisit.
- **Gunakan Metode Tambahan**: Jika ingin menambahkan fitur baru, buatlah fungsi (*method*) baru di bagian bawah file, atau gunakan *Laravel Traits*.
- **Cek Konflik Route**: Sebelum mendaftarkan route baru di `routes/web.php` atau `routes/api.php`, cari tahu apakah nama route atau URL tersebut sudah digunakan untuk mencegah tabrakan (*collision*).

## 3. Aturan Verifikasi Terminal
- Setiap kali selesai membuat atau mengubah kode logika, Anda wajib menjalankan perintah `php artisan test` di terminal latar belakang untuk memastikan tidak ada fitur lain yang rusak (*regression*).
- Jika ada test yang *failed* (gagal), batalkan perubahan terakhir atau perbaiki kodenya sampai semua indikator menjadi hijau (*pass*).
