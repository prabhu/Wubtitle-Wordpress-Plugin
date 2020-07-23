import { useState } from 'react';
import * as Yup from 'yup';
import { __ } from '@wordpress/i18n';
import { Formik, Form, Field } from 'formik';
import CardSection from './CardSection';
import {
	useStripe,
	useElements,
	CardNumberElement,
} from '@stripe/react-stripe-js';

export default function CheckoutForm(props) {
	const {
		createSubscription,
		backFunction,
		error,
		paymentPreValues,
		setError,
	} = props;
	const stripe = useStripe();
	const elements = useElements();
	const [loading, setLoading] = useState(false);
	const [changeOn, setChangeOn] = useState(false);
	const requiredMessage = __('Required', 'wubtitle');
	const DisplayingErrorMessagesSchema = Yup.lazy(() => {
		let yupObject = {};
		if (!paymentPreValues || changeOn) {
			yupObject = {
				name: Yup.string().required(requiredMessage),
				email: Yup.string()
					.email(__('Invalid email', 'wubtitle'))
					.required(requiredMessage),
			};
		}
		return Yup.object().shape(yupObject);
	});

	const handleSubmit = async (values) => {
		const { name, email } = values;
		if (!stripe || !elements) {
			return;
		}
		let cardNumber = elements.getElement(CardNumberElement);
		if (paymentPreValues && !changeOn) {
			values.name = paymentPreValues.name;
			values.email = paymentPreValues.email;
			cardNumber = null;
			createSubscription(paymentPreValues.paymentMethod, values);
			return;
		}
		if (!cardNumber) {
			createSubscription(paymentPreValues.paymentMethod, values);
			return;
		}
		setLoading(true);

		const response = await stripe.createPaymentMethod({
			type: 'card',
			card: cardNumber,
			billing_details: {
				name,
				email,
			},
		});
		if (response.error) {
			setError(response.error.message);
		}
		createSubscription(response.paymentMethod.id, values);
		setLoading(false);
	};

	return (
		<div className="checkout-form">
			<div className="title-section">
				<h2>{__('Payment Details', 'wubtitle')}</h2>
				{paymentPreValues ? (
					<div className="switch-container">
						<p>
							{changeOn
								? __('editing enabled', 'wubtitle')
								: __('editing disabled', 'wubtitle')}
						</p>
						<label htmlFor="change-data" className="switch">
							<input
								type="checkbox"
								onClick={() => {
									setChangeOn(!changeOn);
								}}
							/>
							<span className="slider round" />
						</label>
					</div>
				) : null}
			</div>
			{paymentPreValues ? (
				<div className="summary columns">
					<div className="column">
						<p>
							<strong>{__('Email', 'wubtitle')}: </strong>
							{paymentPreValues.email}
						</p>
						<p>
							<strong>{__('Card Details', 'wubtitle')}: </strong>
							{paymentPreValues.cardNumber}
						</p>
					</div>
					<div className="column">
						<p>
							<strong>{__('Card Holder', 'wubtitle')}: </strong>
							{paymentPreValues.name}
						</p>
						<p>
							<strong>{__('Expires', 'wubtitle')}: </strong>
							{paymentPreValues.expiration}
						</p>
					</div>
				</div>
			) : null}
			<Formik
				initialValues={{
					name: '',
					email: '',
				}}
				validationSchema={DisplayingErrorMessagesSchema}
				onSubmit={(values) => {
					handleSubmit(values);
				}}
			>
				{({ errors, touched }) => (
					<Form>
						{!paymentPreValues || changeOn ? (
							<div className="fields-container">
								<div className="form-field-container">
									<label htmlFor="email">E-Mail</label>
									<Field name="email" placeholder="Email" />
									<p className="error-message">
										{touched.email && errors.email}
									</p>
								</div>
								<div className="form-field-container">
									<label htmlFor="name">
										{__('Card Holder', 'wubtitle')}
									</label>
									<Field
										name="name"
										placeholder="Card Holder"
									/>
									<p className="error-message">
										{touched.name && errors.name}
									</p>
								</div>
								<div className="form-field-container card">
									<CardSection />
								</div>
							</div>
						) : null}
						<div className="error-message-container" role="alert">
							<p className="error-message">{error}</p>
						</div>
						<div className="button-bar">
							<button
								className="cancel"
								onClick={() => backFunction()}
							>
								{__('Cancel', 'wubtitle')}
							</button>
							<button
								disabled={!stripe || loading}
								className={loading ? 'disabled' : ''}
							>
								{loading && (
									<i className="fa fa-refresh fa-spin loading-margin" />
								)}
								{__('Confirm order', 'wubtitle')}
							</button>
						</div>
					</Form>
				)}
			</Formik>
		</div>
	);
}
