let didScroll = false;
let lastScrollTop = 0;
const delta = 5;
const header = document.querySelector<HTMLElement>("header");
const navbarHeight = header?.offsetHeight ?? 0;

const hasScrolled = () => {
	if (!header) return;

	const st = window.scrollY;
	if (st) if (Math.abs(lastScrollTop - st) <= delta) return;
	if (st > lastScrollTop && st > navbarHeight) {
		header.classList.add("header--up");
	} else if (
		st + window.innerHeight <
		document.documentElement.scrollHeight
	) {
		header.classList.remove("header--up");
	}
	lastScrollTop = st;
};

window.addEventListener("scroll", () => {
	didScroll = true;
});

setInterval(() => {
	if (didScroll) {
		hasScrolled();
		didScroll = false;
	}
}, 250);
