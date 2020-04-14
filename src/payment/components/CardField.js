import React from "react";
import { CardElement } from "@stripe/react-stripe-js";

const CARD_OPTIONS = {
	iconStyle: "solid",
	style: {
		base: {
			iconColor: "#fff",
			color: "#fff",
			fontWeight: 500,
			fontFamily: "Roboto, Open Sans, Segoe UI, sans-serif",
			fontSize: "16px",
			fontSmoothing: "antialiased",
			":-webkit-autofill": {
				color: "#fff"
			},
			"::placeholder": {
				color: "#fff"
			}
		},
		invalid: {
			iconColor: "#ffc7ee",
			color: "#ffc7ee"
		}
	}
};

const CardField = ({ onChange }) => (
	<div className="FormRow">
		<CardElement options={CARD_OPTIONS} onChange={onChange} />
	</div>
);

export default CardField;
