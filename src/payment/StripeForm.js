import React from "react";
import ReactDOM from "react-dom";
import { loadStripe } from "@stripe/stripe-js";
import { Elements } from "@stripe/react-stripe-js";
import CheckoutForm from "./components/CheckoutForm";

const stripePromise = loadStripe("pk_test_6pRNASCoBOKtIshFeQd4XMUh");

function StripeForm() {
	return (
		<div className="FormWrapper">
			<Elements stripe={stripePromise}>
				<CheckoutForm />
			</Elements>
		</div>
	);
}

document.addEventListener("DOMContentLoaded", function() {
	if (document.getElementById("payment-form")) {
		ReactDOM.render(
			<StripeForm />,
			document.getElementById("payment-form")
		);
	}
});
