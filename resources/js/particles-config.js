/**
 * ============================================
 * PARTICLES CONFIGURATION FILE
 * ============================================
 * 
 * File ini untuk mempermudah kustomisasi efek partikel
 * Ubah nilai-nilai di bawah sesuai kebutuhan Anda
 * 
 * Dokumentasi lengkap: /PARTICLES_GUIDE.md
 */

window.PARTICLES_CONFIG = {
  /**
   * KONFIGURASI CANVAS PARTICLES (Desktop)
   * =====================================
   */
  canvas: {
    // Jumlah partikel yang ditampilkan
    // Semakin banyak = lebih indah tapi lebih berat
    // Desktop: 40-100, Mobile: 20-40
    particleCount: 60,

    // Durasi hidup partikel dalam milliseconds
    particleLife: 8000,

    // Ukuran minimum partikel
    particleMinSize: 0.5,

    // Ukuran maksimum partikel
    particleMaxSize: 2.5,

    // Kecepatan vertikal (Y)
    // { min: minimum speed, max: maximum speed }
    // Semakin besar = semakin cepat naik
    speedY: { min: 0.3, max: 1.2 },

    // Kecepatan horizontal (X)
    // Nilai negative = bergerak ke kiri, positive = ke kanan
    speedX: { min: -0.5, max: 0.5 },

    // Berapa banyak partikel yang di-spawn per frame
    // Semakin besar = partikel muncul lebih sering
    spawnRate: 3,

    // Opacity (transparansi) canvas
    // 0 = transparan penuh, 1 = fully opaque
    opacity: 0.6,

    // Warna-warna partikel (RGBA format)
    colors: [
      'rgba(23, 143, 120, 0.4)',    // Teal (primary)
      'rgba(255, 122, 89, 0.35)',   // Coral (accent)
      'rgba(29, 79, 120, 0.35)',    // Ocean (secondary)
      'rgba(255, 201, 92, 0.3)',    // Gold (highlight)
      'rgba(16, 23, 46, 0.25)',     // Ink (dark)
      'rgba(247, 242, 232, 0.2)',   // Cloud (light)
    ],
  },

  /**
   * KONFIGURASI DOM PARTICLES (Mobile)
   * ===================================
   */
  dom: {
    // Jumlah partikel untuk mobile
    // Lebih sedikit dari desktop untuk performa
    particleCount: 40,

    // Durasi hidup partikel dalam milliseconds
    particleLifetime: 8000,

    // Berapa banyak partikel yang di-spawn setiap 500ms
    spawnRate: 2,
  },

  /**
   * KONFIGURASI UMUM
   * =================
   */
  general: {
    // Breakpoint untuk menentukan desktop vs mobile (pixels)
    mobileBreakpoint: 768,

    // Tentukan apakah particles ditampilkan
    // Ubah ke false untuk menonaktifkan sepenuhnya
    enabled: true,

    // Gunakan Canvas untuk desktop
    useCanvasOnDesktop: true,

    // Gunakan DOM untuk mobile
    useDOMOnMobile: true,

    // Hormati preferensi 'prefers-reduced-motion' dari sistem
    respectReducedMotion: true,

    // Debug mode (tampilkan info di console)
    debug: false,
  },

  /**
   * KONFIGURASI VISUAL
   * ===================
   */
  visual: {
    // Efek glow pada partikel
    enableGlow: true,

    // Blur effect pada partikel
    blurAmount: 0.5,

    // Shadow effect (hanya canvas)
    enableShadow: true,

    // Wave motion untuk partikel (hanya canvas)
    enableWaveMotion: true,

    // Variasi rotasi partikel (hanya canvas)
    enableRotation: true,
  },

  /**
   * KONFIGURASI AKSESIBILITAS
   * ==========================
   */
  accessibility: {
    // Pause animasi saat tab tidak aktif
    pauseWhenTabInactive: true,

    // Reduce opacity untuk dark backgrounds
    reducedOpacityOnDarkBg: true,

    // Default opacity untuk mode reduced motion
    reducedMotionOpacity: 0,
  },
};

/**
 * Helper functions untuk menggunakan konfigurasi
 */
window.PARTICLES_UTILS = {
  /**
   * Mendapatkan konfigurasi berdasarkan device
   */
  getConfig() {
    const isMobile = window.innerWidth < window.PARTICLES_CONFIG.general.mobileBreakpoint;
    return isMobile ? window.PARTICLES_CONFIG.dom : window.PARTICLES_CONFIG.canvas;
  },

  /**
   * Mengubah jumlah partikel dinamis
   */
  setParticleCount(count) {
    if (window.particlesEngine && typeof window.particlesEngine.setParticleCount === 'function') {
      window.particlesEngine.setParticleCount(count);
    }
  },

  /**
   * Mengubah opacity dinamis
   */
  setOpacity(opacity) {
    if (window.particlesEngine && typeof window.particlesEngine.setOpacity === 'function') {
      window.particlesEngine.setOpacity(opacity);
    }
  },

  /**
   * Mengaktifkan/menonaktifkan particles
   */
  toggleParticles(enable) {
    if (enable && !window.particlesEngine) {
      // Initialize if not already done
      const isMobile = window.innerWidth < window.PARTICLES_CONFIG.general.mobileBreakpoint;
      if (!isMobile && window.ParticlesEngine) {
        window.particlesEngine = new window.ParticlesEngine();
      } else if (window.DOMParticlesEngine) {
        window.particlesEngine = new window.DOMParticlesEngine();
      }
    } else if (!enable && window.particlesEngine) {
      window.particlesEngine.destroy();
      window.particlesEngine = null;
    }
  },

  /**
   * Log konfigurasi untuk debugging
   */
  logConfig() {
    console.group('🎆 Particles Configuration');
    console.log('General:', window.PARTICLES_CONFIG.general);
    console.log('Canvas:', window.PARTICLES_CONFIG.canvas);
    console.log('DOM:', window.PARTICLES_CONFIG.dom);
    console.log('Visual:', window.PARTICLES_CONFIG.visual);
    console.groupEnd();
  },
};

// Auto-log pada development
if (window.PARTICLES_CONFIG.general.debug) {
  document.addEventListener('DOMContentLoaded', () => {
    console.log('✨ Particles system initialized with config:', window.PARTICLES_CONFIG);
  });
}
