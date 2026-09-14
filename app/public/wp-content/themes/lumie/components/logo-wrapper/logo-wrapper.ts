import Swiper from "swiper";
import { Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/autoplay";

new Swiper(".logo-wrapper.swiper", {
	modules: [Autoplay],

	slidesPerView: 2,

	loop: true,

	speed: 5000,

	autoplay: {
		delay: 0,
	},

	spaceBetween: 40,

	breakpoints: {
		739: {
			slidesPerView: 3,
		},
		980: {
			slidesPerView: 1,
		},
	},
});
