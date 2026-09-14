import { scroll } from "motion";

const prefersReducedMotion = window.matchMedia(
	"(prefers-reduced-motion: reduce)",
).matches;

const DIM_OPACITY = 0.25;

const OVERLAP = 4;

const AUTHOR_RANGE = 0.2;

const AUTHOR_SHIFT = 12;

/**
 * Wraps every word of an element in its own span so each can be faded separately.
 *
 * @param element - The element whose text should be split into words
 * @returns The created word spans, in reading order
 */
function wrapWords(element: HTMLElement): HTMLElement[] {
	const words: HTMLElement[] = [];

	const walk = (node: ChildNode): void => {
		if (node.nodeType === 3) {
			const parts = (node.textContent || "").split(/(\s+)/);
			const fragment = document.createDocumentFragment();

			parts.forEach((part) => {
				if (!part) {
					return;
				}

				if (!part.trim()) {
					fragment.appendChild(document.createTextNode(part));
					return;
				}

				const word = document.createElement("span");
				word.className = "quote__word";
				word.textContent = part;
				word.style.opacity = `${DIM_OPACITY}`;

				fragment.appendChild(word);
				words.push(word);
			});

			node.replaceWith(fragment);
			return;
		}

		Array.from(node.childNodes).forEach(walk);
	};

	Array.from(element.childNodes).forEach(walk);

	return words;
}

function clamp(value: number): number {
	return Math.min(Math.max(value, 0), 1);
}

if (!prefersReducedMotion) {
	const blockquotes =
		document.querySelectorAll<HTMLElement>(".quote__blockquote");

	blockquotes.forEach((blockquote) => {
		const words = wrapWords(blockquote);

		if (!words.length) {
			return;
		}

		const author = blockquote
			.closest(".quote__wrapper")
			?.querySelector<HTMLElement>(".quote__author");

		if (author) {
			author.style.opacity = "0";
			author.style.transform = `translateY(${AUTHOR_SHIFT}px)`;
		}

		const step = 1 / (words.length + OVERLAP);

		const lastOpacity: number[] = words.map(() => DIM_OPACITY);

		scroll(
			(progress: number) => {
				words.forEach((word, index) => {
					const local = clamp(
						(progress - index * step) / (step * OVERLAP),
					);
					const opacity =
						Math.round(
							(DIM_OPACITY + (1 - DIM_OPACITY) * local) * 100,
						) / 100;

					if (opacity === lastOpacity[index]) {
						return;
					}

					lastOpacity[index] = opacity;
					word.style.opacity = `${opacity}`;
				});

				if (author) {
					const local = clamp(
						(progress - (1 - AUTHOR_RANGE)) / AUTHOR_RANGE,
					);

					author.style.opacity = `${local}`;
					author.style.transform = `translateY(${(1 - local) * AUTHOR_SHIFT}px)`;
				}
			},
			{
				target: blockquote,
				offset: ["start 85%", "end 45%"],
			},
		);
	});
}
