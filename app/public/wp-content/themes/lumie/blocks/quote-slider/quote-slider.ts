import Swiper from "swiper";
import { A11y, Autoplay, Pagination } from "swiper/modules";
import "swiper/css";
import { initAutoplayToggle } from "../../src/scripts/files/autoplay-toggle";

const AUTOPLAY_DELAY = 6000;

const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

const sliders = document.querySelectorAll<HTMLElement>(".quote-slider__slider");

sliders.forEach((slider) => {
	const pagination = slider.querySelector<HTMLElement>(
		".quote-slider__pagination",
	);

	const swiper = new Swiper(slider, {
		modules: [A11y, Autoplay, Pagination],
		slidesPerView: 1,
		spaceBetween: 20,
		loop: true,
		a11y: {
			paginationBulletMessage: "Naar dia {{index}}",
		},
		breakpoints: {
			980: {
				direction: "vertical",
				allowTouchMove: false,
			},
		},
		autoplay: prefersReducedMotion
			? false
			: {
					delay: AUTOPLAY_DELAY,
					disableOnInteraction: false,
					pauseOnMouseEnter: true,
				},
		pagination: pagination
			? {
					el: pagination,
					clickable: true,
				}
			: false,
	});

	const toggle = slider.parentElement?.querySelector<HTMLButtonElement>(".quote-slider__toggle");

	// Without reduced motion the quotes rotate on their own, so visitors need a way to stop them
	if (toggle && !prefersReducedMotion && swiper.slides.length > 1) {
		initAutoplayToggle(
			toggle,
			() => swiper.autoplay.stop(),
			() => swiper.autoplay.start(),
		);
	}
});
