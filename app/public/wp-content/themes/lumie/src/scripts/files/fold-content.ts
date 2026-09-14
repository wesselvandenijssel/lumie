const triggers = document.querySelectorAll<HTMLElement>(
	".fold-content-trigger"
);

triggers.forEach((trigger) =>
	trigger.addEventListener("click", () => {
		const currentTrigger = trigger;
		currentTrigger.style.visibility = "hidden";
		currentTrigger.previousElementSibling?.classList.add(
			"fold-content--active"
		);
	})
);
