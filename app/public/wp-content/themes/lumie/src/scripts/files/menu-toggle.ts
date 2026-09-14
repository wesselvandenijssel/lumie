const $all = (selector: string): HTMLElement[] =>
	Array.from(document.querySelectorAll(selector));

const toggleClass = (selector: string, className: string, force?: boolean) => {
	$all(selector).forEach((el) => el.classList.toggle(className, force));
};

/**
 * Opens a submenu element
 *
 * @param submenu The submenu element to be opened
 */
const openSubMenu = (submenu: HTMLElement) => {
	toggleClass(".header__background", "header__background--active", true);

	// Slides the submenu on mobile, on everything else just hides it instantly
	if (window.innerWidth < 740) {
		submenu.slideToggle(400);
	} else {
		submenu.classList.add("sub-menu--active");
	}
};

/**
 * Closes all submenus
 */
const closeSubMenu = () => {
	// Slides the submenus up on mobile
	if (window.innerWidth < 740) {
		$all(".submenu-toggle--active + .sub-menu").forEach((el) => {
			el.slideUp(400);
		});
	}

	// Removes all the classes used for styling the submenu as active
	toggleClass(".header__background", "header__background--active", false);
	toggleClass(".submenu-toggle--active", "submenu-toggle--active", false);
	toggleClass(".sub-menu", "sub-menu--active", false);
	toggleClass(".menu-item-has-children > span", "active", false);
};

/**
 * Menu toggle - Shows/hides the entire menu
 */
$all(".menu-toggle").forEach((toggle) => {
	const toggleMenu = () => {
		toggle.setAttribute(
			"aria-expanded",
			String(!toggle.classList.contains("menu-toggle--active")),
		);
		toggle.classList.toggle("menu-toggle--active");
		document.body.classList.toggle("no-scroll");
		$all(".main-navigation__content").forEach((el) => el.slideToggle(400));
	};

	toggle.addEventListener("click", toggleMenu);

	toggle.addEventListener("keydown", (e) => {
		if (e.repeat) return;

		if (e.key === "Enter" || e.key === " ") {
			e.preventDefault();
			toggleMenu();
		}
	});
});

/**
 * Menu background - Closes the submenu on click
 */
$all(".header__background").forEach((bg) =>
	bg.addEventListener("click", closeSubMenu),
);

/**
 * Main submenu toggle - Handles the first-level submenu toggle, doesn't work for mobile
 */
$all("ul.menu > .menu-item-has-children > span").forEach((span) =>
	span.addEventListener("click", () => {
		if (window.innerWidth < 740) return;

		// Check if this submenu is already opened. If so, close it and stop
		if (span.classList.contains("active")) return closeSubMenu();

		// Close the opened submenu before opening a new one
		closeSubMenu();

		// Target the submenu and open it
		const toggle = span.nextElementSibling;
		const submenu = toggle?.nextElementSibling;

		if (toggle?.classList.contains("submenu-toggle"))
			toggle.classList.add("submenu-toggle--active");

		if (submenu instanceof HTMLElement) {
			openSubMenu(submenu);
			span.classList.add("active");
		}
	}),
);

/**
 * Submenu toggle - Handles all submenu toggles, only works for mobile
 */
$all(".submenu-toggle").forEach((toggle) =>
	toggle.addEventListener("click", () => {
		if (window.innerWidth >= 740) return;

		// Check if this submenu is already opened. If so, close it and stop
		if (toggle.classList.contains("submenu-toggle--active"))
			return closeSubMenu();

		// Target the submenu and open it
		const submenu = toggle.nextElementSibling;
		if (submenu instanceof HTMLElement) {
			// First close the opened submenu before opening a new one, but only when it doesn't contain a visible .back-to-previous
			const submenuBackToPrevious = submenu.querySelector(
				":scope > .back-to-previous",
			);
			let submenuBackToPreviousStyle: CSSStyleDeclaration | null = null;
			if (submenuBackToPrevious instanceof HTMLElement) {
				submenuBackToPreviousStyle = window.getComputedStyle(
					submenuBackToPrevious,
				);
				if (submenuBackToPreviousStyle.display === "none") {
					closeSubMenu();
				}
			}

			openSubMenu(submenu);
			toggle.classList.add("submenu-toggle--active");
		}
	}),
);

/**
 * Submenu back to previous buttons - Close the current submenu and go back to the previous
 */
$all(".menu-item-has-children .back-to-previous").forEach((prevButton) =>
	prevButton.addEventListener("click", () => {
		const prevButtonMenuItem = prevButton.closest(".menu-item");
		if (!prevButtonMenuItem) return;
		const prevButtonToggle = prevButtonMenuItem.querySelector(
			":scope > .submenu-toggle",
		);
		if (!prevButtonToggle) return;
		prevButtonToggle.classList.remove("submenu-toggle--active");
	}),
);
