import React, { useState } from 'react';
import { __ } from '@wordpress/i18n';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faChevronDown } from '@fortawesome/free-solid-svg-icons';

export default function InvoiceSummary(props) {
	const { invoiceValues } = props;
	const [isOpen, setIsOpen] = useState(false);
	const [accordionClass, setAccordionClass] = useState('closed');

	const handleClick = () => {
		setIsOpen(!isOpen);
		if (isOpen) {
			setAccordionClass('opened');
		} else {
			setAccordionClass('closed');
		}
	};

	return (
		<div className={`summary ${accordionClass}`}>
			<div className="accordion-bar">
				<h2>{__('Billing Recap', 'wubtitle')}</h2>
				<span
					className={`accordion is-hidden-on-desktop ${accordionClass}`}
					onClick={handleClick}
					aria-hidden="true"
				>
					<FontAwesomeIcon icon={faChevronDown} />
				</span>
			</div>
			<div className="columns">
				<div className="column">
					<p>
						<strong>{__('Name', 'wubtitle')}: </strong>
						{invoiceValues.invoice_name}
					</p>
					<p>
						<strong>{__('Email', 'wubtitle')}: </strong>
						{invoiceValues.invoice_email}
					</p>
					<p>
						<strong>{__('Country', 'wubtitle')}: </strong>
						{invoiceValues.country}
					</p>
					{invoiceValues.cap ? (
						<p>
							<strong>{__('Postal Code', 'wubtitle')}: </strong>
							{invoiceValues.cap}
						</p>
					) : (
						''
					)}
					<p>
						<strong>{__('Address', 'wubtitle')}: </strong>
						{invoiceValues.address}
					</p>

					{invoiceValues.vat_code ? (
						<p>
							<strong>{__('VAT Code', 'wubtitle')}: </strong>
							{invoiceValues.vat_code}
						</p>
					) : (
						''
					)}
				</div>

				<div className="column">
					<p>
						<strong>{__('Lastname', 'wubtitle')}: </strong>
						{invoiceValues.invoice_lastname}
					</p>
					<p>
						<strong>{__('Company Name', 'wubtitle')}: </strong>
						{invoiceValues.company_name}
					</p>
					{invoiceValues.province ? (
						<p>
							<strong>{__('Province', 'wubtitle')}: </strong>
							{invoiceValues.province}
						</p>
					) : (
						''
					)}

					<p>
						<strong>{__('City', 'wubtitle')}: </strong>
						{invoiceValues.city}
					</p>
					{invoiceValues.fiscal_code ? (
						<p>
							<strong>{__('Fiscal Code', 'wubtitle')}: </strong>
							{invoiceValues.fiscal_code}
						</p>
					) : (
						''
					)}
					{invoiceValues.destination_code !== '0000000' ? (
						<p>
							<strong>
								{__('Destination Code', 'wubtitle')}:{' '}
							</strong>
							{invoiceValues.destination_code}
						</p>
					) : (
						''
					)}
				</div>
			</div>
		</div>
	);
}
