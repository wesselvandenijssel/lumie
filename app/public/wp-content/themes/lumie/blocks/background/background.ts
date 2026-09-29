const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

const EDGE_ZONE = 22;

const INSET = 3;

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

if (!prefersReducedMotion) {
	const sparkles = document.querySelectorAll<HTMLElement>(
		".background__sparkle",
	);

	// Each iteration ends while the sparkle is hidden, so the jump is invisible.
	sparkles.forEach((sparkle) =>
		sparkle.addEventListener("animationiteration", () =>
			placeSparkle(sparkle),
		),
	);
}
