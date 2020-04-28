let BuyLicenseWindow = null;
let UpdatePlanWindow = null;
let CancelSubscriptionWindow = null;
document.addEventListener("DOMContentLoaded", function() {
	const buyButton = document.querySelector("#buy-license-button");
	if (buyButton) {
		buyButton.addEventListener("click", e => {
			e.preventDefault();
			showBuyLicenseWindow();
		});
	}

	const cancelButton = document.querySelector("#cancel-license-button");
	if (cancelButton) {
		cancelButton.addEventListener("click", () => {
			showCancelSubscriptionWindow();
		});
	}

	const updateButton = document.querySelector("#update-plan-button");
	if (updateButton) {
		updateButton.addEventListener("click", () => {
			showUpdatePlanWindow();
		});
	}
});
const showUpdatePlanWindow = () => {
	if (UpdatePlanWindow === null || UpdatePlanWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=500,
            height=500,
            scrollbars=yes,
        `;
		wp.ajax
			.send("update_template", {
				type: "GET"
			})
			.done(response => {
				UpdatePlanWindow = window.open(
					"",
					"Update Plan",
					windowFeatures
				);
				UpdatePlanWindow.document.write(response);
			});
	} else {
		UpdatePlanWindow.focus();
	}
};
const showBuyLicenseWindow = () => {
	if (BuyLicenseWindow === null || BuyLicenseWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=1200,
            height=700,
            scrollbars=yes,
        `;
		wp.ajax
			.send("payment_template", {
				type: "GET"
			})
			.done(response => {
				BuyLicenseWindow = window.open(
					"",
					"Buy-license",
					windowFeatures
				);
				BuyLicenseWindow.document.write(response);
			});
	} else {
		BuyLicenseWindow.focus();
		return BuyLicenseWindow;
	}
};

const showCancelSubscriptionWindow = () => {
	if (CancelSubscriptionWindow === null || CancelSubscriptionWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=1200,
            height=700,
            scrollbars=yes,
        `;
		wp.ajax
			.send("cancel_template", {
				type: "GET"
			})
			.done(response => {
				CancelSubscriptionWindow = window.open(
					"",
					"Cancel subscription",
					windowFeatures
				);
				CancelSubscriptionWindow.document.write(response);
			});
	} else {
		CancelSubscriptionWindow.focus();
	}
};
window.onunload = function() {
	if (BuyLicenseWindow !== null) {
		BuyLicenseWindow.close();
	}
	if (UpdatePlanWindow !== null) {
		UpdatePlanWindow.close();
	}
	if (CancelSubscriptionWindow !== null) {
		CancelSubscriptionWindow.close();
	}
};
