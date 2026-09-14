import Swiper from "swiper";
import { A11y, Autoplay, Pagination } from "swiper/modules";
import "swiper/css";

const AUTOPLAY_DELAY = 6000;

const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

const sliders = document.querySelectorAll<HTMLElement>(".quote-slider__slider");

sliders.forEach((slider) => {
	const pagination = slider.querySelector<HTMLElement>(
		".quote-slider__pagination",
	);

	new Swiper(slider, {
		modules: [A11y, Autoplay, Pagination],
		slidesPerView: 1,
		spaceBetween: 20,
		loop: true,
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
});
