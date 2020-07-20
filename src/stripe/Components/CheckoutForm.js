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
	const { createSubscription, backFunction, error } = props;
	const stripe = useStripe();
	const elements = useElements();
	const [loading, setLoading] = useState(false);
	const requiredMessage = __('Required', 'wubtitle');
	const DisplayingErrorMessagesSchema = Yup.object().shape({
		name: Yup.string().required(requiredMessage),
		email: Yup.string()
			.email(__('Invalid email', 'wubtitle'))
			.required(requiredMessage),
		lastname: Yup.string().required(requiredMessage),
	});

	const handleSubmit = async (values) => {
		const { name, lastname } = values;
		if (!stripe || !elements) {
			return;
		}
		setLoading(true);
		const cardNumber = elements.getElement(CardNumberElement);

		const fullName = `${name} ${lastname}`;

		await stripe
			.createPaymentMethod({
				type: 'card',
				card: cardNumber,
				billing_details: {
					name: fullName,
				},
			})
			.then((response) => {
				createSubscription(response.paymentMethod.id, values);
				setLoading(false);
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
						<label htmlFor="email">E-Mail</label>
						<Field name="email" placeholder="Email" />
						<p className="error-message">
							{touched.email && errors.email}
						</p>
					</div>
					<div className="form-field-container">
						<label htmlFor="name">{__('Name', 'wubtitle')}</label>
						<Field name="name" placeholder="Name" />
						<p className="error-message">
							{touched.name && errors.name}
						</p>
					</div>
					{/* <div className="form-field-container">
						<label htmlFor="lastname">
							{__('Lastname', 'wubtitle')}
						</label>
						<Field name="lastname" placeholder="Lastname" />
						<p className="error-message">
							{touched.lastname && errors.lastname}
						</p>
					</div> */}

					<div className="form-field-container card">
						<CardSection />
					</div>
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
	);
}
