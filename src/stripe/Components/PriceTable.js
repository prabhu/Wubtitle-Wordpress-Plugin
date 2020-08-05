import React from 'react';
import { __ } from '@wordpress/i18n';

const PriceTable = (props) => {
	let {
		price,
		taxPercentage,
		taxAmount,
		total,
		taxable,
		discountedPrice,
	} = props;
	if (discountedPrice) {
		total = taxable ? discountedPrice.newTotal : discountedPrice.price;
		taxAmount = discountedPrice.newTax;
	}
	return (
		<table className="price-table">
			<tr>
				<td>{__('Price', 'wubtitle')}</td>
				{discountedPrice ? (
					<td className="val">
						<span className="cut-vat">
							{price} &euro;
							<span className="cut-line" />
						</span>
						{parseFloat(discountedPrice.price)} &euro;
					</td>
				) : (
					<td className="val">{price} &euro;</td>
				)}
			</tr>
			<tr>
				<td>
					{__('VAT', 'wubtitle')} ({taxPercentage}%)
				</td>
				{taxable ? (
					<td className="val">{taxAmount} &euro;</td>
				) : (
					<td className="val">
						0 &euro;{' '}
						<span className="description">
							{__('no Vat due for you', 'wubtitle')}
						</span>
					</td>
				)}
			</tr>
			<tr className="total">
				<td>{__('Total', 'wubtitle')}</td>
				<td className="val">
					{total} &euro;
					<span className="valxm">
						{__(' per month', 'wubtitle')}
					</span>
				</td>
			</tr>
		</table>
	);
};

export default PriceTable;
