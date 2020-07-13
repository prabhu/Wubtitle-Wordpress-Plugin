const paymentModule = (function (Stripe, document) {
	let stripe = null;

	const { adminAjax, nonce, wubtitleEnv } = WP_GLOBALS;

	const handleChoice = (plan) => {
		fetch(adminAjax, {
			method: 'POST',
			credentials: 'include',
			headers: new Headers({
				'Content-Type': 'application/x-www-form-urlencoded',
			}),
			body: `action=submit_plan&_ajax_nonce=${nonce}&pricing_plan=${plan}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					if (response.data === 'change_plan') {
						window.opener.confirmPlanChange();
					}
					window.opener.customStripeForm(plan);
				}
			});
	};

	const handleUnsubscription = () => {
		fetch(adminAjax, {
			method: 'POST',
			credentials: 'include',
			headers: new Headers({
				'Content-Type': 'application/x-www-form-urlencoded',
			}),
			body: `action=cancel_subscription&_ajax_nonce=${nonce}`,
		}).then(() => {
			window.opener.redirectToCallback('notices-code=delete');
		});
	};

	const init = () => {
		const stripeKey =
			wubtitleEnv === 'development'
				? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
				: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
		stripe = Stripe(stripeKey);
		const buttons = document.querySelectorAll('.button-choose-plan');
		buttons.forEach((button) => {
			button.addEventListener('click', () => {
				const plan = button.getAttribute('plan');
				if (plan === 'plan_0') {
					handleUnsubscription();
				} else {
					handleChoice(plan);
				}
			});
		});

		const unsubscribeButton = document.querySelector('#unsubscribeButton');
		const closeButton = document.querySelector('#close');
		if (unsubscribeButton) {
			unsubscribeButton.addEventListener('click', () => {
				handleUnsubscription();
			});
		}
		if (closeButton) {
			closeButton.addEventListener('click', () => {
				window.close();
			});
		}
	};

	return {
		init,
	};
})(Stripe, document);

paymentModule.init();
