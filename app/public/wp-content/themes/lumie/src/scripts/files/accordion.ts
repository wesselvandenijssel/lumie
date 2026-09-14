const accordions = document.querySelectorAll<HTMLElement>(
	".accordion__question"
);

// Add role and tabindex for accessibility
accordions.forEach((accordion) => {
	// Set initial aria-expanded state
	const accordionParent = accordion.closest(".accordion");
	const isExpanded =
		accordionParent?.classList.contains("accordion--active") || false;
	accordion.setAttribute("aria-expanded", String(isExpanded));
});

const toggleAccordion = (accordion: HTMLElement) => {
	const accordionParent = accordion.closest(".accordion");
	if (!accordionParent) return;

	// Only reset the active state if the clicked accordion isn't active yet
	if (!accordionParent.classList.contains("accordion--active")) {
		document
			.querySelectorAll<HTMLElement>(
				".accordion--active .accordion__answer"
			)
			.forEach((el) => {
				el.slideUp(400);
			});

		document.querySelectorAll(".accordion--active").forEach((el) => {
			el.classList.remove("accordion--active");
			// Update aria-expanded for other accordions
			const question = el.querySelector<HTMLElement>(
				".accordion__question"
			);
			if (question) {
				question.setAttribute("aria-expanded", "false");
			}
		});
	}

	// Always toggle the active state of the clicked accordion
	accordionParent.classList.toggle("accordion--active");
	const isExpanded = accordionParent.classList.contains("accordion--active");
	accordion.setAttribute("aria-expanded", String(isExpanded));

	const nextElement = accordion.nextElementSibling as HTMLElement;

	if (nextElement) {
		nextElement.slideToggle(400);
	}
};

accordions.forEach((accordion) => {
	// Click event
	accordion.addEventListener("click", () => toggleAccordion(accordion));

	// Keyboard navigation: Enter/Space to toggle, Escape to close
	accordion.addEventListener("keydown", (e) => {
		if (e.key === "Enter" || e.key === " ") {
			e.preventDefault();
			toggleAccordion(accordion);
		}

		// Close accordion with Escape key
		if (e.key === "Escape") {
			const accordionParent = accordion.closest(".accordion");
			if (accordionParent?.classList.contains("accordion--active")) {
				e.preventDefault();
				toggleAccordion(accordion);
			}
		}
	});
});
