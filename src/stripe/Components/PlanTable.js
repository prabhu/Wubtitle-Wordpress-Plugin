import React from 'react';
import { __ } from '@wordpress/i18n';

const PlanTable = (props) => {
	const { currentPlan, currentPrice, renewal } = props;

	return (
		<table className="price-table">
			<tr>
				<td>{__('Your plan', 'wubtitle')}</td>
				<td className="val">{currentPlan}</td>
			</tr>
			<tr className="total">
				<td>{__('Price', 'wubtitle')}</td>
				<td className="val">
					{currentPrice} &euro;
					<span className="valxm">{__('per month', 'wubtitle')}</span>
				</td>
			</tr>
			{renewal ? (
				<tr className="total">
					<td>{__('Automatic renewal', 'wubtitle')}</td>
					<td className="val">{renewal}</td>
				</tr>
			) : (
				''
			)}
		</table>
	);
};

export default PlanTable;
