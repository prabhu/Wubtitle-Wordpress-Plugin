export default function InvoiceSummary(props) {
	const { invoiceValues } = props;

	return (
		<div>
			<h2>SUMMARY</h2>
			<h3>Name</h3>
			<p>{invoiceValues.invoice_name}</p>
			<h3>Lastname</h3>
			<p>{invoiceValues.invoice_lastname}</p>
			<h3>Email</h3>
			<p>{invoiceValues.invoice_email}</p>
			<h3>Address</h3>
			<p>{invoiceValues.address}</p>
			<h3>Cap</h3>
			<p>{invoiceValues.cap}</p>
			<h3>City</h3>
			<p>{invoiceValues.city}</p>
			<h3>Company Name</h3>
			<p>{invoiceValues.company_name}</p>
			<h3>Country</h3>
			<p>{invoiceValues.country}</p>
			<h3>Fiscal Code</h3>
			<p>{invoiceValues.fiscal_code}</p>
			<h3>Province</h3>
			<p>{invoiceValues.province}</p>
			<h3>Telephone</h3>
			<p>{invoiceValues.telephone}</p>
			<h3>Vat Code</h3>
			<p>{invoiceValues.vat_code}</p>
		</div>
	);
}
