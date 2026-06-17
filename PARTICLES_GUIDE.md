# Particles Animation System 🎆

Sistem animasi partikel yang elegan dan responsif untuk seluruh aplikasi Perpustakaan Sekolah.

## Deskripsi

Particles Animation adalah komponen visual yang menampilkan efek partikel bergerak di seluruh halaman. Efek ini memberikan dimensi visual yang menarik dan modern tanpa mengorbankan performa aplikasi.

## Fitur

- ✨ **Efek Partikel Dinamis**: Partikel yang bergerak secara halus dan elegan
- 📱 **Responsif**: Otomatis menyesuaikan dengan desktop dan mobile
- ⚡ **Performa Tinggi**: Menggunakan Canvas API untuk rendering yang smooth
- 🎨 **Warna Terpadu**: Menggunakan palet warna dari tema proyek
- ♿ **Aksesibel**: Menghormati preferensi `prefers-reduced-motion`
- 🔌 **Modular**: Mudah di-customize dan di-kontrol

## Struktur File

```
resources/
├── css/
│   └── particles.css          # Styling untuk partikel
├── js/
│   └── particles.js           # Logika animasi partikel
└── views/
    └── partials/
        └── particles.blade.php # Template Blade untuk include
```

## Cara Kerja

### 1. **Canvas Particles** (Desktop)
Untuk perangkat desktop (lebih dari 768px), sistem menggunakan:
- **Canvas API** untuk rendering partikel
- **RequestAnimationFrame** untuk animasi smooth 60fps
- Jumlah partikel yang lebih banyak (hingga 60)

### 2. **DOM Particles** (Mobile)
Untuk perangkat mobile (kurang dari 768px), sistem menggunakan:
- **CSS Animations** untuk performa lebih baik
- **DOM elements** yang di-generate secara dinamis
- Jumlah partikel yang lebih sedikit (hingga 40)

### 3. **Aksesibilitas**
Sistem menghormati pengaturan sistem pengguna:
```javascript
const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
```
Jika pengguna mengaktifkan mode "Reduce Motion", animasi akan dinonaktifkan.

## Integrasi

Particles sudah terintegrasi di semua layout utama:

1. **Admin Dashboard**: `resources/views/layout/admin.blade.php`
2. **Portal Anggota/Guru**: `resources/views/layout/anggota.blade.php`
3. **Dashboard Kepala**: `resources/views/layout/kepala.blade.php`
4. **Halaman Login**: `resources/views/tampilan/login.blade.php`
5. **Halaman Register**: `resources/views/tampilan/register.blade.php`
6. **Landing Page**: `resources/views/welcome.blade.php`

Untuk meng-include particles di halaman lain, gunakan:
```blade
@include('partials.particles')
```

## Customization

### Mengubah Jumlah Partikel

Edit `resources/js/particles.js`, cari konfigurasi:

```javascript
this.config = {
  particleCount: 60,        // Desktop
  // ...
};
```

Untuk mobile, di `DOMParticlesEngine`:
```javascript
this.config = {
  particleCount: 40,        // Mobile
  // ...
};
```

### Mengubah Warna Partikel

Edit `resources/js/particles.js`:

```javascript
colors: [
  'rgba(23, 143, 120, 0.4)',    // Teal
  'rgba(255, 122, 89, 0.35)',   // Coral
  'rgba(29, 79, 120, 0.35)',    // Ocean
  'rgba(255, 201, 92, 0.3)',    // Gold
  'rgba(16, 23, 46, 0.25)',     // Ink
  'rgba(247, 242, 232, 0.2)',   // Cloud
],
```

Atau edit kelas CSS di `resources/css/particles.css`:

```css
.particle-teal {
  background: rgba(23, 143, 120, 0.7);
  box-shadow: 0 0 8px rgba(23, 143, 120, 0.4);
}
```

### Mengubah Kecepatan Partikel

Edit `resources/js/particles.js`:

```javascript
speedY: { min: 0.3, max: 1.2 },    // Kecepatan vertikal
speedX: { min: -0.5, max: 0.5 },   // Kecepatan horizontal
```

### Mengubah Opacity Efek

Edit `resources/js/particles.js` di method `startAnimation()`:

```javascript
// Ubah dari 0.6 ke nilai lain (0-1)
this.canvas.style.opacity = '0.6';
```

Atau ubah di CSS `resources/css/particles.css`:

```css
#particles-canvas {
  opacity: 0.6;  /* Ubah nilai ini */
}
```

### Mengubah Durasi Animasi

Edit kelas CSS di `resources/css/particles.css`:

```css
@keyframes particleDrift {
  /* Durasi ditentukan di element dengan animation-duration */
}
```

Atau di JavaScript `resources/js/particles.js`:

```javascript
// Di class DOMParticlesEngine
this.config = {
  particleLifetime: 8000, // dalam milliseconds
  // ...
};
```

## Kontrol Manual

Jika ingin mengontrol particles engine secara manual:

```javascript
// Akses instance global
const engine = window.particlesEngine;

// Ubah jumlah partikel
engine.setParticleCount(80);

// Ubah opacity
engine.setOpacity(0.8);

// Destroy instance
engine.destroy();
```

## Performance Tips

1. **Desktop**: Canvas particles memberikan performa terbaik untuk desktop
2. **Mobile**: DOM particles lebih ringan untuk perangkat mobile
3. **Reduce Motion**: Animasi otomatis dinonaktifkan jika pengguna mengaktifkan mode aksesibilitas
4. **Background Tab**: Canvas particles otomatis di-pause saat tab tidak aktif

## Troubleshooting

### Partikel tidak muncul
1. Pastikan `resources/css/particles.css` dan `resources/js/particles.js` tersimpan dengan benar
2. Pastikan import sudah ditambahkan di `resources/css/app.css` dan `resources/js/app.js`
3. Clear cache: `npm run build`
4. Check browser console untuk error messages

### Partikel terlalu banyak/sedikit
- Ubah nilai `particleCount` di `resources/js/particles.js`
- Ubah `spawnRate` untuk kecepatan spawn partikel

### Partikel bergerak terlalu cepat/lambat
- Ubah `speedY` dan `speedX` di konfigurasi
- Ubah `particleLifetime` untuk durasi hidup partikel

### FPS rendah pada mobile
- Kurangi `particleCount`
- Switch ke `DOMParticlesEngine` (sudah otomatis)
- Kurangi opacity partikel

## Browser Support

- ✅ Chrome/Edge 90+
- ✅ Firefox 88+
- ✅ Safari 14+
- ✅ Mobile browsers dengan Canvas support

## Notes

- Sistem particles berjalan di `z-index: 1` untuk memastikan konten utama di atas (z-index: 10+)
- Partikel menggunakan `pointer-events: none` sehingga tidak mengganggu interaksi pengguna
- Semua animasi menggunakan `requestAnimationFrame` untuk performa optimal

## License

Bagian dari Perpustakaan Sekolah SMKN 1 Tirtamulya
