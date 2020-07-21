import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import CheckoutForm from './Components/CheckoutForm';
import InvoiceForm from './Components/InvoiceForm';
import InvoiceSummary from './Components/InvoiceSummary';
import InfoPriceColumn from './Components/InfoPriceColumn';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

function App() {
	const {
		wubtitleEnv,
		planId,
		ajaxUrl,
		ajaxNonce,
		pricePlan,
		namePlan,
		taxPercentage,
		taxAmount,
	} = WP_GLOBALS;
	const stripeKey =
		wubtitleEnv === 'development'
			? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
			: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
	const stripePromise = loadStripe(stripeKey);

	const [error, setError] = useState(null);
	const [invoiceValues, setInvoiceValues] = useState(null);
	const [isBack, setIsBack] = useState(false);

	const handleSubmit = (values) => {
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=check_vat_code&_ajax_nonce=${ajaxNonce}&vat_code=${values.vat_code}&country=${values.country}&price_plan=${pricePlan}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					setError(null);
					values.tax = response.data;
					setInvoiceValues(values);
					if (isBack) {
						setIsBack(false);
					}
				} else {
					setError(response.data);
				}
			});
	};

	const backFunction = () => {
		setIsBack(true);
		setError(null);
	};
	const cancelFunction = () => {
		window.opener.cancelPayment();
	};
	const createSubscription = (paymentMethodId, values) => {
		const { email } = values;
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=create_subscription&paymentMethodId=${paymentMethodId}&planId=${planId}&email=${email}&_ajax_nonce=${ajaxNonce}&invoiceObject=${JSON.stringify(
				invoiceValues
			)}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					setError(null);
					window.opener.redirectToCallback('notices-code=payment');
					window.close();
				}
				setError(response.data);
			});
	};

	return (
		<div className="main columns">
			<InfoPriceColumn
				price={pricePlan}
				name={namePlan}
				taxPercentage={taxPercentage}
				taxAmount={taxAmount}
			/>

			<Elements stripe={stripePromise}>
				{invoiceValues && !isBack ? (
					<div className="wrapper-form">
						<InvoiceSummary
							invoiceValues={invoiceValues}
							price={pricePlan}
						/>
						<CheckoutForm
							createSubscription={createSubscription}
							error={error}
							backFunction={backFunction}
						/>
					</div>
				) : (
					<InvoiceForm
						handleSubmit={handleSubmit}
						invoicePreValues={invoiceValues}
						error={error}
						cancelFunction={cancelFunction}
					/>
				)}
			</Elements>
		</div>
	);
}
if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'));
}
