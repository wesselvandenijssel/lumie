/**
 * @param popupName	The data-popup attribute associated with the popup
 */
const openPopup = (popupName: string) => {
	if (!popupName) return;

	const popups = document.querySelectorAll<HTMLElement>(
		`.popup[data-popup*='${popupName}']`
	);
	popups.forEach((el) => el.classList.add("popup--active"));

	document
		.querySelectorAll<HTMLElement>(`.popup-background`)
		.forEach((el) => el.classList.add("popup-background--active"));

	document.querySelector("body")?.classList.add("no-scroll");

	setTimeout(() => {
		document
			.querySelector<HTMLElement>(".popup-background")
			?.classList.add("popup-background--show");
	}, 10);
	setTimeout(() => {
		popups.forEach((el) => el.classList.add("popup--show"));

		// Set focus to first focusable element in popup for accessibility
		const activePopup = document.querySelector<HTMLElement>(
			`.popup[data-popup*='${popupName}'].popup--active`
		);
		if (activePopup) {
			const firstFocusable = activePopup.querySelector<HTMLElement>(
				'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
			);
			if (firstFocusable) {
				firstFocusable.focus();
			} else {
				// If no focusable element, focus the close button
				const closeButton =
					activePopup.querySelector<HTMLElement>(".popup__close");
				closeButton?.focus();
			}
		}
	}, 100);
};

const closePopup = () => {
	// Then close the popups
	document
		.querySelectorAll<HTMLElement>(".popup")
		.forEach((el) => el.classList.remove("popup--active", "popup--show"));

	document
		.querySelectorAll<HTMLElement>(".popup-background")
		.forEach((el) =>
			el.classList.remove(
				"popup-background--active",
				"popup-background--show"
			)
		);

	document.querySelector("body")?.classList.remove("no-scroll");
};

const showPopupButtons = document.querySelectorAll<HTMLElement>(".show-popup");

showPopupButtons.forEach((showPopupButton) => {
	// Click event
	showPopupButton.addEventListener("click", () =>
		openPopup(showPopupButton.dataset.popup ?? "")
	);

	// Keyboard event for accessibility
	showPopupButton.addEventListener("keydown", (e) => {
		if (e.key === "Enter" || e.key === " ") {
			e.preventDefault();
			openPopup(showPopupButton.dataset.popup ?? "");
		}
	});
});

const closePopupButtons = document.querySelectorAll<HTMLElement>(
	".popup__close, .popup-background"
);

closePopupButtons.forEach((closePopupButton) => {
	// Click event
	closePopupButton.addEventListener("click", () => closePopup());

	// Keyboard event for accessibility (only for close buttons, not background)
	if (closePopupButton.classList.contains("popup__close")) {
		closePopupButton.addEventListener("keydown", (e) => {
			if (e.key === "Enter" || e.key === " ") {
				e.preventDefault();
				closePopup();
			}
		});
	}
});

// Close popup on Escape key press
document.addEventListener("keydown", (e) => {
	if (e.key === "Escape") {
		const activePopup = document.querySelector(".popup--active");
		if (activePopup) {
			closePopup();
		}
	}
});
