import React from 'react';
import { __ } from '@wordpress/i18n';

const PlanTable = (props) => {
	const { currentPlan, currentPrice, renewal, taxable, taxAmount } = props;

	return (
		<table className="price-table">
			<tr>
				<td>{__('Your plan', 'wubtitle')}</td>
				<td className="val">{currentPlan}</td>
			</tr>
			<tr>
				<td>
					<td>{__('Price', 'wubtitle')}</td>
				</td>
				{taxable ? (
					<td className="val">
						{parseFloat(currentPrice) + parseFloat(taxAmount)}{' '}
						&euro;{' '}
						<span className="valxm">
							{__('per month', 'wubtitle')}
						</span>
					</td>
				) : (
					<td className="val">
						<span className="cut-vat">
							{parseFloat(currentPrice) + parseFloat(taxAmount)}
							&euro;
							<span className="cut-line" />
						</span>
						{currentPrice} &euro;
					</td>
				)}
			</tr>
			{renewal ? (
				<tr className="total">
					<td>{__('Automatic renewal', 'wubtitle')}</td>
					<td className="val">{renewal}</td>
				</tr>
			) : null}
		</table>
	);
};

export default PlanTable;
