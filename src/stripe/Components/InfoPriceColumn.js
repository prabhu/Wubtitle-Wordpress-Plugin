/**
 * Use the CSS tab above to style your Element's container.
 */
import React from 'react';
import { __ } from '@wordpress/i18n';

const InfoPriceColumn = (props) => {
	const { price, name, taxAmount, taxPercentage, taxable } = props;
	let total = parseFloat(price);
	if (taxable) {
		total = parseFloat(price) + parseFloat(taxAmount);
	}
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
							{__('VAT', 'wubtitle')} ({taxPercentage}%)
						</td>
						{taxable ? (
							<td className="val">{taxAmount} &euro;</td>
						) : (
							<td className="val">
								<span className="cut-vat">
									{taxAmount} &euro;
									<span className="cut-line">
										{/* css only */}
									</span>
								</span>
								0 &euro;
							</td>
						)}
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
