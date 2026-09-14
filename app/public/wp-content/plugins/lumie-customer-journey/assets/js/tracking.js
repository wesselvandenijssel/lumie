(function () {
	"use strict";

	const STORAGE_KEY = "mb_customer_journey";
	const MAX_ENTRIES = 50;

	// mbCJ is injected by wp_localize_script from the server side so it always
	// reflects the real canonical path even on cached pages.
	const currentPath =
		(window.mbCJ && window.mbCJ.path) || window.location.pathname;
	const currentTitle = (window.mbCJ && window.mbCJ.title) || document.title;

	function readJourney() {
		try {
			return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
		} catch (e) {
			return [];
		}
	}

	function saveJourney(journey) {
		try {
			localStorage.setItem(STORAGE_KEY, JSON.stringify(journey));
		} catch (e) {
			// localStorage quota exceeded or unavailable — degrade silently.
		}
	}

	function track() {
		let journey = readJourney();

		const entry = {
			path: currentPath,
			title: currentTitle,
			url: window.location.href,
			referrer: document.referrer || null,
			timestamp: new Date().toISOString(),
		};

		// Avoid duplicate consecutive entries (e.g. hard reload on same page).
		const last = journey[journey.length - 1];
		if (last && last.path === entry.path) {
			journey[journey.length - 1] = entry; // refresh timestamp
		} else {
			journey.push(entry);
		}

		// Cap history length so localStorage doesn't grow unbounded.
		if (journey.length > MAX_ENTRIES) {
			journey = journey.slice(journey.length - MAX_ENTRIES);
		}

		saveJourney(journey);
	}

	function clearJourney() {
		try {
			localStorage.removeItem(STORAGE_KEY);
		} catch (e) {}
	}

	// Non-AJAX GF submissions: PHP detects the post-submit cookie and sets this
	// flag so the journey is wiped before the first step of the new session.
	if (window.mbCJ && window.mbCJ.clearJourney) {
		clearJourney();
	}

	track();

	// -----------------------------------------------------------------------
	// Gravity Forms integration
	//
	// Injects a hidden input (mb_cj_journey) into every GF form so the full
	// localStorage journey is POSTed with the submission. Works for both
	// standard and AJAX-submitted forms, and for multi-page forms that render
	// via gform_post_render.
	// -----------------------------------------------------------------------

	const GF_POST_FIELD = "mb_cj_journey";

	function injectOrUpdateField(form) {
		if (!form) return;

		let raw;
		try {
			raw = localStorage.getItem(STORAGE_KEY) || "[]";
		} catch (e) {
			raw = "[]";
		}
		let input = form.querySelector('input[name="' + GF_POST_FIELD + '"]');

		if (!input) {
			input = document.createElement("input");
			input.type = "hidden";
			input.name = GF_POST_FIELD;
			form.appendChild(input);
		}

		input.value = raw;
	}

	function setupGfForms() {
		const forms = document.querySelectorAll(".gform_wrapper form");
		for (let i = 0; i < forms.length; i++) {
			injectOrUpdateField(forms[i]);
		}
	}

	// Run once the DOM is ready (safe with defer loading).
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", setupGfForms);
	} else {
		setupGfForms();
	}

	// Re-inject after GF re-renders a form (AJAX page turns, conditional logic).
	// Also clear the journey when AJAX confirmation is shown.
	if (window.jQuery) {
		window
			.jQuery(document)
			.on("gform_post_render", function (event, formId) {
				const form = document.getElementById("gform_" + formId);
				injectOrUpdateField(form);
			})
			.on("gform_confirmation_loaded", clearJourney);
	}

	// Refresh the field value right before submission so late page visits
	// (multi-tab scenarios) are included.
	document.addEventListener(
		"submit",
		function (event) {
			const form = event.target;
			if (
				form &&
				form.querySelector('input[name="' + GF_POST_FIELD + '"]')
			) {
				injectOrUpdateField(form);
			}
		},
		true,
	); // capture phase so it fires before GF's own submit handler
})();
