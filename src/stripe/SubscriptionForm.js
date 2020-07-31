import React, { useState } from 'react';
import ReactDOM from 'react-dom';
import CheckoutForm from './Components/CheckoutForm';
import InvoiceForm from './Components/InvoiceForm';
import InvoiceSummary from './Components/InvoiceSummary';
import InfoPriceColumn from './Components/InfoPriceColumn';
import { Elements } from '@stripe/react-stripe-js';
import { loadStripe } from '@stripe/stripe-js';

function App() {
	const {
		wubtitleEnv,
		planId,
		ajaxUrl,
		ajaxNonce,
		pricePlan,
		namePlan,
		taxAmount,
		taxPercentage,
	} = WP_GLOBALS;
	const stripeKey =
		wubtitleEnv === 'development'
			? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
			: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
	const stripePromise = loadStripe(stripeKey);

	const [loading, setLoading] = useState(false);
	const [coupon, setCoupon] = useState(null);
	const [error, setError] = useState(null);
	const [invoiceValues, setInvoiceValues] = useState(null);
	const [isBack, setIsBack] = useState(false);
	const [discountedPrice, setDiscountedPrice] = useState(false);

	const handleSubmit = (values) => {
		setLoading(true);
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=check_vat_code&_ajax_nonce=${ajaxNonce}&vat_code=${values.vat_code}&country=${values.country}&price_plan=${pricePlan}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				setLoading(false);
				if (response.success) {
					setError(null);
					values.tax = response.data;
					setInvoiceValues(values);
					if (isBack) {
						setIsBack(false);
					}
				} else {
					setError(response.data);
				}
			});
	};

	const backFunction = () => {
		setIsBack(true);
		setError(null);
	};
	const cancelFunction = () => {
		window.opener.cancelPayment();
	};

	const confirmPayment = (clientSecret, paymentMethod, stripe) => {
		stripe
			.confirmCardPayment(clientSecret, {
				payment_method: paymentMethod,
				setup_future_usage: 'off_session',
			})
			.then((result) => {
				if (result.paymentIntent.status === 'succeeded') {
					setLoading(false);
					setError(null);
					window.opener.redirectToCallback('notices-code=payment');
					window.close();
				}
				if (result.error) {
					setLoading(false);
					setError(result.error.message);
				}
			});
	};

	const sendPaymentMethod = (setupIntent, stripe, values) => {
		const { name, email } = values;
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=confirm_subscription&actionCheckout=create&coupon=${coupon}&name=${name}&email=${email}&planId=${planId}&_ajax_nonce=${ajaxNonce}&setupIntent=${JSON.stringify(
				setupIntent
			)}`,
		})
			.then((resp) => resp.json())
			.then((result) => {
				if (result.success) {
					setError(null);
					if (
						result.data &&
						result.data.status === 'requires_action'
					) {
						setError(null);
						confirmPayment(
							result.data.clientSecret,
							setupIntent.paymentMethod,
							stripe
						);
					} else {
						setLoading(false);
						window.opener.redirectToCallback(
							'notices-code=payment'
						);
						window.close();
					}
				} else {
					setLoading(false);
					setError(result.data);
				}
			});
	};

	const confirmSetup = (clientSecret, cardNumber, values, stripe) => {
		const { name, email } = values;
		stripe
			.confirmCardSetup(clientSecret, {
				payment_method: {
					type: 'card',
					card: cardNumber,
					billing_details: {
						name,
						email,
					},
				},
			})
			.then((result) => {
				if (
					result.setupIntent &&
					result.setupIntent.status === 'succeeded'
				) {
					setError(null);
					sendPaymentMethod(result, stripe, values);
				}
				if (result.error) {
					setLoading(false);
					setError(result.error.message);
				}
			});
	};

	const createSubscription = (cardNumber, values, stripe) => {
		setLoading(true);
		const { email } = values;
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=create_subscription&actionCheckout=create&email=${email}&_ajax_nonce=${ajaxNonce}&invoiceObject=${JSON.stringify(
				invoiceValues
			)}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					confirmSetup(response.data, cardNumber, values, stripe);
				} else {
					setLoading(false);
					setError(response.data);
				}
			});
	};

	const updatePrice = (currentPrice) => {
		if (currentPrice) {
			setDiscountedPrice(currentPrice);
		} else {
			setDiscountedPrice(false);
		}
	};

	return (
		<div className="main columns">
			<InfoPriceColumn
				update={false}
				price={pricePlan}
				name={namePlan}
				taxAmount={taxAmount}
				taxPercentage={taxPercentage}
				taxable={invoiceValues ? invoiceValues.tax : true}
				discountedPrice={discountedPrice}
			/>

			<Elements stripe={stripePromise}>
				{invoiceValues && !isBack ? (
					<div className="wrapper-form column">
						<InvoiceSummary
							invoiceValues={invoiceValues}
							price={pricePlan}
						/>
						<CheckoutForm
							createSubscription={createSubscription}
							error={error}
							backFunction={backFunction}
							paymentPreValues={null}
							updatePrice={updatePrice}
							loading={loading}
							coupon={coupon}
							setCoupon={setCoupon}
						/>
					</div>
				) : (
					<InvoiceForm
						handleSubmit={handleSubmit}
						invoicePreValues={invoiceValues}
						error={error}
						cancelFunction={cancelFunction}
						loading={loading}
					/>
				)}
			</Elements>
		</div>
	);
}
if (document.getElementById('root')) {
	ReactDOM.render(<App />, document.getElementById('root'));
}
