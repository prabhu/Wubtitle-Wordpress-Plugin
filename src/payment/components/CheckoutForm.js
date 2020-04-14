import React, { useState } from "react";
import { CardElement, useElements, useStripe } from "@stripe/react-stripe-js";
import CardField from "./CardField";
import Field from "./Field";
import SubmitButton from "./SubmitButton";
import ErrorMessage from "./ErrorMessage";
import ResetButton from "./ResetButton";

const CheckoutForm = () => {
	const stripe = useStripe();
	const elements = useElements();
	const [error, setError] = useState(null);
	const [cardComplete, setCardComplete] = useState(false);
	const [processing, setProcessing] = useState(false);
	const [paymentMethod, setPaymentMethod] = useState(null);
	const [billingDetails, setBillingDetails] = useState({
		email: "",
		phone: "",
		name: ""
	});

	const handleSubmit = async event => {
		event.preventDefault();

		if (!stripe || !elements) {
			// Stripe.js has not loaded yet. Make sure to disable
			// form submission until Stripe.js has loaded.
			return;
		}

		if (error) {
			elements.getElement("card").focus();
			return;
		}

		if (cardComplete) {
			setProcessing(true);
		}

		const payload = await stripe.createPaymentMethod({
			type: "card",
			card: elements.getElement(CardElement),
			billing_details: billingDetails
		});

		setProcessing(false);

		if (payload.error) {
			setError(payload.error);
		} else {
			setPaymentMethod(payload.paymentMethod);
		}
	};

	const reset = () => {
		setError(null);
		setProcessing(false);
		setPaymentMethod(null);
		setBillingDetails({
			email: "",
			phone: "",
			name: ""
		});
	};

	return paymentMethod ? (
		<div className="Result">
			<div className="ResultTitle" role="alert">
				Payment successful
			</div>
			<div className="ResultMessage">
				Thanks for trying Stripe Elements. No money was charged, but we
				generated a PaymentMethod: {paymentMethod.id}
			</div>
			<ResetButton onClick={reset} />
		</div>
	) : (
		<form className="Form" onSubmit={handleSubmit}>
			<fieldset className="FormGroup">
				<Field
					label="Name"
					id="name"
					type="text"
					placeholder="Jane Doe"
					required
					autoComplete="name"
					value={billingDetails.name}
					onChange={e => {
						setBillingDetails({
							...billingDetails,
							name: e.target.value
						});
					}}
				/>
				<Field
					label="Email"
					id="email"
					type="email"
					placeholder="janedoe@gmail.com"
					required
					autoComplete="email"
					value={billingDetails.email}
					onChange={e => {
						setBillingDetails({
							...billingDetails,
							email: e.target.value
						});
					}}
				/>
				<Field
					label="Phone"
					id="phone"
					type="tel"
					placeholder="(941) 555-0123"
					required
					autoComplete="tel"
					value={billingDetails.phone}
					onChange={e => {
						setBillingDetails({
							...billingDetails,
							phone: e.target.value
						});
					}}
				/>
			</fieldset>
			<fieldset className="FormGroup">
				<CardField
					onChange={e => {
						setError(e.error);
						setCardComplete(e.complete);
					}}
				/>
			</fieldset>
			{error && <ErrorMessage>{error.message}</ErrorMessage>}
			<SubmitButton
				processing={processing}
				error={error}
				disabled={!stripe}
			>
				Pay
			</SubmitButton>
		</form>
	);
};

export default CheckoutForm;
