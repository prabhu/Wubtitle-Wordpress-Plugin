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
		wp.ajax
			.send("payment_template", {
				type: "GET"
			})
			.done(response => {
				BuyLicenseWindow = window.open(
					"",
					"Buy license",
					windowFeatures
				);
				BuyLicenseWindow.document.body.innerHTML = response;
			});
	} else {
		BuyLicenseWindow.focus();
	}
};
