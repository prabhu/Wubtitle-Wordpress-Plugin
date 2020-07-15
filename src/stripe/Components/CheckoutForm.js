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

	const DisplayingErrorMessagesSchema = Yup.lazy((values) => {
		const yupObject = {
			name: Yup.string().required('Required'),
			email: Yup.string().email('Invalid email').required('Required'),
			lastname: Yup.string().required('Required'),
			invoice_name: Yup.string().required('Required'),
			invoice_email: Yup.string()
				.email('Invalid email')
				.required('Required'),
			invoice_lastname: Yup.string().required('Required'),
			telephone: Yup.string().required('Required'),
			address: Yup.string().required('Required'),
			cap: Yup.string().required('Required'),
			city: Yup.string().required('Required'),
			province: Yup.string().required('Required'),
			country: Yup.string().required('Required'),
		};
		if (values.company_name) {
			yupObject.vat_code = Yup.string().required('Required');
		} else {
			yupObject.fiscal_code = Yup.string().required('Required');
		}
		return Yup.object().shape(yupObject);
	});

	const handleSubmit = async (values) => {
		const { name, lastname, email } = values;
		delete values.name;
		delete values.lastname;
		delete values.email;

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
					values
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
		<Formik
			initialValues={{
				name: '',
				email: '',
				lastname: '',
				invoice_name: '',
				invoice_email: '',
				invoice_lastname: '',
				telephone: '',
				company_name: '',
				address: '',
				cap: '',
				city: '',
				province: '',
				country: '',
				vat_code: '',
				fiscal_code: '',
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

					<h1> Billing Details</h1>

					<div className="form-field-container">
						<label htmlFor="invoiceName">Name</label>
						<Field name="invoice_name" placeholder="Name" />
						<p className="error-message">
							{touched.invoice_name && errors.invoice_name}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="invoiceLastname">Lastname</label>
						<Field name="invoice_lastname" placeholder="Lastname" />
						<p className="error-message">
							{touched.invoice_lastname &&
								errors.invoice_lastname}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="invoiceEmail">E-Mail</label>
						<Field name="invoice_email" placeholder="Email" />
						<p className="error-message">
							{touched.invoice_email && errors.invoice_email}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="telephone">Telephone</label>
						<Field name="telephone" placeholder="Telephone" />
						<p className="error-message">
							{touched.telephone && errors.telephone}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="companyName">Company Name</label>
						<Field name="company_name" placeholder="Company Name" />
						<p className="error-message">
							{touched.company_name && errors.company_name}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="address">Address</label>
						<Field name="address" placeholder="Address" />
						<p className="error-message">
							{touched.address && errors.address}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="cap">CAP</label>
						<Field name="cap" placeholder="CAP" />
						<p className="error-message">
							{touched.cap && errors.cap}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="city">City</label>
						<Field name="city" placeholder="City" />
						<p className="error-message">
							{touched.city && errors.city}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="province">Province</label>
						<Field name="province" placeholder="Province" />
						<p className="error-message">
							{touched.province && errors.province}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="country">Country</label>
						<Field name="country" placeholder="Country" />
						<p className="error-message">
							{touched.country && errors.country}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="vatCode">Vat Code</label>
						<Field name="vat_code" placeholder="Vat Code" />
						<p className="error-message">
							{touched.vat_code && errors.vat_code}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="fiscalCode">Fiscal Code</label>
						<Field name="fiscal_code" placeholder="Fiscal Code" />
						<p className="error-message">
							{touched.fiscal_code && errors.fiscal_code}
						</p>
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
	);
}
