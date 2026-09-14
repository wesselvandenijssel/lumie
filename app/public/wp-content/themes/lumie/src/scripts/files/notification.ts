// Converts strings to camelcase
const camelize = (str: string) =>
	str
		.replace(/(?:^\w|[A-Z]|\b\w)/g, (word: string, index: number) =>
			index === 0 ? word.toLowerCase() : word.toUpperCase()
		)
		.replace(/\s+/g, "");

const notifications = document.querySelectorAll(".notification");

const closeNotifications = document.querySelectorAll<HTMLElement>(
	".notification__close"
);

notifications.forEach((notification) => {
	const notificationText = notification
		.querySelector(".notification__text")
		.textContent.trim();
	const notificationSessionVariable = camelize(
		`notification ${notificationText}`
	);
	const notificationStatus = sessionStorage.getItem(
		notificationSessionVariable
	);

	if (notificationStatus !== "hidden") {
		notification.classList.add("notification--active");
	}
});

closeNotifications.forEach((closeNotification) =>
	closeNotification.addEventListener("click", () => {
		document.querySelectorAll(".notification").forEach((notification) => {
			const notificationText = notification
				.querySelector(".notification__text")
				.textContent.trim();
			const notificationSessionVariable = camelize(
				`notification ${notificationText}`
			);

			notification.classList.remove("notification--active");

			sessionStorage.setItem(notificationSessionVariable, "hidden");
		});
	})
);
