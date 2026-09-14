// localStorage throws in private mode and when site data is blocked, so every
// access is guarded.
const readStorage = (key: string): string | null => {
	try {
		return localStorage.getItem(key);
	} catch {
		return null;
	}
};

const writeStorage = (key: string, value: string): void => {
	try {
		localStorage.setItem(key, value);
	} catch {
		return;
	}
};

// Keyed per popup, so closing one startup popup does not silence another.
const startupDismissalKey = (popupName: string): string =>
	`startup-popup-dismissed-${popupName}`;

// The popups render inside <main class="site-content">, which has its own
// stacking context (position: relative, z-index: 1). Anything inside it is
// capped at that level, so no z-index could lift a popup above the sticky
// header. Re-parenting to <body> puts them back in the root stacking context.
[".popups", ".popup-background"].forEach((selector) => {
	document.querySelectorAll<HTMLElement>(selector).forEach((el) => {
		if (el.parentElement !== document.body) {
			document.body.appendChild(el);
		}
	});
});

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

	// Both elements: locking only body still lets the root element scroll.
	document.documentElement.classList.add("no-scroll");
	document.body.classList.add("no-scroll");

	setTimeout(() => {
		document
			.querySelector<HTMLElement>(".popup-background")
			?.classList.add("popup-background--show");
	}, 10);
	setTimeout(() => {
		popups.forEach((el) => el.classList.add("popup--show"));

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
				const closeButton =
					activePopup.querySelector<HTMLElement>(".popup__close");
				closeButton?.focus();
			}
		}
	}, 100);
};

/**
 * @param force - Only a successful age verification may close an age gate.
 */
const closePopup = (force: boolean = false) => {
	// An age gate is a legal requirement, so Escape, a background click and the
	// missing close button must all leave it standing.
	if (!force && document.querySelector(".popup--age-gate.popup--active")) {
		return;
	}

	// Recorded before the classes go, so button, background and Escape all count.
	document
		.querySelectorAll<HTMLElement>(".popup--startup.popup--active")
		.forEach((el) => {
			if (el.classList.contains("popup--age-gate")) return;

			const popupName = el.dataset.popup;
			if (popupName) {
				writeStorage(startupDismissalKey(popupName), "1");
			}
		});

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

	document.documentElement.classList.remove("no-scroll");
	document.body.classList.remove("no-scroll");
};

const showPopupButtons = document.querySelectorAll<HTMLElement>(".show-popup");

showPopupButtons.forEach((showPopupButton) => {
	showPopupButton.addEventListener("click", () =>
		openPopup(showPopupButton.dataset.popup ?? "")
	);

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
	closePopupButton.addEventListener("click", () => closePopup());

	if (closePopupButton.classList.contains("popup__close")) {
		closePopupButton.addEventListener("keydown", (e) => {
			if (e.key === "Enter" || e.key === " ") {
				e.preventDefault();
				closePopup();
			}
		});
	}
});

document.addEventListener("keydown", (e) => {
	if (e.key === "Escape") {
		const activePopup = document.querySelector(".popup--active");
		if (activePopup) {
			closePopup();
		}
	}
});

/**
 * A verification is remembered for 30 days: long enough not to nag returning
 * customers, short enough that a shared device does not stay unlocked forever.
 */
const AGE_VERIFIED_KEY = "age-verified-at";
const AGE_VERIFIED_DAYS = 30;
const MINIMUM_AGE = 18;

const isAgeVerified = (): boolean => {
	const verifiedAt = Number(readStorage(AGE_VERIFIED_KEY));
	if (!Number.isFinite(verifiedAt) || verifiedAt <= 0) return false;

	return Date.now() - verifiedAt < AGE_VERIFIED_DAYS * 24 * 60 * 60 * 1000;
};

/**
 * Whole years between a date of birth and today, so someone turning 18 tomorrow
 * still counts as 17.
 */
const yearsSince = (birthDate: Date): number => {
	const today = new Date();
	const monthDiff = today.getMonth() - birthDate.getMonth();
	let age = today.getFullYear() - birthDate.getFullYear();

	if (
		monthDiff < 0 ||
		(monthDiff === 0 && today.getDate() < birthDate.getDate())
	) {
		age--;
	}

	return age;
};

/**
 * Wires up the date-of-birth form. Nothing here trusts the browser's own date
 * validation, because the three fields are plain text inputs.
 */
const setupAgeGate = (popup: HTMLElement): void => {
	const form = popup.querySelector<HTMLFormElement>(".age-gate");
	const error = popup.querySelector<HTMLElement>('[data-age-gate="error"]');
	if (!form || !error) return;

	const valueOf = (name: string): number =>
		Number(
			form.querySelector<HTMLInputElement>(`[data-age-gate="${name}"]`)
				?.value
		);

	const showError = (message: string | undefined) => {
		error.textContent = message ?? "";
		error.hidden = false;
	};

	const inputs = Array.from(
		form.querySelectorAll<HTMLInputElement>(".age-gate__input")
	);

	inputs.forEach((input, index) => {
		input.addEventListener("input", () => {
			// Strip anything non-numeric, otherwise a stray character fills the
			// field without ever advancing.
			input.value = input.value.replace(/\D/g, "");

			if (
				input.value.length >= input.maxLength &&
				index < inputs.length - 1
			) {
				inputs[index + 1].focus();
			}
		});

		input.addEventListener("keydown", (e) => {
			if (e.key === "Backspace" && input.value === "" && index > 0) {
				inputs[index - 1].focus();
			}
		});
	});

	form.addEventListener("submit", (e) => {
		e.preventDefault();

		const day = valueOf("day");
		const month = valueOf("month");
		const year = valueOf("year");

		if (!day || !month || !year) {
			showError(form.dataset.errorIncomplete);
			return;
		}

		const birthDate = new Date(year, month - 1, day);

		// Date rolls impossible input over (31 February becomes 3 March), so the
		// parts are compared back to catch it.
		const isRealDate =
			birthDate.getFullYear() === year &&
			birthDate.getMonth() === month - 1 &&
			birthDate.getDate() === day;

		if (!isRealDate || birthDate.getTime() > Date.now()) {
			showError(form.dataset.errorInvalid);
			return;
		}

		if (yearsSince(birthDate) < MINIMUM_AGE) {
			showError(form.dataset.errorUnderage);
			form.querySelectorAll<HTMLInputElement>(".age-gate__input").forEach(
				(input) => {
					input.value = "";
				}
			);
			return;
		}

		writeStorage(AGE_VERIFIED_KEY, String(Date.now()));
		error.hidden = true;
		popup.classList.remove("popup--age-gate");
		closePopup(true);
	});
};

document
	.querySelectorAll<HTMLElement>(".popup--startup")
	.forEach((startupPopup) => {
		const popupName = startupPopup.dataset.popup;
		if (!popupName) return;

		const isAgeGate = startupPopup.classList.contains("popup--age-gate");

		if (!isAgeGate && readStorage(startupDismissalKey(popupName)) === "1") {
			startupPopup.remove();
			return;
		}

		if (isAgeGate && isAgeVerified()) {
			startupPopup.remove();
			return;
		}

		const seconds = Number(startupPopup.dataset.popupDelay);
		const delay = (Number.isFinite(seconds) ? seconds : 3) * 1000;

		setTimeout(() => openPopup(popupName), delay);

		if (isAgeGate) {
			setupAgeGate(startupPopup);
		}
	});
