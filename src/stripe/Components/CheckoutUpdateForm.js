import { useState } from 'react';
import { useStripe, useElements, CardElement } from '@stripe/react-stripe-js';
import * as Yup from 'yup';
import { Formik, Form, Field } from 'formik';

import CardSection from './CardSection';

export default function CheckoutForm() {
	const { ajaxUrl, ajaxNonce } = WP_GLOBALS;
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
				body: `action=update_payment_method&paymentMethodId=${paymentMethodId}&email=${email}&_ajax_nonce=${ajaxNonce}&name=${name}&lastname=${lastname}`,
			})
				.then((resp) => resp.json())
				.then((response) => {
					setLoading(false);
					if (response.success) {
						setError(null);
						window.opener.redirectToCallback('notices-code=update');
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
					<label htmlFor="name">Name</label>
					<Field name="name" placeholder="Name" />
					{touched.name && errors.name && (
						<div className="error-message">{errors.name}</div>
					)}
					<label htmlFor="lastname">Lastname</label>
					<Field name="lastname" placeholder="Lastname" />
					{touched.lastname && errors.lastname && (
						<div className="error-message">{errors.lastname}</div>
					)}
					<label htmlFor="email">E-Mail</label>
					<Field name="email" placeholder="Email" />
					{touched.email && errors.email && (
						<div className="error-message">{errors.email}</div>
					)}
					<CardSection />
					<div className="error-message" role="alert">
						{error}
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
