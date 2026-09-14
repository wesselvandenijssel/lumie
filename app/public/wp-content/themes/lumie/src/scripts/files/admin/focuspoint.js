/* eslint-env browser */
document.addEventListener("DOMContentLoaded", () => {
	function round(value, precision) {
		const multiplier = 10 ** (precision || 0);
		return Math.round(value * multiplier) / multiplier;
	}

	function position(x, y) {
		const pointer = document.querySelector(
			".media-modal .focuspoint .focuspoint__pointer"
		);

		// Set pointer position
		if (pointer) {
			pointer.style.left = `${x}%`;
			pointer.style.top = `${y}%`;
		}
	}

	function focuspoint() {
		const detailsImage = document.querySelector(
			".media-modal .details-image"
		);
		if (detailsImage) {
			// Create a wrapper div for the focus point
			const focuspointWrapper = document.createElement("div");
			focuspointWrapper.classList.add("focuspoint");

			// Insert the wrapper before the details image
			detailsImage.parentNode.insertBefore(
				focuspointWrapper,
				detailsImage
			);

			// Move the details image into the wrapper
			focuspointWrapper.appendChild(detailsImage);

			// Create a pointer div for the focus point
			const focuspointPointer = document.createElement("div");
			focuspointPointer.classList.add("focuspoint__pointer");
			document
				.querySelector(".media-modal .focuspoint")
				.appendChild(focuspointPointer);

			// Find the attachment actions element
			const attachmentActions = document.querySelector(
				".media-modal .attachment-actions"
			);
			if (attachmentActions) {
				const saveButton = document.createElement("button");
				saveButton.classList.add(
					"focuspoint-save",
					"button",
					"button-primary"
				);
				saveButton.textContent = "Save Image";
				attachmentActions.appendChild(saveButton);
			}

			// Find the input elements for X and Y positions
			const inputX = document.querySelector("input[id*=posX]");
			const inputY = document.querySelector("input[id*=posY]");

			if (inputX && inputY) {
				// Get the X and Y positions
				const posX = parseFloat(inputX.value);
				const posY = parseFloat(inputY.value);

				// Position the focus point
				position(posX, posY);
			}
		}
	}

	if (wp && wp.media && wp.media.view && wp.media.view.Modal) {
		// Store the original open method of Modal
		const originalOpen = wp.media.view.Modal.prototype.open;
		wp.media.view.Modal.prototype.open = function (...args) {
			originalOpen.apply(this, args);
			setTimeout(focuspoint, 100);
		};
	}

	document.addEventListener("click", (e) => {
		if (e.target.closest(".media-modal .details-image")) {
			const inputX = document.querySelector("input[id*=posX]");
			const inputY = document.querySelector("input[id*=posY]");

			if (inputX && inputY) {
				// Calculate the relative position of the click within the details image
				const parentOffset = e.target
					.closest(".media-modal .details-image")
					.getBoundingClientRect();

				const relX = e.pageX - parentOffset.left;
				const x = round(
					(relX /
						e.target.closest(".media-modal .details-image")
							.clientWidth) *
						100,
					1
				);

				const relY = e.pageY - parentOffset.top;
				const y = round(
					(relY /
						e.target.closest(".media-modal .details-image")
							.clientHeight) *
						100,
					1
				);

				// Update the input values with the calculated positions
				inputX.value = x;
				inputY.value = y;

				position(x, y);
			}
		}

		document.addEventListener("click", (event) => {
			if (event.target.matches(".media-modal .focuspoint-save")) {
				const button = document.querySelector(
					".media-modal .focuspoint-save"
				);

				// Dispatch change events for input elements with IDs containing "posX" or "posY"
				["posX", "posY"].forEach((idPart) => {
					document
						.querySelectorAll(`input[id*=${idPart}]`)
						.forEach((input) => {
							const changeEvent = new Event("change", {
								bubbles: true,
								cancelable: true,
							});
							input.dispatchEvent(changeEvent);
						});
				});

				button.textContent = "Saving...";

				setTimeout(() => {
					button.textContent = "Save Image";
				}, 1000);
			}
		});
	});
});
