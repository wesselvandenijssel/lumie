import Swiper from "swiper";
import { Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/autoplay";

new Swiper(".header__top-wrapper .swiper", {
	modules: [Autoplay],
	slidesPerView: 1,
	loop: true,
	speed: 400,
	spaceBetween: 0,
	autoplay: {
		delay: 5000,
	},
	breakpoints: {
		740: {
			slidesPerView: 2,
		},
		980: {
			slidesPerView: "auto",
			spaceBetween: 25,
		},
	},
});
