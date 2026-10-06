const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

const EDGE_ZONE = 22;

const INSET = 3;

const FLAVOURS = [
	"zest",
	"peach",
	"blush",
	"electric-green",
	"zest",
	"peach",
	"blush",
	"electric-green",
	"navy",
];

/**
 * Returns a random number between min and max.
 *
 * @param min - Lower bound
 * @param max - Upper bound
 * @returns A random number in the given range
 */
function random(min: number, max: number): number {
	return min + Math.random() * (max - min);
}

/**
 * Moves a sparkle to a new random spot, favouring the left and right edges
 * so it mostly stays clear of the centred content.
 *
 * @param sparkle - The sparkle element to reposition
 * @returns void
 */
function placeSparkle(sparkle: HTMLElement): void {
	const x =
		Math.random() < 0.5
			? random(INSET, EDGE_ZONE)
			: random(100 - EDGE_ZONE, 100 - INSET);

	sparkle.style.setProperty("--sparkle-x", `${x.toFixed(1)}%`);
	sparkle.style.setProperty(
		"--sparkle-y",
		`${random(INSET, 100 - INSET).toFixed(1)}%`,
	);
}

/**
 * Swaps the sparkle's flavour modifier for the given flavour.
 *
 * @param sparkle - The sparkle element to recolour
 * @param flavour - The flavour to apply
 * @returns void
 */
function setFlavour(sparkle: HTMLElement, flavour: string): void {
	FLAVOURS.forEach((name) =>
		sparkle.classList.remove(`background__sparkle--${name}`),
	);
	sparkle.classList.add(`background__sparkle--${flavour}`);
}

if (!prefersReducedMotion) {
	const sparkles = document.querySelectorAll<HTMLElement>(
		".background__sparkle",
	);

	// Each iteration ends while the sparkle is hidden, so the jump is invisible.
	sparkles.forEach((sparkle) => {
		// FLAVOURS repeats names, so track the position instead of reading the class.
		// Start from the sibling index, matching the flavour view.php rendered.
		let flavourIndex = Array.from(
			sparkle.parentElement?.children ?? [],
		).indexOf(sparkle);

		sparkle.addEventListener("animationiteration", () => {
			flavourIndex = (flavourIndex + 1) % FLAVOURS.length;
			placeSparkle(sparkle);
			setFlavour(sparkle, FLAVOURS[flavourIndex]);
		});
	});
}
