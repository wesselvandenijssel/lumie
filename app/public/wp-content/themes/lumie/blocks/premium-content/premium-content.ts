import { scroll } from "motion";

// Respect the reduced-motion preference
const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

// Total vertical travel of the image, as a percentage of its own height.
// The CSS gives the image a 25% overhang top and bottom, so keep this under 50.
const PARALLAX_RANGE = 50;

if (!prefersReducedMotion) {
	const premiumContentImageWrappers = document.querySelectorAll<HTMLElement>(
		".premium-content__image-wrapper",
	);

	premiumContentImageWrappers.forEach((imageWrapper) => {
		const image = imageWrapper.querySelector<HTMLElement>("img");

		if (!image) {
			return;
		}

		// Parallax
		scroll(
			(progress: number) => {
				const shift = (progress - 0.5) * PARALLAX_RANGE;
				image.style.transform = `translateY(${shift}%)`;
			},
			{
				target: imageWrapper,
				offset: ["start end", "end start"],
			},
		);
	});
}
