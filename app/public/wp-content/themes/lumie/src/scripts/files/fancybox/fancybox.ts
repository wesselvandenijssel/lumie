import { Fancybox } from "@fancyapps/ui";
import "@fancyapps/ui/dist/fancybox/fancybox.css";

Fancybox.bind("[data-fancybox]", {
	// Basic configuration for version 6.x
	idle: false,
	dragToClose: false,

	Carousel: {
		Toolbar: {
			absolute: false,
			display: {
				left: [],
				middle: [],
				right: ["close", "thumbs", "fullscreen"],
			},
		},
	},
});
