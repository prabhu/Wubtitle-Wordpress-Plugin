import React from 'react';
import ReactDOM from 'react-dom';
import CheckoutUpdateForm from './Components/CheckoutUpdateForm';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

// Make sure to call `loadStripe` outside of a component’s render to avoid
// recreating the `Stripe` object on every render.
const stripePromise = loadStripe('pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7');

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
