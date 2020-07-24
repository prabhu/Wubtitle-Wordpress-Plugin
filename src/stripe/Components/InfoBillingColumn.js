import React, { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { ReactComponent as InfoIcon } from '../../../assets/img/info-white-18dp.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';
import PriceTable from './PriceTable';
import Disclaimer from './Disclaimer';
import SubscribeName from './SubscribeName';

const InfoPriceColumn = (props) => {
	const { price, name, taxAmount, taxPercentage, taxable } = props;
	let total = parseFloat(price);
	if (taxable) {
		total = parseFloat(price) + parseFloat(taxAmount);
	}
	const [isOpen, setIsOpen] = useState(false);
	return (
		<div className="column price-column">
			<div className="price">
				<SubscribeName name={name} />
				<p className="mobile-price-info is-hidden-on-desktop">
					<span className="total">{total} &euro; </span>
					<span className="valxm">{__('per month', 'wubtitle')}</span>
					<InfoIcon
						className="info-icon"
						onClick={() => setIsOpen(!isOpen)}
					/>
				</p>
				<PriceTable
					price={price}
					taxPercentage={taxPercentage}
					taxAmount={taxAmount}
					taxable={taxable}
					total={total}
				/>
			</div>
			<Disclaimer />
			<div
				className={
					isOpen ? 'mobile-price-view opened' : 'mobile-price-view'
				}
			>
				<div className="top">
					<div
						className="nav-back"
						onClick={() => setIsOpen(!isOpen)}
						aria-hidden="true"
					>
						<span>
							<FontAwesomeIcon icon={faArrowLeft} />
							Subscription details
						</span>
					</div>
					<SubscribeName name={name} />
					<PriceTable
						price={price}
						taxPercentage={taxPercentage}
						taxAmount={taxAmount}
						taxable={taxable}
						total={total}
					/>
				</div>
				<Disclaimer />
			</div>
		</div>
	);
};

export default InfoPriceColumn;
