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

	const [isOpen, setOpen] = useState(false);

	const handleClick = () => {
		setOpen(!isOpen);
	};

	const style = {
		open: {
			a: 'a',
			b: 'b'
		},
		close: {
			a: 'a',
			b: 'b'
		}
	};

	return (
		<div className="column price-column">
			<span style={isOpen ? style.open : style.close} className="toggle" onClick={handleClick()}>v</span>
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
							{__('VAT', 'wubtitle')} ({vatPer}%)
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
