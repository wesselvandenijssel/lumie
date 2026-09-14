const footerToggles = document.querySelectorAll<HTMLElement>(
	".footer__menu-fold-button"
);

footerToggles.forEach((footerToggle) =>
	footerToggle.addEventListener("click", () => {
		const currentFooterToggle = footerToggle;
		currentFooterToggle.style.visibility = "hidden";
		currentFooterToggle.setAttribute("aria-expanded", "true");

		const footerFolded =
			currentFooterToggle.previousElementSibling.querySelector<HTMLElement>(
				".folded"
			);

		footerFolded.slideDown(400);
	})
);
