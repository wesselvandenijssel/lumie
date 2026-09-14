/* This slideToggle solution is based on this repository: https://github.com/ericbutler555/plain-js-slidetoggle */
const s = (
	el: HTMLElement,
	duration: number = 400,
	callback?: () => void,
	isDown: boolean = false
) => {
	el.style.overflow = "hidden";
	if (isDown) el.style.display = "block";

	const elStyles = window.getComputedStyle(el);
	const elHeight = parseFloat(elStyles.getPropertyValue("height"));
	const elPaddingTop = parseFloat(elStyles.getPropertyValue("padding-top"));
	const elPaddingBottom = parseFloat(
		elStyles.getPropertyValue("padding-bottom")
	);
	const elMarginTop = parseFloat(elStyles.getPropertyValue("margin-top"));
	const elMarginBottom = parseFloat(
		elStyles.getPropertyValue("margin-bottom")
	);

	const stepHeight = elHeight / duration;
	const stepPaddingTop = elPaddingTop / duration;
	const stepPaddingBottom = elPaddingBottom / duration;
	const stepMarginTop = elMarginTop / duration;
	const stepMarginBottom = elMarginBottom / duration;

	let start: number | undefined;

	function step(timestamp: number) {
		if (start === undefined) start = timestamp;

		const elapsed = timestamp - start;

		if (isDown) {
			el.style.height = `${stepHeight * elapsed}px`;
			el.style.paddingTop = `${stepPaddingTop * elapsed}px`;
			el.style.paddingBottom = `${stepPaddingBottom * elapsed}px`;
			el.style.marginTop = `${stepMarginTop * elapsed}px`;
			el.style.marginBottom = `${stepMarginBottom * elapsed}px`;
		} else {
			el.style.height = `${elHeight - stepHeight * elapsed}px`;
			el.style.paddingTop = `${
				elPaddingTop - stepPaddingTop * elapsed
			}px`;
			el.style.paddingBottom = `${
				elPaddingBottom - stepPaddingBottom * elapsed
			}px`;
			el.style.marginTop = `${elMarginTop - stepMarginTop * elapsed}px`;
			el.style.marginBottom = `${
				elMarginBottom - stepMarginBottom * elapsed
			}px`;
		}

		if (elapsed >= duration) {
			el.style.height = "";
			el.style.paddingTop = "";
			el.style.paddingBottom = "";
			el.style.marginTop = "";
			el.style.marginBottom = "";
			el.style.overflow = "";
			if (!isDown) el.style.display = "none";
			if (typeof callback === "function") callback();
		} else {
			window.requestAnimationFrame(step);
		}
	}

	window.requestAnimationFrame(step);
};

declare global {
	interface HTMLElement {
		slideToggle(duration: number, callback?: () => void): void;
		slideUp(duration: number, callback?: () => void): void;
		slideDown(duration: number, callback?: () => void): void;
	}
}

HTMLElement.prototype.slideToggle = function slideToggle(
	this: HTMLElement,
	duration: number,
	callback?: () => void
) {
	if (this.clientHeight === 0) {
		s(this as HTMLElement, duration, callback, true);
	} else {
		s(this as HTMLElement, duration, callback);
	}
};

HTMLElement.prototype.slideUp = function slideUp(
	this: HTMLElement,
	duration: number,
	callback?: () => void
) {
	s(this as HTMLElement, duration, callback);
};

HTMLElement.prototype.slideDown = function slideDown(
	this: HTMLElement,
	duration: number,
	callback?: () => void
) {
	s(this as HTMLElement, duration, callback, true);
};

export default HTMLElement;
