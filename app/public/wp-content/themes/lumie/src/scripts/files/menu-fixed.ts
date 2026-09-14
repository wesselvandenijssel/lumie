let didScroll = false;
let lastScrollTop = 0;
const delta = 5;
const navbarHeight = document.querySelector("header").offsetHeight;

const hasScrolled = () => {
	const st = window.scrollY;
	if (st) if (Math.abs(lastScrollTop - st) <= delta) return;
	if (st > lastScrollTop && st > navbarHeight) {
		document.querySelector("header").classList.add("header--up");
	} else if (
		st + window.screen.height <
		document.documentElement.scrollHeight
	) {
		document.querySelector("header").classList.remove("header--up");
	}
	if (st > 10) {
		document.querySelector("header").classList.add("header--scrolled");
	} else {
		document.querySelector("header").classList.remove("header--scrolled");
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
