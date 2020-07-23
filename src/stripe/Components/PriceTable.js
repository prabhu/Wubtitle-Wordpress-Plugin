import React from 'react';
import { __ } from '@wordpress/i18n';

const PriceTable = (props) => {
	const { price, vatPer, vat, total } = props;

	return (
		<table className="price-table">
			<tr>
				<td>{__('Price', 'wubtitle')}</td>
				<td className="val">{price} &euro;</td>
			</tr>
			<tr>
				<td>
					{__('VAT', 'wubtitle')} ({vatPer}%)
				</td>
				<td className="val">{vat} &euro;</td>
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
