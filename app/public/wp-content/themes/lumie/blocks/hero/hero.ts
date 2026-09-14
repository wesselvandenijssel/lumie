const observer = new MutationObserver((mutations, observerInstance) => {
	const wistiaPlayer = document.querySelector("wistia-player");

	if (wistiaPlayer && wistiaPlayer.shadowRoot) {
		const style = document.createElement("style");
		style.innerText = `
			video {
				object-fit: cover !important;
			}
		`;

		wistiaPlayer.shadowRoot.append(style);

		observerInstance.disconnect();
	}
});

observer.observe(document.body, {
	childList: true,
	subtree: true,
});
