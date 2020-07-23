import React, { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { ReactComponent as InfoIcon } from '../../../assets/img/info-white-18dp.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';

const InfoPriceColumn = (props) => {
	const { price, name } = props;
	const vatPer = 22;
	const vat = ((price / 100) * vatPer).toFixed(2);
	const total = parseFloat(price) + parseFloat(vat);

	const [isOpen, setIsOpen] = useState(false);
	const [toggleClass, setToggleClass] = useState('closed');

	const handleClick = () => {
		setIsOpen(!isOpen);
		if (isOpen) {
			setToggleClass('opened');
		} else {
			setToggleClass('closed');
		}
	};

	return (
		<div className="column price-column">
			<div className="price">
				<p className="price-name">
					{__('Subscribe to', 'wubtitle')} {name}{' '}
					{__('plan', 'wubtitle')}
				</p>
				<p className="mobile-price-info">
					{total} &euro;{' '}
					<span className="valxm">
						{__('per mounth', 'wubtitle')}
					</span>
					<InfoIcon className="info-icon" onClick={handleClick} />
				</p>

				<table className="desktop-price-info">
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
			<div className={`mobile-price-view ${toggleClass}`}>
				<div className="top">
					<div
						className="nav-back"
						onClick={handleClick}
						aria-hidden="true"
					>
						<span>
							<FontAwesomeIcon icon={faArrowLeft} />
							Subscription details
						</span>
					</div>
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
				<div className="mobile-disclaimer">
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
		</div>
	);
};

export default InfoPriceColumn;
