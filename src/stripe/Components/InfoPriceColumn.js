import React, { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { ReactComponent as InfoIcon } from '../../../assets/img/info-white-18dp.svg';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons';
import PriceTable from './PriceTable';
import Disclaimer from './Disclaimer';
import SubscribeName from './SubscribeName';

const InfoPriceColumn = (props) => {
	const { price, name } = props;
	const vatPer = 22;
	const vat = ((price / 100) * vatPer).toFixed(2);
	const total = parseFloat(price) + parseFloat(vat);

	const [isOpen, setIsOpen] = useState(false);

	return (
		<div className="column price-column">
			<div className="price">
				<SubscribeName name={name} />
				<p className="mobile-price-info">
					<span className="total">{total} &euro; </span>
					<span className="valxm">{__('per month', 'wubtitle')}</span>
					<InfoIcon
						className="info-icon"
						onClick={() => setIsOpen(!isOpen)}
					/>
				</p>
				<PriceTable
					price={price}
					vatPer={vatPer}
					vat={vat}
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
						vatPer={vatPer}
						vat={vat}
						total={total}
					/>
				</div>
				<Disclaimer />
			</div>
		</div>
	);
};

export default InfoPriceColumn;
