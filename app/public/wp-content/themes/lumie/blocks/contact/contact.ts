const contactButtons = document.querySelectorAll<HTMLElement>(
	".contact__tab-button",
);

contactButtons.forEach((button) => {
	button.addEventListener("click", () => {
		const tabName = button.dataset.tab;
		const section = button.closest("section");

		if (!tabName || !section) return;

		section
			.querySelectorAll<HTMLElement>(".contact__tab-button")
			.forEach((el) => {
				const isActive = el.dataset.tab === tabName;

				el.classList.toggle("contact__tab-button--active", isActive);
				el.setAttribute("aria-selected", isActive ? "true" : "false");
			});

		section
			.querySelectorAll<HTMLElement>(".contact__form-tab")
			.forEach((el) => {
				el.classList.toggle(
					"contact__form-tab--active",
					el.dataset.tab === tabName,
				);
			});
	});
});
