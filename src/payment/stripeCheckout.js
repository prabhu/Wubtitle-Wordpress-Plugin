document.addEventListener("DOMContentLoaded", function() {
	const BuyLicenseWindow = null;
	document
		.querySelector("#buy-license-button")
		.addEventListener("click", () => {
			showBuyLicenseWindow(BuyLicenseWindow);
		});
});

const showBuyLicenseWindow = BuyLicenseWindow => {
	if (BuyLicenseWindow === null || BuyLicenseWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=500,
            height=500,
            scrollbars=yes,
        `;
		BuyLicenseWindow = window.open(
			"https://google.it",
			"Custom Google",
			windowFeatures
		);
	} else {
		BuyLicenseWindow.focus();
	}
};
