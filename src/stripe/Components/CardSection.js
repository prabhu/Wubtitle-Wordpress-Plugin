/**
 * Use the CSS tab above to style your Element's container.
 */
import React from 'react';
import { __ } from '@wordpress/i18n';
import {
	CardNumberElement,
	CardExpiryElement,
	CardCvcElement,
} from '@stripe/react-stripe-js';

const CARD_ELEMENT_OPTIONS = {
	style: {
		base: {
			color: '#32325d',
			fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
			fontSmoothing: 'antialiased',
			fontSize: '16px',
			'::placeholder': {
				color: '#aab7c4',
			},
		},
		invalid: {
			color: '#fa755a',
			iconColor: '#fa755a',
		},
	},
};

function CardSection() {
	return (
		<label htmlFor="form">
			{__('Card details', 'wubtitle')}
			<CardNumberElement options={CARD_ELEMENT_OPTIONS} className="num" />
			<CardExpiryElement options={CARD_ELEMENT_OPTIONS} className="exp" />
			<CardCvcElement options={CARD_ELEMENT_OPTIONS} className="cvc" />
		</label>
	);
}

export default CardSection;
