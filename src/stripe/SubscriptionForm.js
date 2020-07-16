import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import CheckoutForm from './Components/CheckoutForm';
import InvoiceForm from './Components/InvoiceForm';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

function App() {
	const { wubtitleEnv } = WP_GLOBALS;
	const stripeKey =
		wubtitleEnv === 'development'
			? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
			: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
	const stripePromise = loadStripe(stripeKey);
	const [invoiceValues, setInvoiceValues] = useState(null);
	const handleSubmit = (values) => {
		setInvoiceValues(values);
	};
	return (
		<Elements stripe={stripePromise}>
			{invoiceValues ? (
				<CheckoutForm invoiceValues={invoiceValues} />
			) : (
				<InvoiceForm handleSubmit={handleSubmit} />
			)}
		</Elements>
	);
}
if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'));
}
