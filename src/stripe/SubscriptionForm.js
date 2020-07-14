import React from 'react';
import ReactDOM from 'react-dom';
import CheckoutForm from './Components/CheckoutForm';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

const { wubtitleEnv } = WP_GLOBALS;
const stripeKey =
	wubtitleEnv === 'development'
		? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
		: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
const stripePromise = loadStripe(stripeKey);

function App() {
	return (
		<Elements stripe={stripePromise}>
			<CheckoutForm />
		</Elements>
	);
}
if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'));
}
