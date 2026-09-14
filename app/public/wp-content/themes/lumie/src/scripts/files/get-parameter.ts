const getUrlParameter = (sParam: string) => {
	const sPageURL = window.location.search.substring(1);
	const sURLVariables = sPageURL.split("&");
	let sParameterName;
	let i;

	for (i = 0; i < sURLVariables.length; i += 1) {
		sParameterName = sURLVariables[i].split("=");

		if (sParameterName[0] === sParam) {
			return sParameterName[1] === undefined
				? true
				: decodeURIComponent(sParameterName[1]);
		}
	}

	return null;
};
export default getUrlParameter;
