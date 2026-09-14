import { scroll } from "motion";

const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

const PARALLAX_RANGE = 50;

if (!prefersReducedMotion) {
	const mediaWrappers = document.querySelectorAll<HTMLElement>(
		".image-banner__media-wrapper",
	);

	mediaWrappers.forEach((mediaWrapper) => {
		const image = mediaWrapper.querySelector<HTMLElement>(
			".image-banner__media--image",
		);

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
