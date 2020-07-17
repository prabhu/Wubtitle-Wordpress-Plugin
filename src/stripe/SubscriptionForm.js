import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import CheckoutForm from './Components/CheckoutForm';
import InvoiceForm from './Components/InvoiceForm';
import InvoiceSummary from './Components/InvoiceSummary';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

function App() {
	const { wubtitleEnv, planId, ajaxUrl, ajaxNonce } = WP_GLOBALS;
	const stripeKey =
		wubtitleEnv === 'development'
			? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
			: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
	const stripePromise = loadStripe(stripeKey);

	const [error, setError] = useState(null);
	const [invoiceValues, setInvoiceValues] = useState(null);

	const handleSubmit = (values) => {
		setInvoiceValues(values);
	};

	const createSubscription = (paymentMethodId, values) => {
		const { name, lastname, email } = values;
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=create_subscription&paymentMethodId=${paymentMethodId}&planId=${planId}&email=${email}&_ajax_nonce=${ajaxNonce}&name=${name}&lastname=${lastname}&invoiceObject=${JSON.stringify(
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
		<Elements stripe={stripePromise}>
			{invoiceValues ? (
				<div className="wrapper-form">
					<InvoiceSummary invoiceValues={invoiceValues} />
					<CheckoutForm
						createSubscription={createSubscription}
						error={error}
					/>
				</div>
			) : (
				<InvoiceForm
					handleSubmit={handleSubmit}
					invoicePreValues={null}
				/>
			)}
		</Elements>
	);
}
if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'));
}
