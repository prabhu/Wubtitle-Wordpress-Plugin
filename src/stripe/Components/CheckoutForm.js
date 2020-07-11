import { useState } from 'react';
import { useStripe, useElements, CardElement } from '@stripe/react-stripe-js';

import CardSection from './CardSection';

export default function CheckoutForm() {
	const { clientId } = WP_GLOBALS;
	const [error, setError] = useState(null);
	const [name, setName] = useState('');
	const [email, setEmail] = useState('');
	const stripe = useStripe();
	const elements = useElements();

	const handleSubmit = async (event) => {
		// We don't want to let default form submission happen here,
		// which would refresh the page.
		event.preventDefault();

		if (!stripe || !elements) {
			// Stripe.js has not yet loaded.
			// Make sure to disable form submission until Stripe.js has loaded.
			return;
		}

		const result = await stripe.confirmCardPayment(clientId, {
			payment_method: {
				card: elements.getElement(CardElement),
				billing_details: {
					name,
					email,
				},
			},
		});

		if (result.error) {
			// Show error to your customer (e.g., insufficient funds)
			setError(result.error.message);
		} else {
			setError(null);
			//TODO: send token
		}
	};

	return (
		<form onSubmit={handleSubmit}>
			<div className="form-row">
				<label htmlFor="name">Name</label>
				<input
					id="name"
					name="name"
					placeholder="Name"
					required
					value={name}
					onChange={(event) => {
						setName(event.target.value);
					}}
				/>
			</div>
			<div className="form-row">
				<label htmlFor="email">Email</label>
				<input
					id="email"
					name="email"
					placeholder="Email"
					required
					value={email}
					onChange={(event) => {
						setEmail(event.target.value);
					}}
				/>
			</div>
			<div className="error-message" role="alert">
				{error}
			</div>
			<CardSection />
			<button disabled={!stripe}>Confirm order</button>
		</form>
	);
}
