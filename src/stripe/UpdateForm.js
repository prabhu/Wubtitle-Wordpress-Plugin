import React, { useState, useEffect } from 'react';
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
		ajaxUrl,
		ajaxNonce,
		pricePlan,
		invoicePreValues,
		paymentPreValues,
		namePlan,
		expirationDate,
		taxAmount,
		taxPercentage,
		isTaxable,
	} = WP_GLOBALS;
	const stripeKey =
		wubtitleEnv === 'development'
			? 'pk_test_lFmjf2Dz7VURTslihG0xys7m00NjW2BOPI'
			: 'pk_live_PvwHkJ49ry3lfXwkXIx2YKBE00S15aBYz7';
	const stripePromise = loadStripe(stripeKey);

	const [error, setError] = useState(null);
	const [invoiceValues, setInvoiceValues] = useState(null);
	const [isBack, setIsBack] = useState(false);
	const [taxable, setTaxable] = useState(true);

	useEffect(() => {
		if (isTaxable !== null) {
			setTaxable(isTaxable);
		}
	}, [isTaxable]);

	const handleSubmit = (values) => {
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=check_vat_code&_ajax_nonce=${ajaxNonce}&vat_code=${values.vat_code}&country=${values.country}&price_plan=${pricePlan}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					setError(null);
					setTaxable(response.data);
					setInvoiceValues(values);
					if (isBack) {
						setIsBack(false);
					}
				} else {
					setError(response.data);
				}
			});
	};

	const createSubscription = (paymentMethodId, values) => {
		const { name, email, lastname } = values;
		fetch(ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: `action=update_payment_method&paymentMethodId=${paymentMethodId}&email=${email}&_ajax_nonce=${ajaxNonce}&name=${name}&lastname=${lastname}&invoiceObject=${JSON.stringify(
				invoiceValues
			)}`,
		})
			.then((resp) => resp.json())
			.then((response) => {
				if (response.success) {
					setError(null);
					window.opener.redirectToCallback('notices-code=update');
					window.close();
				}
				setError(response.data);
			});
	};

	const backFunction = () => {
		setIsBack(true);
		setError(null);
	};

	const cancelFunction = () => {
		window.close();
	};

	return (
		<div className="main columns">
			<InfoPriceColumn
				update={true}
				price={pricePlan}
				name={namePlan}
				taxAmount={taxAmount}
				taxPercentage={taxPercentage}
				taxable={taxable}
				expirationDate={expirationDate}
				discountedPrice={false}
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
							backFunction={backFunction}
							paymentPreValues={paymentPreValues}
							error={error}
							setError={setError}
						/>
					</div>
				) : (
					<InvoiceForm
						handleSubmit={handleSubmit}
						invoicePreValues={invoiceValues || invoicePreValues}
						error={error}
						cancelFunction={cancelFunction}
					/>
				)}
			</Elements>
		</div>
	);
}
if (document.getElementById('update-form')) {
	ReactDOM.render(<App />, document.getElementById('update-form'));
}
