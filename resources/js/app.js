import './bootstrap';

const initRevealMotion = () => {
	const revealNodes = document.querySelectorAll('[data-reveal]');

	if (!revealNodes.length) {
		return;
	}

	const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	if (prefersReducedMotion) {
		revealNodes.forEach((node) => node.classList.add('is-visible'));
		return;
	}

	const observer = new IntersectionObserver(
		(entries, target) => {
			entries.forEach((entry) => {
				if (!entry.isIntersecting) {
					return;
				}

				entry.target.classList.add('is-visible');
				target.unobserve(entry.target);
			});
		},
		{
			rootMargin: '0px 0px -12% 0px',
			threshold: 0.12,
		}
	);

	revealNodes.forEach((node) => observer.observe(node));
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', initRevealMotion, { once: true });
} else {
	initRevealMotion();
}
