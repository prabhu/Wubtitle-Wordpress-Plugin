import { __ } from '@wordpress/i18n';
export default function InvoiceSummary(props) {
	const { invoiceValues, price } = props;

	return (
		<div>
			<h2>{__('Price', 'wubtitle')}</h2>
			<p>
				<strong>{price}</strong>+<strong>{invoiceValues.tax}</strong>
			</p>
			<h2>{__('Summary', 'wubtitle')}</h2>
			<h3>{__('Name', 'wubtitle')}</h3>
			<p>{invoiceValues.invoice_name}</p>
			<h3>{__('lastname', 'wubtitle')}</h3>
			<p>{invoiceValues.invoice_lastname}</p>
			<h3>Email</h3>
			<p>{invoiceValues.invoice_email}</p>
			<h3>{__('Address', 'wubtitle')}</h3>
			<p>{invoiceValues.address}</p>
			<h3>{__('Postal Code', 'wubtitle')}</h3>
			<p>{invoiceValues.cap}</p>
			<h3>{__('City', 'wubtitle')}</h3>
			<p>{invoiceValues.city}</p>
			<h3>{__('Company Name', 'wubtitle')}</h3>
			<p>{invoiceValues.company_name}</p>
			<h3>{__('Country', 'wubtitle')}</h3>
			<p>{invoiceValues.country}</p>
			<h3>{__('Fiscal Code', 'wubtitle')}</h3>
			<p>{invoiceValues.fiscal_code}</p>
			<h3>{__('Province', 'wubtitle')}</h3>
			<p>{invoiceValues.province}</p>
			<h3>{__('Telephone', 'wubtitle')}</h3>
			<p>{invoiceValues.telephone}</p>
			<h3>{__('Vat Code', 'wubtitle')}</h3>
			<p>{invoiceValues.vat_code}</p>
		</div>
	);
}
