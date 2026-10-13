import Swiper from "swiper";
import { A11y, Autoplay, Pagination } from "swiper/modules";
import "swiper/css";
import { initAutoplayToggle, isAutoplayPaused } from "../../src/scripts/files/autoplay-toggle";

/**
 * The parts of the <wistia-player> web component API used here
 */
interface WistiaPlayer extends HTMLElement {
	muted: boolean;
	play(): Promise<void> | void;
	pause(): Promise<void> | void;
}

const AUTOPLAY_DELAY = 2000;

const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

const carousels = document.querySelectorAll<HTMLElement>(".carousel");

carousels.forEach((carousel) => {
	const slider = carousel.querySelector<HTMLElement>(".carousel__slider");
	const pagination = carousel.querySelector<HTMLElement>(".carousel__pagination");

	if (!slider) {
		return;
	}

	const players = slider.querySelectorAll<WistiaPlayer>("wistia-player");
	const hasMultipleSlides = slider.querySelectorAll(".swiper-slide").length > 1;
	const toggle = carousel.querySelector<HTMLButtonElement>(".carousel__toggle");
	let paused = isAutoplayPaused();

	// Loop the videos so a short clip doesn't end on a still frame before the slide changes
	players.forEach((player) => {
		if (!player.hasAttribute("end-video-behavior")) {
			player.setAttribute("end-video-behavior", "loop");
		}
	});

	// Swiper's loop mode clones slides, which would duplicate the Wistia players; rewind avoids that
	const swiper = new Swiper(slider, {
		modules: [A11y, Autoplay, Pagination],
		slidesPerView: 1,
		spaceBetween: 20,
		speed: 600,
		rewind: true,
		a11y: {
			paginationBulletMessage: "Naar dia {{index}}",
		},
		autoplay:
			hasMultipleSlides && !prefersReducedMotion
				? {
						delay: AUTOPLAY_DELAY,
						disableOnInteraction: false,
						pauseOnMouseEnter: true,
					}
				: false,
		pagination: pagination
			? {
					el: pagination,
					clickable: true,
				}
			: false,
	});

	/**
	 * Plays the Wistia video on the active slide and pauses the rest
	 *
	 * @returns void
	 */
	const syncVideos = (): void => {
		const activeSlide = swiper.slides[swiper.activeIndex];

		players.forEach((player) => {
			const shouldPlay = !prefersReducedMotion && !paused && activeSlide?.contains(player);

			// play() rejects when the browser blocks autoplay; the slide then just shows the poster
			Promise.resolve(shouldPlay ? player.play() : player.pause()).catch(() => {});
		});
	};

	swiper.on("slideChange", syncVideos);

	// Without reduced motion the slides and videos move on their own, so visitors need a way to stop them
	if (toggle && !prefersReducedMotion) {
		initAutoplayToggle(
			toggle,
			() => {
				paused = true;
				swiper.autoplay.stop();
				syncVideos();
			},
			() => {
				paused = false;

				if (hasMultipleSlides) {
					swiper.autoplay.start();
				}

				syncVideos();
			},
		);
	}

	if (!players.length) {
		return;
	}

	// player.js loads async, so wait until the web component is registered before using its API
	window.customElements.whenDefined("wistia-player").then(() => {
		players.forEach((player) => {
			player.muted = true;

			// The video lives in the player's shadow DOM, out of reach of the theme CSS
			if (player.shadowRoot) {
				const style = document.createElement("style");
				style.textContent = "video { object-fit: cover !important; }";
				player.shadowRoot.append(style);
			}

			// Wistia's own autoplay setting can start videos on hidden slides; stop those
			player.addEventListener("play", () => {
				const activeSlide = swiper.slides[swiper.activeIndex];

				if (prefersReducedMotion || paused || !activeSlide?.contains(player)) {
					Promise.resolve(player.pause()).catch(() => {});
				}
			});
		});

		syncVideos();
	});
});
