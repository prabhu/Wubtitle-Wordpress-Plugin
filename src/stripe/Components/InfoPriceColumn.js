/**
 * Use the CSS tab above to style your Element's container.
 */
import React from 'react';
import { __ } from '@wordpress/i18n';

const InfoPriceColumn = (props) => {
	const { price, name } = props;
	const vatPer = 22;
	const vat = ((price / 100) * vatPer).toFixed(2);
	const total = parseFloat(price) + parseFloat(vat);
	return (
		<div className="column price-column">
			<div className="price">
				<p className="price-name">
					{__('Subscribe to', 'wubtitle')} {name}{' '}
					{__('plan', 'wubtitle')}
				</p>
				<table>
					<tr>
						<td>{__('Price', 'wubtitle')}</td>
						<td className="val">{price} &euro;</td>
					</tr>
					<tr>
						<td>
							{__('vat', 'wubtitle')} ({vatPer}%)
						</td>
						<td className="val">{vat} &euro;</td>
					</tr>
					<tr className="total">
						<td>{__('Total', 'wubtitle')}</td>
						<td className="val">
							{total} &euro;{' '}
							<span className="valxm">
								{__('per mounth', 'wubtitle')}
							</span>
						</td>
					</tr>
				</table>
			</div>
			<div className="disclaimer">
				<p>
					{__(
						'When ordering within the EU an order may be exempt to VAT if a valid VAT registration number is provided.',
						'wubtitle'
					)}
				</p>
				<p>
					<a
						href="https://stripe.com/checkout/terms"
						rel="noreferrer"
						target="_blank"
					>
						{__('Terms and conditions', 'wubtitle')}
					</a>
					<span> | </span>
					<a
						href="https://stripe.com/it/privacy"
						target="_blank"
						rel="noreferrer"
					>
						{__('Privacy', 'wubtitle')}
					</a>
				</p>
			</div>
		</div>
	);
};

export default InfoPriceColumn;
