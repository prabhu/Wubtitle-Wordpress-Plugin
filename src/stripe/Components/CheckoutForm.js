import { useState } from 'react';
import { useStripe, useElements, CardElement } from '@stripe/react-stripe-js';
import * as Yup from 'yup';
import { Formik, Form, Field } from 'formik';
import CardSection from './CardSection';

export default function CheckoutForm(props) {
	const { planId, ajaxUrl, ajaxNonce } = WP_GLOBALS;
	const [error, setError] = useState(null);
	const [loading, setLoading] = useState(false);
	const stripe = useStripe();
	const elements = useElements();
	const invoiceValues = props.invoiceValues;
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
				body: `action=create_subscription&paymentMethodId=${paymentMethodId}&planId=${planId}&email=${email}&_ajax_nonce=${ajaxNonce}&name=${name}&lastname=${lastname}&invoiceObject=${JSON.stringify(
					invoiceValues
				)}`,
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
		<div className="wrapper-form">
			<h2>SUMMARY</h2>
			<h3>Name</h3>
			<p>{invoiceValues.invoice_name}</p>
			<h3>Lastname</h3>
			<p>{invoiceValues.invoice_lastname}</p>
			<h3>Email</h3>
			<p>{invoiceValues.invoice_email}</p>
			<h3>Address</h3>
			<p>{invoiceValues.address}</p>
			<h3>Cap</h3>
			<p>{invoiceValues.cap}</p>
			<h3>City</h3>
			<p>{invoiceValues.city}</p>
			<h3>Company Name</h3>
			<p>{invoiceValues.company_name}</p>
			<h3>Country</h3>
			<p>{invoiceValues.country}</p>
			<h3>Fiscal Code</h3>
			<p>{invoiceValues.fiscal_code}</p>
			<h3>Province</h3>
			<p>{invoiceValues.province}</p>
			<h3>Telephone</h3>
			<p>{invoiceValues.telephone}</p>
			<h3>Vat Code</h3>
			<p>{invoiceValues.vat_code}</p>
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
							Confirm order
						</button>
					</Form>
				)}
			</Formik>
		</div>
	);
}
