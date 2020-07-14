import React from 'react';
import ReactDOM from 'react-dom';
import CheckoutUpdateForm from './Components/CheckoutUpdateForm';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

const { wubtitleEnv } = WP_GLOBALS;
const stripeKey =
	wubtitleEnv === 'development'
		? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
		: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
const stripePromise = loadStripe(stripeKey);

function Prova() {
	return (
		<Elements stripe={stripePromise}>
			<CheckoutUpdateForm />
		</Elements>
	);
}

if (document.getElementById('update-form')) {
	ReactDOM.render(<Prova />, document.getElementById('update-form'));
}
