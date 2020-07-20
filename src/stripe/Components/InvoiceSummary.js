import { __ } from '@wordpress/i18n';
export default function InvoiceSummary(props) {
	const { invoiceValues } = props;

	return (
		<div className="summary">
			<h2>{__('Billing Recap', 'wubtitle')}</h2>
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
			<p>
				<strong>{__('Postal Code', 'wubtitle')}: </strong>
				{invoiceValues.cap}
			</p>
			<p>
				<strong>{__('Address', 'wubtitle')}: </strong>
				{invoiceValues.address}
			</p>
			<p>
				<strong>{__('VAT Code', 'wubtitle')}: </strong>
				{invoiceValues.vat_code}
			</p>

			<p>-----------------------</p>

			<p>
				<strong>{__('Lastname', 'wubtitle')}: </strong>
				{invoiceValues.invoice_lastname}
			</p>
			<p>
				<strong>{__('Company Name', 'wubtitle')}: </strong>
				{invoiceValues.company_name}
			</p>
			<p>
				<strong>{__('Province', 'wubtitle')}: </strong>
				{invoiceValues.province}
			</p>
			<p>
				<strong>{__('City', 'wubtitle')}: </strong>
				{invoiceValues.city}
			</p>
			<p>
				<strong>{__('Fiscal Code', 'wubtitle')}: </strong>
				{invoiceValues.fiscal_code}
			</p>
			<p>
				<strong>{__('Destination Code', 'wubtitle')}: </strong>
				{invoiceValues.destination_code}
			</p>
		</div>
	);
}
