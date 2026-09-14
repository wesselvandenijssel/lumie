const boundFields = new WeakSet<HTMLInputElement | HTMLTextAreaElement>();

const formFieldLabel = (
	formField: HTMLInputElement | HTMLTextAreaElement,
	formFieldWrapper: HTMLElement | null,
) => {
	const excludedTypes = new Set([
		"gfield--type-list",
		"gfield--type-consent",
	]);

	if (
		!formFieldWrapper ||
		Array.from(excludedTypes).some((type) =>
			formFieldWrapper.classList.contains(type),
		)
	) {
		return;
	}

	const updateFieldState = () => {
		if (formField.value.trim() !== "") {
			formFieldWrapper.classList.add("gfield--active");
		} else {
			formFieldWrapper.classList.remove("gfield--active");
		}
	};

	if (!boundFields.has(formField)) {
		boundFields.add(formField);

		formField.addEventListener("focus", () => {
			formFieldWrapper.classList.add("gfield--active");
		});

		formField.addEventListener("blur", updateFieldState);
	}

	updateFieldState();
};

const applyFormFieldLabels = () => {
	const formFields = document.querySelectorAll<
		HTMLInputElement | HTMLTextAreaElement
	>(
		'.gform_wrapper .gfield:not(.gfield--type-address) input[type="text"], .gform_wrapper .gfield:not(.gfield--type-address) input[type="email"], .gform_wrapper .gfield:not(.gfield--type-address) input[type="tel"], .gform_wrapper .gfield:not(.gfield--type-address) textarea',
	);

	formFields.forEach((formField) =>
		formFieldLabel(formField, formField.closest(".gfield")),
	);

	const formAddressFields = document.querySelectorAll<HTMLInputElement>(
		'.gform_wrapper .gfield--type-address input[type="text"]',
	);

	formAddressFields.forEach((formAddressField) =>
		formFieldLabel(formAddressField, formAddressField.closest("span")),
	);
};

document.addEventListener("DOMContentLoaded", () => {
	applyFormFieldLabels();

	let timer: ReturnType<typeof setTimeout> | undefined;
	const observer = new MutationObserver(() => {
		if (timer !== undefined) {
			clearTimeout(timer);
		}
		timer = setTimeout(applyFormFieldLabels, 50);
	});

	document.querySelectorAll(".gform_wrapper").forEach((wrapper) => {
		observer.observe(wrapper, { childList: true, subtree: true });
	});
});
