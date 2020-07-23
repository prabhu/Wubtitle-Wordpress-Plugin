/* global settings_object */
/* exported redirectToCallback */
let BuyLicenseWindow = null;
let UpdatePlanWindow = null;
let CancelSubscriptionWindow = null;
let CustomFormWindow = null;
let wait = false;
/* eslint-disable */
function redirectToCallback(param) {
	window.location.href += "&" + param;
}
function cancelPayment(){
	CustomFormWindow.close();
	showBuyLicenseWindow();
}
function confirmPlanChange(){
	BuyLicenseWindow.close();
	confirmPlanChangeWindow();
}
//this function is used by the dialog CustomFormWindow 
function customStripeForm(planRank){
	if (BuyLicenseWindow && !BuyLicenseWindow.closed) {
		BuyLicenseWindow.close();
	} else if (CancelSubscriptionWindow && !CancelSubscriptionWindow.closed) {
		CancelSubscriptionWindow.close();
	}
	showCustomFormWindow(planRank);
}
/* eslint-enable */

document.addEventListener('DOMContentLoaded', function () {
	const buyButton = document.querySelector('#buy-license-button');
	if (buyButton) {
		buyButton.addEventListener('click', (e) => {
			e.preventDefault();
			showBuyLicenseWindow();
		});
	}

	const modifyPlan = document.querySelector('#modify-plan');
	if (modifyPlan) {
		modifyPlan.addEventListener('click', (e) => {
			e.preventDefault();
			showBuyLicenseWindow();
		});
	}

	const resetLicense = document.querySelector('#reset-license');
	if (resetLicense) {
		resetLicense.addEventListener('click', (e) => {
			e.preventDefault();
			resetLicenseFunction();
		});
	}

	const cancelButton = document.querySelector('#cancel-license-button');
	if (cancelButton) {
		cancelButton.addEventListener('click', (e) => {
			e.preventDefault();
			showCancelSubscriptionWindow();
		});
	}

	const updateButton = document.querySelector('#update-plan-button');
	if (updateButton) {
		updateButton.addEventListener('click', (e) => {
			e.preventDefault();
			showUpdatePlanWindow();
		});
	}

	const reactivateButton = document.querySelector('#reactivate-plan-button');
	if (reactivateButton) {
		reactivateButton.addEventListener('click', (e) => {
			e.preventDefault();
			reactivateFunction();
		});
	}
});
const showCustomFormWindow = (planRank) => {
	if (!CustomFormWindow || CustomFormWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=1200,
            height=700,
            scrollbars=yes,
        `;
		wp.ajax
			.send('custom_form_template', {
				type: 'POST',
				data: {
					_ajax_nonce: settings_object.ajaxnonce,
					planRank,
					priceinfo: JSON.stringify(settings_object.infoplans),
				},
			})
			.done((response) => {
				CustomFormWindow = window.open(
					'',
					'custom_form',
					windowFeatures
				);
				CustomFormWindow.document.write(response);
			});
	} else {
		CustomFormWindow.focus();
		return CustomFormWindow;
	}
};
const showUpdatePlanWindow = () => {
	if (UpdatePlanWindow === null || UpdatePlanWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=1200,
            height=700,
            scrollbars=yes,
        `;
		wp.ajax
			.send('update_template', {
				type: 'POST',
				data: {
					_ajax_nonce: settings_object.ajaxnonce,
					priceinfo: JSON.stringify(settings_object.infoplans),
				},
			})
			.done((response) => {
				UpdatePlanWindow = window.open(
					'',
					'Update-Plan',
					windowFeatures
				);
				UpdatePlanWindow.document.write(response);
			});
	} else {
		UpdatePlanWindow.focus();
		return UpdatePlanWindow;
	}
};
const showBuyLicenseWindow = () => {
	if (!wait && (BuyLicenseWindow === null || BuyLicenseWindow.closed)) {
		const windowFeatures = `
            left=500,
            top=200,
            width=1200,
            height=700,
            scrollbars=yes,
		`;
		wait = true;
		wp.ajax
			.send('payment_template', {
				type: 'POST',
				data: {
					_ajax_nonce: settings_object.ajaxnonce,
					priceinfo: JSON.stringify(settings_object.infoplans),
				},
			})
			.done((response) => {
				BuyLicenseWindow = window.open(
					'',
					'Buy-license',
					windowFeatures
				);
				BuyLicenseWindow.document.write(response);
				wait = false;
			});
	} else {
		BuyLicenseWindow.focus();
		return BuyLicenseWindow;
	}
};

const confirmPlanChangeWindow = () => {
	if (BuyLicenseWindow === null || BuyLicenseWindow.closed) {
		const windowFeatures = `
            left=500,
            top=200,
            width=1200,
            height=700,
            scrollbars=yes,
        `;
		wp.ajax
			.send('change_plan_template', {
				type: 'POST',
				data: {
					_ajax_nonce: settings_object.ajaxnonce,
					priceinfo: JSON.stringify(settings_object.infoplans),
				},
			})
			.done((response) => {
				BuyLicenseWindow = window.open(
					'',
					'Buy-license',
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
	if (
		!wait &&
		(CancelSubscriptionWindow === null || CancelSubscriptionWindow.closed)
	) {
		const windowFeatures = `
			left=500,
			top=200,
			width=1200,
			height=700,
			scrollbars=yes,
		`;
		wait = true;
		wp.ajax
			.send('cancel_template', {
				type: 'POST',
				data: {
					_ajax_nonce: settings_object.ajaxnonce,
					priceinfo: JSON.stringify(settings_object.infoplans),
				},
			})
			.done((response) => {
				CancelSubscriptionWindow = window.open(
					'',
					'Cancel subscription',
					windowFeatures
				);
				CancelSubscriptionWindow.document.write(response);
				wait = false;
			});
	} else {
		CancelSubscriptionWindow.focus();
	}
};
const resetLicenseFunction = () => {
	fetch(settings_object.ajax_url, {
		method: 'POST',
		credentials: 'include',
		headers: new Headers({
			'Content-Type': 'application/x-www-form-urlencoded',
		}),
		body: `action=reset_license&_ajax_nonce=${settings_object.ajaxnonce}`,
	})
		.then((resp) => resp.json())
		.then((response) => {
			if (response.success) {
				redirectToCallback('notices-code=reset');
			}
		});
};
const reactivateFunction = () => {
	fetch(settings_object.ajax_url, {
		method: 'POST',
		credentials: 'include',
		headers: new Headers({
			'Content-Type': 'application/x-www-form-urlencoded',
		}),
		body: `action=reactivate_plan&_ajax_nonce=${settings_object.ajaxnonce}`,
	})
		.then((resp) => resp.json())
		.then((response) => {
			if (response.success) {
				redirectToCallback('notices-code=reactivate');
			}
		});
};
window.onunload = function () {
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
