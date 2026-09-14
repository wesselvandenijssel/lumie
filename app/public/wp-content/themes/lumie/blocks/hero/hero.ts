import { scroll } from "motion";

// Respect the reduced-motion preference
const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

// Total vertical travel of the image, as a percentage of its own height.
// The CSS gives the image a 25% overhang top and bottom, so keep this under 50.
const PARALLAX_RANGE = 50;

if (!prefersReducedMotion) {
	const heroMediaWrappers = document.querySelectorAll<HTMLElement>(
		".hero__media-wrapper",
	);

	heroMediaWrappers.forEach((mediaWrapper) => {
		const images = mediaWrapper.querySelectorAll<HTMLElement>("img");

		// Parallax
		images.forEach((image) => {
			if (!image) {
				return;
			}

			scroll(
				(progress: number) => {
					const shift = (progress - 0.5) * PARALLAX_RANGE;
					image.style.transform = `translateY(${shift}%)`;
				},
				{
					target: mediaWrapper,
					offset: ["start end", "end start"],
				},
			);
		});
	});
}

const observer = new MutationObserver((mutations, observerInstance) => {
	const wistiaPlayer = document.querySelector("wistia-player");

	if (wistiaPlayer && wistiaPlayer.shadowRoot) {
		const style = document.createElement("style");
		style.innerText = `
			video {
				object-fit: cover !important;
			}
		`;

		wistiaPlayer.shadowRoot.append(style);

		observerInstance.disconnect();
	}
});

observer.observe(document.body, {
	childList: true,
	subtree: true,
});
