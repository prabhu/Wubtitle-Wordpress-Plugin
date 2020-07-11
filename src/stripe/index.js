import React from 'react';
import ReactDOM from 'react-dom';
import CheckoutForm from './Components/CheckoutForm';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

// Make sure to call `loadStripe` outside of a component’s render to avoid
// recreating the `Stripe` object on every render.
const stripePromise = loadStripe('pk_test_nfUYjFiwdkzYpPOfCZkVZiMK00lOAFcAK7');

function App() {
	return (
		<Elements stripe={stripePromise}>
			<CheckoutForm />
		</Elements>
	);
}

ReactDOM.render(<App />, document.getElementById('root'));
