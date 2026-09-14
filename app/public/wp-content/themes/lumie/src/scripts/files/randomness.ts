/**
 * IOS hover fix
 * https://stackoverflow.com/questions/2851663/how-do-i-simulate-a-hover-with-a-touch-in-touch-enabled-browsers
 * Source: http://fofwebdesign.co.uk/template/_testing/ios-sticky-hover-fix.htm
 */

(function iosHoverFix(l) {
	l.addEventListener("touchend", () => {});
})(document);

document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
	anchor.addEventListener("click", function handleClick(e) {
		e.preventDefault();

		document.querySelector(this.getAttribute("href")).scrollIntoView({
			behavior: "smooth",
		});
	});
});
