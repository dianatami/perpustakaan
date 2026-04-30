import './bootstrap';

const clockFormatter = new Intl.DateTimeFormat('id-ID', {
	hour: '2-digit',
	minute: '2-digit',
	second: '2-digit',
	hour12: false,
});

const dateFormatter = new Intl.DateTimeFormat('id-ID', {
	weekday: 'long',
	day: '2-digit',
	month: 'long',
	year: 'numeric',
});

const updateLiveClocks = () => {
	const clockNodes = document.querySelectorAll('[data-live-clock]');
	const dateNodes = document.querySelectorAll('[data-live-date]');

	if (!clockNodes.length && !dateNodes.length) {
		return;
	}

	const now = new Date();
	const timeText = clockFormatter.format(now);
	const dateText = dateFormatter.format(now);

	clockNodes.forEach((node) => {
		node.textContent = timeText;
	});

	dateNodes.forEach((node) => {
		node.textContent = dateText;
	});
};

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

const initLiveClocks = () => {
	updateLiveClocks();
	window.setInterval(updateLiveClocks, 1000);
};

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', () => {
		initRevealMotion();
		initLiveClocks();
	}, { once: true });
} else {
	initRevealMotion();
	initLiveClocks();
}
