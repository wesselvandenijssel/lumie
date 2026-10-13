const STORAGE_KEY = "lumie-autoplay-paused";

/**
 * Reads whether the visitor paused moving content earlier, so the choice carries over to the next page
 *
 * @returns boolean
 */
export const isAutoplayPaused = (): boolean => {
	try {
		return localStorage.getItem(STORAGE_KEY) === "1";
	} catch {
		return false;
	}
};

/**
 * Wires a pause/play button for moving content (WCAG 2.2.2) and keeps its label, icon and the stored choice in sync
 *
 * @param button - The toggle button, rendered hidden in the block template
 * @param onPause - Stops the moving content
 * @param onPlay - Starts the moving content again
 * @returns void
 */
export const initAutoplayToggle = (
	button: HTMLButtonElement,
	onPause: () => void,
	onPlay: () => void,
): void => {
	const render = (paused: boolean): void => {
		button.textContent = paused
			? (button.dataset.labelPlay ?? "")
			: (button.dataset.labelPause ?? "");
		button.classList.toggle("autoplay-toggle--paused", paused);
	};

	let paused = isAutoplayPaused();

	render(paused);
	button.hidden = false;

	if (paused) {
		onPause();
	}

	button.addEventListener("click", () => {
		paused = !paused;

		try {
			localStorage.setItem(STORAGE_KEY, paused ? "1" : "0");
		} catch {
			// Storage can be blocked; the toggle still works for this page
		}

		render(paused);

		if (paused) {
			onPause();
		} else {
			onPlay();
		}
	});
};
