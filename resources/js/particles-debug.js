/**
 * ================================================
 * PARTICLES DEBUG & TESTING SCRIPT
 * ================================================
 * 
 * Jalankan script ini di browser console (F12) untuk debug
 */

// 1. Check apakah particles sudah di-load
console.log('%c🔍 PARTICLES SYSTEM DEBUG', 'font-size: 14px; font-weight: bold; color: #178f78;');
console.log('='.repeat(50));

// Check canvas
const canvas = document.getElementById('particles-canvas');
console.log('✓ Canvas element:', canvas ? '✅ Found' : '❌ Missing');
if (canvas) {
  console.log('  - Size:', canvas.width, 'x', canvas.height);
  console.log('  - Opacity:', canvas.style.opacity);
}

// Check container
const container = document.getElementById('particles-container');
console.log('✓ Container element:', container ? '✅ Found' : '❌ Missing');

// Check engines
console.log('✓ ParticlesEngine:', window.ParticlesEngine ? '✅ Loaded' : '❌ Missing');
console.log('✓ DOMParticlesEngine:', window.DOMParticlesEngine ? '✅ Loaded' : '❌ Missing');

// Check instance
console.log('✓ Particles Engine Instance:', window.particlesEngine ? '✅ Active' : '❌ Not initialized');
if (window.particlesEngine) {
  console.log('  - Type:', window.particlesEngine.constructor.name);
  console.log('  - Particles count:', window.particlesEngine.particles?.length || 'N/A');
}

// Check config
console.log('✓ Config:', window.PARTICLES_CONFIG ? '✅ Available' : '❌ Missing');

// Check utilities
console.log('✓ Utilities:', window.PARTICLES_UTILS ? '✅ Available' : '❌ Missing');

console.log('='.repeat(50));

// 2. Manual initialization if needed
if (!window.particlesEngine && window.initializeParticles) {
  console.log('%c🚀 Initializing particles manually...', 'color: #ff7a59;');
  window.initializeParticles();
  setTimeout(() => {
    console.log('✓ Initialization complete:', window.particlesEngine ? '✅' : '❌');
  }, 500);
}

// 3. Quick commands for testing
console.log('%c💡 QUICK COMMANDS', 'font-size: 12px; font-weight: bold; color: #1d4f78;');
console.log('='.repeat(50));
console.log('// Ubah jumlah partikel:');
console.log('PARTICLES_UTILS.setParticleCount(100)');
console.log('');
console.log('// Ubah opacity:');
console.log('PARTICLES_UTILS.setOpacity(0.8)');
console.log('');
console.log('// Toggle on/off:');
console.log('PARTICLES_UTILS.toggleParticles(false)');
console.log('PARTICLES_UTILS.toggleParticles(true)');
console.log('');
console.log('// Lihat konfigurasi:');
console.log('PARTICLES_UTILS.logConfig()');
console.log('');
console.log('// Destroy dan reinit:');
console.log('window.particlesEngine.destroy()');
console.log('window.initializeParticles()');
console.log('='.repeat(50));

// 4. Auto-test
console.log('%c🧪 RUNNING AUTO-TEST', 'font-size: 12px; font-weight: bold; color: #2ab59a;');
setTimeout(() => {
  if (window.particlesEngine) {
    console.log('✅ Particles engine is running!');
    console.log('✅ You should see particles animating on your screen');
  } else {
    console.error('❌ Particles engine failed to initialize');
    console.error('Possible causes:');
    console.error('  1. Build not run (npm run build)');
    console.error('  2. Canvas/Container not in DOM');
    console.error('  3. prefers-reduced-motion is enabled');
  }
}, 1000);
