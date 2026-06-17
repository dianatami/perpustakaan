<!--
==============================================
PARTICLES SYSTEM DEMO & USAGE EXAMPLES
==============================================

File ini menunjukkan berbagai cara untuk menggunakan
dan menyesuaikan sistem partikel dalam proyek.
-->

<!-- CONTOH 1: Include Particles di Halaman Custom -->
<div id="demo-container">
  @include('partials.particles')
  <h1>Halaman dengan Partikel Custom</h1>
  <p>Konten halaman Anda di sini...</p>
</div>

<!-- CONTOH 2: Mengontrol Particles via JavaScript -->
<script>
  // Tunggu DOM ready
  document.addEventListener('DOMContentLoaded', () => {
    // Ubah jumlah partikel
    PARTICLES_UTILS.setParticleCount(80);

    // Ubah opacity
    PARTICLES_UTILS.setOpacity(0.8);

    // Lihat konfigurasi saat ini
    PARTICLES_UTILS.logConfig();
  });
</script>

<!-- CONTOH 3: Mengubah Warna Partikel -->
<script>
  // Ubah warna di PARTICLES_CONFIG sebelum DOMContentLoaded
  window.PARTICLES_CONFIG = {
    ...window.PARTICLES_CONFIG,
    canvas: {
      ...window.PARTICLES_CONFIG.canvas,
      colors: [
        'rgba(255, 107, 107, 0.4)',  // Red
        'rgba(255, 193, 7, 0.35)',   // Yellow
        'rgba(76, 175, 80, 0.35)',   // Green
      ],
    },
  };
</script>

<!-- CONTOH 4: Toggle Particles On/Off -->
<button onclick="PARTICLES_UTILS.toggleParticles(false)">
  Matikan Partikel
</button>

<button onclick="PARTICLES_UTILS.toggleParticles(true)">
  Hidupkan Partikel
</button>

<!-- CONTOH 5: Kustomisasi Per Halaman -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Pastikan particles aktif
    if (!window.particlesEngine) {
      PARTICLES_UTILS.toggleParticles(true);
    }

    // Kustomisasi khusus untuk halaman ini
    setTimeout(() => {
      PARTICLES_UTILS.setParticleCount(100); // Lebih banyak partikel
      PARTICLES_UTILS.setOpacity(0.7);        // Opacity lebih tinggi
    }, 500);
  });
</script>

<!-- CONTOH 6: Responsive Particles -->
<script>
  // Ubah jumlah partikel berdasarkan ukuran layar
  function adjustParticles() {
    const isMobile = window.innerWidth < 768;
    const count = isMobile ? 30 : 80;
    PARTICLES_UTILS.setParticleCount(count);
  }

  window.addEventListener('resize', adjustParticles);
  document.addEventListener('DOMContentLoaded', adjustParticles);
</script>

<!-- CONTOH 7: Disable untuk Halaman Tertentu -->
<script>
  // Jika halaman ini tidak ingin menampilkan particles
  document.addEventListener('DOMContentLoaded', () => {
    const disableParticles = false; // Ubah ke true untuk menonaktifkan
    
    if (disableParticles) {
      PARTICLES_UTILS.toggleParticles(false);
    }
  });
</script>

<!-- CONTOH 8: Animation Event Listener -->
<script>
  // Monitor particles engine jika ada
  window.addEventListener('load', () => {
    if (window.particlesEngine) {
      console.log('✨ Particles engine siap! Instance:', window.particlesEngine);
      
      // Akses method engine
      if (window.particlesEngine.setupPageVisibility) {
        console.log('✓ Page visibility monitoring aktif');
      }
    }
  });
</script>

<!-- CONTOH 9: Kustomisasi CSS Partikel -->
<style>
  /* Override CSS particles untuk tampilan custom */
  
  /* Partikel dengan glow lebih terang */
  .particle-glow {
    filter: blur(0.5px) drop-shadow(0 0 5px currentColor);
  }

  /* Partikel dengan ukuran lebih besar */
  .particle-size-4 {
    width: 10px;
    height: 10px;
  }

  /* Warna custom untuk tema tertentu */
  body.theme-dark #particles-canvas {
    opacity: 0.3;
  }

  body.theme-light #particles-canvas {
    opacity: 0.7;
  }
</style>

<!-- CONTOH 10: DEBUG Mode -->
<script>
  // Aktifkan debug mode untuk melihat info particles
  document.addEventListener('DOMContentLoaded', () => {
    window.PARTICLES_CONFIG.general.debug = true;
    PARTICLES_UTILS.logConfig();
    
    // Monitor performance
    if (window.particlesEngine) {
      console.time('Particles Animation');
      console.timeEnd('Particles Animation');
    }
  });
</script>

<!-- CONTOH 11: Integrasi dengan Event Page -->
<script>
  // Ubah particles saat terjadi event tertentu
  document.addEventListener('DOMContentLoaded', () => {
    // Saat halaman loading
    PARTICLES_UTILS.setOpacity(0.4);

    // Saat loading selesai
    window.addEventListener('load', () => {
      PARTICLES_UTILS.setOpacity(0.6);
    });

    // Saat ada interaksi pengguna
    document.addEventListener('mousemove', () => {
      // Bisa trigger animasi atau change effect di sini
    });
  });
</script>

<!-- CONTOH 12: Menyimpan Preferensi Pengguna -->
<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Ambil preferensi dari localStorage
    const savedConfig = localStorage.getItem('particles-preference');
    
    if (savedConfig) {
      const config = JSON.parse(savedConfig);
      PARTICLES_UTILS.setParticleCount(config.count);
      PARTICLES_UTILS.setOpacity(config.opacity);
    }

    // Simpan preferensi
    function savePreference() {
      const preference = {
        count: window.PARTICLES_CONFIG.canvas.particleCount,
        opacity: window.PARTICLES_CONFIG.canvas.opacity,
      };
      localStorage.setItem('particles-preference', JSON.stringify(preference));
    }

    // Panggil savePreference saat ada perubahan
    // savePreference();
  });
</script>

<!-- NOTES PENTING -->
<!--
1. Selalu include particles partial di dalam <body>:
   @include('partials.particles')

2. Konfigurasikan di window.PARTICLES_CONFIG sebelum DOMContentLoaded

3. Gunakan PARTICLES_UTILS untuk kontrol dinamis

4. Respek preferensi aksesibilitas pengguna (prefers-reduced-motion)

5. Monitor performance, terutama di mobile devices

6. Test di berbagai browser dan perangkat

7. Dokumentasi lengkap di: /PARTICLES_GUIDE.md
-->
