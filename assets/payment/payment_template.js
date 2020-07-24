(function (document) {
	const { adminAjax, nonce } = WP_GLOBALS;

	const handleChoice = (planRank) => {
		fetch(adminAjax, {
			method: 'POST',
			credentials: 'include',
			headers: new Headers({
				'Content-Type': 'application/x-www-form-urlencoded',
			}),
			body: `action=submit_plan&_ajax_nonce=${nonce}&plan_rank=${planRank}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					if (
						response.data &&
						response.data.message === 'change_plan'
					) {
						window.opener.confirmPlanChange(
							response.data.amount_preview,
							planRank
						);
						return;
					}
					window.opener.customStripeForm(planRank);
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
		const buttons = document.querySelectorAll('.button-choose-plan');
		buttons.forEach((button) => {
			button.addEventListener('click', () => {
				const planRank = button.getAttribute('plan');
				if (planRank === '0') {
					handleUnsubscription();
				} else {
					handleChoice(planRank);
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
})(document).init();
