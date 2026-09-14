document.addEventListener("DOMContentLoaded", () => {
	/**
	 * Opening/closing of the widget
	 */
	const mbw = document.querySelector(".mbw");

	if (!mbw) return;

	const mbwContainer = mbw.querySelector(".mbw__container");
	const mbwClose = mbw.querySelector(".mbw__close");
	const mbwToggle = mbw.querySelector(".mbw__toggle");
	const mbwButtons = mbw.querySelectorAll(".mbw-button");
	const mbwFrames = mbw.querySelectorAll(`.mbw__content-frame`);
	const mbwFrameButtons = mbw.querySelectorAll(".mbw-button--content");
	const mbwBtnFrameButtons = mbw.querySelectorAll(".mbw-button--buttons");

	if (!mbwContainer || !mbwClose || !mbwToggle) return;

	const openMbWidget = () => {
		mbwContainer.classList.add("mbw__container--active");
	};

	const closeMbWidget = () => {
		mbwContainer.classList.remove("mbw__container--active");

		// Reset the buttons to their original state
		mbwButtons.forEach((el) => el.classList.remove("mbw-button--hidden"));

		// Reset the frames to their original state
		mbwFrames.forEach((el) =>
			el.classList.remove("mbw__content-frame--active"),
		);
	};

	const toggleMbWidget = () => {
		if (mbwContainer.classList.contains("mbw__container--active")) {
			closeMbWidget();
		} else {
			openMbWidget();
		}
	};

	mbwClose.addEventListener("click", () => {
		closeMbWidget();
	});

	mbwToggle.addEventListener("click", () => {
		toggleMbWidget();
	});

	/**
	 * Opening new content frames
	 */
	mbwFrameButtons.forEach((button) =>
		button.addEventListener("click", () => {
			const frameID = button.dataset.frame;
			const frame = mbw.querySelector(
				`.mbw__content-frame[data-frame*='${frameID}']`,
			);

			if (frame) {
				frame.classList.add("mbw__content-frame--active");
			}

			// Hide the other buttons
			let prev = button.previousElementSibling;
			while (prev) {
				prev.classList.add("mbw-button--hidden");
				prev = prev.previousElementSibling;
			}

			let next = button.nextElementSibling;
			while (next) {
				next.classList.add("mbw-button--hidden");
				next = next.nextElementSibling;
			}
		}),
	);

	/**
	 * Opening new button frames
	 */
	mbwBtnFrameButtons.forEach((button) =>
		button.addEventListener("click", () => {
			const frameID = button.dataset.frame;
			const frame = mbw.querySelector(
				`.mbw__buttons-frame[data-frame*='${frameID}']`,
			);

			if (frame) {
				frame.classList.add("mbw__buttons-frame--active");
			}

			// Hide the other buttons
			let prev = button.previousElementSibling;
			while (prev) {
				prev.classList.add("mbw-button--hidden");
				prev = prev.previousElementSibling;
			}

			let next = button.nextElementSibling;
			while (next) {
				next.classList.add("mbw-button--hidden");
				next = next.nextElementSibling;
			}
		}),
	);

	/**
	 * Opening the widget automatically
	 */
	const mbwHasOpenedAutomatically = sessionStorage.getItem(
		"mbwHasOpenedAutomatically",
	);

	if (!mbwHasOpenedAutomatically) {
		// Open after x amount of page visits
		if (mbw.classList.contains("mbw--page_visits")) {
			let mbwPageVisits =
				parseInt(sessionStorage.getItem("mbwPageVisits")) || 0;
			const pageVisitsTarget = parseInt(mbw.dataset.amount);

			mbwPageVisits++;
			sessionStorage.setItem("mbwPageVisits", mbwPageVisits);

			if (mbwPageVisits === pageVisitsTarget) {
				setTimeout(() => {
					openMbWidget();
					sessionStorage.setItem("mbwHasOpenedAutomatically", true);
				}, 1000);
			}
		}

		// Open after scrolling x percentage on the page
		if (mbw.classList.contains("mbw--scroll_percentage")) {
			const onScrollPercentage = (percentage, callback) => {
				if (percentage < 0 || percentage > 100) return;

				let triggered = false;
				const threshold = percentage / 100;

				window.addEventListener("scroll", () => {
					if (triggered) return;

					const scrollTop =
						window.scrollY || document.documentElement.scrollTop;
					const windowHeight = window.innerHeight;
					const documentHeight =
						document.documentElement.scrollHeight;

					const scrolledPercentage =
						scrollTop / (documentHeight - windowHeight);

					if (scrolledPercentage >= threshold) {
						triggered = true;
						callback();
					}
				});
			};
			const scrollPercentage = mbw.dataset.amount;

			onScrollPercentage(scrollPercentage, () => {
				openMbWidget();
				sessionStorage.setItem("mbwHasOpenedAutomatically", true);
			});
		}

		// Open after x amount of seconds
		if (mbw.classList.contains("mbw--seconds")) {
			const milliseconds = mbw.dataset.amount * 1000;

			setTimeout(() => {
				openMbWidget();
				sessionStorage.setItem("mbwHasOpenedAutomatically", true);
			}, milliseconds);
		}
	}
});
