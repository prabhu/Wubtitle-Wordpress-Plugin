import { useState } from 'react';
import { useStripe, useElements, CardElement } from '@stripe/react-stripe-js';
import * as Yup from 'yup';
import { Formik, Form, Field } from 'formik';

import CardSection from './CardSection';

export default function CheckoutForm() {
	const { planId, ajaxUrl, ajaxNonce } = WP_GLOBALS;
	const [error, setError] = useState(null);
	const [loading, setLoading] = useState(false);
	const stripe = useStripe();
	const elements = useElements();

	const DisplayingErrorMessagesSchema = Yup.object().shape({
		name: Yup.string().required('Required'),
		email: Yup.string().email('Invalid email').required('Required'),
		lastname: Yup.string().required('Required'),
	});

	const handleSubmit = async (values) => {
		const { name, lastname, email } = values;
		setLoading(true);

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
					setLoading(false);
					if (response.success) {
						setError(null);
						window.opener.redirectToCallback(
							'notices-code=payment'
						);
						window.close();
					}
					setError(response.data);
				});
		};

		const fullName = `${name} ${lastname}`;

		await stripe
			.createPaymentMethod({
				type: 'card',
				card: cardElement,
				billing_details: {
					name: fullName,
				},
			})
			.then((response) => {
				createSubscription(response.paymentMethod.id);
			});
	};

	return (
		<Formik
			initialValues={{
				name: '',
				email: '',
				lastname: '',
			}}
			validationSchema={DisplayingErrorMessagesSchema}
			onSubmit={(values) => {
				handleSubmit(values);
			}}
		>
			{({ errors, touched }) => (
				<Form>
					<div className="form-field-container">
						<label htmlFor="name">Name</label>
						<Field name="name" placeholder="Name" />
						<p className="error-message">
							{touched.name && errors.name}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="lastname">Lastname</label>
						<Field name="lastname" placeholder="Lastname" />
						<p className="error-message">
							{touched.lastname && errors.lastname}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="email">E-Mail</label>
						<Field name="email" placeholder="Email" />
						<p className="error-message">
							{touched.email && errors.email}
						</p>
					</div>
					<div className="form-field-container">
						<CardSection />
					</div>
					<div className="error-message-container" role="alert">
						<p className="error-message">{error}</p>
					</div>
					<button
						disabled={!stripe || loading}
						className={loading ? 'disabled' : ''}
					>
						{loading && (
							<i className="fa fa-refresh fa-spin loading-margin" />
						)}
						Update Payment
					</button>
				</Form>
			)}
		</Formik>
	);
}
