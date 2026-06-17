/**
 * ============================================
 * PARTICLES ANIMATION ENGINE
 * ============================================
 * 
 * Sistem animasi partikel yang elegan dan responsif
 * untuk seluruh aplikasi
 */

class ParticlesEngine {
  constructor(containerId = 'particles-canvas') {
    this.canvas = document.getElementById(containerId) || this.createCanvas(containerId);
    this.ctx = this.canvas.getContext('2d');
    this.particles = [];
    this.config = {
      particleCount: 60,
      particleLife: 8000, // ms
      particleMinSize: 0.5,
      particleMaxSize: 2.5,
      colors: [
        'rgba(23, 143, 120, 0.4)',    // Teal
        'rgba(255, 122, 89, 0.35)',   // Coral
        'rgba(29, 79, 120, 0.35)',    // Ocean
        'rgba(255, 201, 92, 0.3)',    // Gold
        'rgba(16, 23, 46, 0.25)',     // Ink
        'rgba(247, 242, 232, 0.2)',   // Cloud
      ],
      speedY: { min: 0.3, max: 1.2 },
      speedX: { min: -0.5, max: 0.5 },
      spawnRate: 3, // partikel per frame
    };

    this.setupCanvas();
    this.startAnimation();
    this.setupResizeListener();
  }

  createCanvas(id) {
    const canvas = document.createElement('canvas');
    canvas.id = id;
    canvas.style.cssText = `
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
      pointer-events: none;
      opacity: 0.6;
    `;
    document.body.insertBefore(canvas, document.body.firstChild);
    return canvas;
  }

  setupCanvas() {
    this.canvas.width = window.innerWidth;
    this.canvas.height = window.innerHeight;
  }

  setupResizeListener() {
    let resizeTimeout;
    window.addEventListener('resize', () => {
      clearTimeout(resizeTimeout);
      resizeTimeout = setTimeout(() => {
        this.setupCanvas();
      }, 200);
    });
  }

  spawnParticle() {
    const size = Math.random() * (this.config.particleMaxSize - this.config.particleMinSize) + this.config.particleMinSize;
    const x = Math.random() * this.canvas.width;
    const y = this.canvas.height + size;

    const particle = {
      x,
      y,
      size,
      vx: this.config.speedX.min + Math.random() * (this.config.speedX.max - this.config.speedX.min),
      vy: -(this.config.speedY.min + Math.random() * (this.config.speedY.max - this.config.speedY.min)),
      color: this.config.colors[Math.floor(Math.random() * this.config.colors.length)],
      life: this.config.particleLife,
      maxLife: this.config.particleLife,
      rotation: Math.random() * Math.PI * 2,
      rotationSpeed: (Math.random() - 0.5) * 0.05,
    };

    this.particles.push(particle);
  }

  updateParticles(deltaTime) {
    this.particles = this.particles.filter(p => p.life > 0);

    for (let i = 0; i < this.config.spawnRate; i++) {
      if (this.particles.length < this.config.particleCount) {
        this.spawnParticle();
      }
    }

    this.particles.forEach(particle => {
      particle.x += particle.vx;
      particle.y += particle.vy;
      particle.life -= 16; // Approximate to 60fps
      particle.rotation += particle.rotationSpeed;

      // Tambah efek percepatan saat mendekati akhir hidup
      if (particle.life < 1000) {
        particle.vy *= 1.01;
      }

      // Sedikit wave motion
      particle.vx += Math.sin(particle.rotation) * 0.02;
    });
  }

  drawParticles() {
    this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

    this.particles.forEach(particle => {
      const opacity = Math.max(0, particle.life / particle.maxLife);
      const alpha = this.extractAlpha(particle.color);
      const colorWithOpacity = particle.color.replace(/[\d.]+\)$/g, `${alpha * opacity})`);

      this.ctx.fillStyle = colorWithOpacity;
      this.ctx.beginPath();
      this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
      this.ctx.fill();

      // Tambah glow effect untuk partikel besar
      if (particle.size > 1.5) {
        this.ctx.strokeStyle = colorWithOpacity.replace(/[\d.]+\)$/g, `${alpha * opacity * 0.5})`);
        this.ctx.lineWidth = particle.size * 0.5;
        this.ctx.beginPath();
        this.ctx.arc(particle.x, particle.y, particle.size + 1, 0, Math.PI * 2);
        this.ctx.stroke();
      }
    });
  }

  extractAlpha(colorStr) {
    const match = colorStr.match(/[\d.]+\)$/);
    return match ? parseFloat(match[0]) : 1;
  }

  animate() {
    this.updateParticles(16);
    this.drawParticles();
    requestAnimationFrame(() => this.animate());
  }

  startAnimation() {
    this.animate();
  }

  setParticleCount(count) {
    this.config.particleCount = count;
  }

  setOpacity(opacity) {
    this.canvas.style.opacity = opacity;
  }

  // Pause animasi saat tab tidak aktif
  setupPageVisibility() {
    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        this.canvas.style.opacity = '0';
      } else {
        this.canvas.style.opacity = '0.6';
      }
    });
  }

  // Destroy instance
  destroy() {
    if (this.canvas && this.canvas.parentNode) {
      this.canvas.parentNode.removeChild(this.canvas);
    }
  }
}

/**
 * DOM-Based Particles (CSS Animations)
 * Alternatif lebih ringan menggunakan CSS animations
 */
class DOMParticlesEngine {
  constructor(containerId = 'particles-container') {
    this.container = document.getElementById(containerId) || this.createContainer(containerId);
    this.config = {
      particleCount: 40,
      particleLifetime: 8000,
      spawnRate: 2,
    };

    this.colorClasses = [
      'particle-teal',
      'particle-coral',
      'particle-ocean',
      'particle-gold',
      'particle-ink',
      'particle-cloud',
    ];

    this.sizeClasses = ['particle-size-1', 'particle-size-2', 'particle-size-3', 'particle-size-4'];
    this.animationClasses = ['particle-animation-1', 'particle-animation-2', 'particle-animation-3'];

    this.startSpawning();
  }

  createContainer(id) {
    const container = document.createElement('div');
    container.id = id;
    container.className = 'particles-container';
    document.body.insertBefore(container, document.body.firstChild);
    return container;
  }

  createParticle() {
    const particle = document.createElement('div');
    const colorClass = this.colorClasses[Math.floor(Math.random() * this.colorClasses.length)];
    const sizeClass = this.sizeClasses[Math.floor(Math.random() * this.sizeClasses.length)];
    const animationClass = this.animationClasses[Math.floor(Math.random() * this.animationClasses.length)];

    const startX = Math.random() * window.innerWidth;
    const driftX = (Math.random() - 0.5) * 200;
    const delay = Math.random() * 0.5;
    const duration = 6 + Math.random() * 4;

    particle.className = `particle ${colorClass} ${sizeClass} ${animationClass}`;
    particle.style.cssText = `
      left: ${startX}px;
      top: ${window.innerHeight}px;
      --drift-x: ${driftX}px;
      animation-duration: ${duration}s;
      animation-delay: ${delay}s;
    `;

    this.container.appendChild(particle);

    setTimeout(() => {
      particle.remove();
    }, (duration + delay) * 1000);
  }

  startSpawning() {
    setInterval(() => {
      for (let i = 0; i < this.config.spawnRate; i++) {
        if (this.container.children.length < this.config.particleCount) {
          this.createParticle();
        }
      }
    }, 500);
  }

  destroy() {
    if (this.container && this.container.parentNode) {
      this.container.parentNode.removeChild(this.container);
    }
  }
}

/**
 * Initialize Function
 */
function initializeParticles() {
  try {
    // Cek preferensi performance
    const isMobile = window.innerWidth < 768;
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (prefersReducedMotion) {
      console.log('✓ Particle animations disabled due to system preferences');
      return;
    }

    // Tunggu hingga canvas element tersedia
    const canvas = document.getElementById('particles-canvas');
    const container = document.getElementById('particles-container');

    if (!canvas && !container) {
      console.warn('⚠ Particles containers not found, retrying...');
      setTimeout(initializeParticles, 500);
      return;
    }

    // Gunakan Canvas particles untuk desktop, DOM particles untuk mobile
    if (isMobile) {
      if (window.DOMParticlesEngine) {
        window.particlesEngine = new DOMParticlesEngine();
        console.log('✓ DOM Particles Engine initialized (Mobile)');
      }
    } else {
      if (window.ParticlesEngine) {
        window.particlesEngine = new ParticlesEngine();
        console.log('✓ Canvas Particles Engine initialized (Desktop)');
      }
    }

    // Log success
    if (window.particlesEngine) {
      console.log('✓ Particles system is active', window.particlesEngine);
    }
  } catch (error) {
    console.error('✗ Error initializing particles:', error);
  }
}

/**
 * Export untuk penggunaan manual
 */
if (typeof window !== 'undefined') {
  window.ParticlesEngine = ParticlesEngine;
  window.DOMParticlesEngine = DOMParticlesEngine;

  // Initialize - try multiple approaches
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeParticles);
  } else {
    // DOM sudah loaded
    setTimeout(initializeParticles, 100);
  }

  // Also expose initialization function
  window.initializeParticles = initializeParticles;
}
