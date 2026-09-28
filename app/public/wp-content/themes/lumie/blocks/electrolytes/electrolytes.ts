import Swiper from "swiper";
import { A11y, Pagination } from "swiper/modules";
import "swiper/css";

const sliders = document.querySelectorAll<HTMLElement>(".electrolytes__slider");

sliders.forEach((slider) => {
	const slideCount = slider.querySelectorAll(".swiper-slide").length;

	new Swiper(slider, {
		modules: [A11y, Pagination],
		slidesPerView: 1.15,
		spaceBetween: 12,
		pagination: {
			el: slider.parentElement?.querySelector<HTMLElement>(".electrolytes__pagination"),
			clickable: true,
		},
		breakpoints: {
			740: {
				enabled: false,
				slidesPerView: slideCount,
				spaceBetween: 16,
			},
		},
	});
});
