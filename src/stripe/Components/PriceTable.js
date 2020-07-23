import React from 'react';
import { __ } from '@wordpress/i18n';

const PriceTable = (props) => {
	const { price, taxPercentage, taxAmount, total, taxable } = props;

	return (
		<table className="price-table">
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
							{taxAmount} &euro;vatPer
							<span className="cut-line">{/* css only */}</span>
						</span>
						0 &euro;
					</td>
				)}
			</tr>
			<tr className="total">
				<td>{__('Total', 'wubtitle')}</td>
				<td className="val">
					{total} &euro;{' '}
					<span className="valxm">{__('per month', 'wubtitle')}</span>
				</td>
			</tr>
		</table>
	);
};

export default PriceTable;
