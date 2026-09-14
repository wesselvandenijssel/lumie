import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/autoplay";

document.addEventListener("DOMContentLoaded", () => {
	const selects = document.querySelectorAll(
		".blog__filter-select",
	) as NodeListOf<HTMLSelectElement>;

	selects.forEach((select) => {
		select.addEventListener("change", () => {
			const form = select.closest("form");
			if (form) {
				form.submit();
			}
		});
	});
});

new Swiper(".blog__grid.swiper", {
	modules: [Navigation],
	slidesPerView: "auto",
	spaceBetween: 10,
	navigation: {
		nextEl: ".blog__swiper-button--next",
		prevEl: ".blog__swiper-button--prev",
	},

	breakpoints: {
		740: {
			spaceBetween: 20,
		},

		980: {
			spaceBetween: 40,
		},
	},
});
