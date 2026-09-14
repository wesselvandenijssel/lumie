import { animate, scroll } from "motion";

// Respect the reduced-motion preference
const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

// Total vertical travel of the image, as a percentage of its own height.
// The CSS gives the image a 25% overhang top and bottom, so keep this under 50.
const PARALLAX_RANGE = 50;

if (!prefersReducedMotion) {
	const projectCardXL = document.querySelectorAll<HTMLElement>(
		".project[data-thumbnail='Project XL']",
	);

	projectCardXL.forEach((card) => {
		const image = card.querySelector<HTMLElement>("img");

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
				target: card,
				offset: ["start end", "end start"],
			},
		);

		// Shrink effect
		const shrinkWidth = window.screen.width >= 740 ? "570px" : "220px";

		scroll(
			animate(
				card,
				{
					width: ["100%", shrinkWidth],
				},
				{
					ease: "linear",
				},
			),
			{
				target: card,
				offset: ["start 25px", "start -50vh"],
			},
		);
	});
}
