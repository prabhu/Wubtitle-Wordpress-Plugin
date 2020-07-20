/**
 * Use the CSS tab above to style your Element's container.
 */
import React from 'react';
// import { __ } from '@wordpress/i18n';

const InfoPriceColumn = (props) => {
	const { price, name } = props;
	const vatPer = 22;
	const vat = ((price / 100) * vatPer).toFixed(2);
	const total = parseFloat(price) + parseFloat(vat);
	return (
		<div className="column price-column">
			<div className="price">
				<p className="price-name">Subscribe to {name} plan</p>
				<table>
					<tr>
						<td>Price</td>
						<td className="val">{price}</td>
					</tr>
					<tr>
						<td>VAT ({vatPer}%)</td>
						<td className="val">{vat}</td>
					</tr>
					<tr className="total">
						<td>Total</td>
						<td className="val">{total} per month</td>
					</tr>
				</table>
			</div>
			<div className="disclaimer">
				<p>lorem lorevareg sdgv ergvre grgergerge gergreg wefrgwergw</p>
				<p>
					<a
						href="https://google.it"
						rel="noreferrer"
						target="_blank"
					>
						Terms and conditions
					</a>
					|
					<a
						href="https://google.it"
						target="_blank"
						rel="noreferrer"
					>
						Privacy
					</a>
				</p>
			</div>
		</div>
	);
};

export default InfoPriceColumn;
