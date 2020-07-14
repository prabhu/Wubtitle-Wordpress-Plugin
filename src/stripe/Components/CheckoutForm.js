import { useState } from 'react';
import { useStripe, useElements, CardElement } from '@stripe/react-stripe-js';

import CardSection from './CardSection';

export default function CheckoutForm() {
	const { planId, ajaxUrl, ajaxNonce } = WP_GLOBALS;
	const [error, setError] = useState(null);
	const [name, setName] = useState('');
	const [lastname, setLastname] = useState('');
	const [email, setEmail] = useState('');
	const [isDisabled, setIsDisabled] = useState(false);
	const stripe = useStripe();
	const elements = useElements();

	const handleSubmit = async (event) => {
		event.preventDefault();
		setIsDisabled(true);

		if (!stripe || !elements) {
			return;
		}

		const cardElement = elements.getElement(CardElement);

		const createSubscription = (paymentMethodId) => {
			fetch(ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: `action=create_subscription&paymentMethodId=${paymentMethodId}&planId=${planId}&email=${email}&_ajax_nonce=${ajaxNonce}&name=${name}&lastname=${lastname}`,
			})
				.then((resp) => resp.json())
				.then((response) => {
					if (!response.success) {
						setError(response.data);
					} else {
						setError(null);
					}
					setIsDisabled(false);
				});
		};

		await stripe
			.createPaymentMethod({
				type: 'card',
				card: cardElement,
				billing_details: {
					name,
				},
			})
			.then((response) => {
				createSubscription(response.paymentMethod.id);
			});
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
				<label htmlFor="name">Lastname</label>
				<input
					id="lastname"
					name="lastname"
					placeholder="Lastname"
					required
					value={lastname}
					onChange={(event) => {
						setLastname(event.target.value);
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
			<button
				disabled={!stripe || isDisabled}
				className={isDisabled ? 'disabled' : ''}
			>
				Confirm order
			</button>
		</form>
	);
}
