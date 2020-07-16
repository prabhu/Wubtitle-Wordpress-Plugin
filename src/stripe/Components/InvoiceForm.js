import * as Yup from 'yup';
import { Formik, Form, Field } from 'formik';
import countries from '../data/countries.json';
import provinces from '../data/provinces.json';
import euCountries from '../data/europeanCountries.json';

export default function CheckoutForm(props) {
	const DisplayingErrorMessagesSchema = Yup.lazy((values) => {
		const yupObject = {
			invoice_name: Yup.string().required('Required'),
			invoice_email: Yup.string()
				.email('Invalid email')
				.required('Required'),
			invoice_lastname: Yup.string().required('Required'),
			telephone: Yup.string().required('Required'),
			address: Yup.string().required('Required'),
			city: Yup.string().required('Required'),
			country: Yup.string().required('Required'),
		};
		if (!euCountries.includes(values.country)) {
			return Yup.object().shape(yupObject);
		}
		if (values.company_name) {
			yupObject.vat_code = Yup.string().required('Required');
		} else if (values.country === 'IT') {
			yupObject.fiscal_code = Yup.string().required('Required');
		}
		if (values.country === 'IT') {
			yupObject.cap = Yup.string().required('Required');
			yupObject.province = Yup.string().required('Required');
		}
		return Yup.object().shape(yupObject);
	});

	return (
		<div className="wrapper-form">
			<Formik
				initialValues={{
					invoice_name: '',
					invoice_email: '',
					invoice_lastname: '',
					telephone: '',
					company_name: '',
					address: '',
					cap: '',
					city: '',
					province: '',
					country: '',
					vat_code: '',
					fiscal_code: '',
					destination_code: '0000000',
				}}
				validationSchema={DisplayingErrorMessagesSchema}
				onSubmit={(values) => {
					props.handleSubmit(values);
				}}
			>
				{({ errors, touched, values }) => (
					<Form>
						<h2> Billing Details</h2>

						<div className="form-field-container">
							<label htmlFor="invoiceName">Name</label>
							<Field name="invoice_name" placeholder="Name" />
							<p className="error-message">
								{touched.invoice_name && errors.invoice_name}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="invoiceLastname">Lastname</label>
							<Field
								name="invoice_lastname"
								placeholder="Lastname"
							/>
							<p className="error-message">
								{touched.invoice_lastname &&
									errors.invoice_lastname}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="invoiceEmail">E-Mail</label>
							<Field name="invoice_email" placeholder="Email" />
							<p className="error-message">
								{touched.invoice_email && errors.invoice_email}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="companyName">Company Name</label>
							<Field
								name="company_name"
								placeholder="Company Name"
							/>
							<p className="error-message">
								{touched.company_name && errors.company_name}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="country">Country</label>
							<Field name="country" component="select">
								<option value="" label={'Select a country'} />
								{Object.entries(countries).map(
									([key, value]) => (
										<option
											key={key}
											value={key}
											label={value}
										/>
									)
								)}
							</Field>
							<p className="error-message">
								{touched.country && errors.country}
							</p>
						</div>
						<div
							className={`form-field-container ${
								values.country !== 'IT' ? 'hidden' : ''
							}`}
						>
							<label htmlFor="province">Province</label>
							<Field name="province" component="select">
								<option value="" label={'Select a province'} />
								{Object.entries(provinces).map(
									([key, value]) => (
										<option
											key={key}
											value={key}
											label={value}
										/>
									)
								)}
							</Field>
							<p className="error-message">
								{touched.province && errors.province}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="city">City</label>
							<Field name="city" placeholder="City" />
							<p className="error-message">
								{touched.city && errors.city}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="address">Address</label>
							<Field name="address" placeholder="Address" />
							<p className="error-message">
								{touched.address && errors.address}
							</p>
						</div>
						<div
							className={`form-field-container ${
								values.country !== 'IT' ? 'hidden' : ''
							}`}
						>
							<label htmlFor="cap">CAP</label>
							<Field name="cap" placeholder="CAP" />
							<p className="error-message">
								{touched.cap && errors.cap}
							</p>
						</div>
						<div
							className={`form-field-container ${
								!values.company_name ||
								!euCountries.includes(values.country)
									? 'hidden'
									: ''
							}`}
						>
							<label htmlFor="vatCode">Vat Code</label>
							<Field name="vat_code" placeholder="Vat Code" />
							<p className="error-message">
								{touched.vat_code && errors.vat_code}
							</p>
						</div>
						<div
							className={`form-field-container ${
								'IT' !== values.country ? 'hidden' : ''
							}`}
						>
							<label htmlFor="fiscalCode">Fiscal Code</label>
							<Field
								name="fiscal_code"
								placeholder="Fiscal Code"
							/>
							<p className="error-message">
								{touched.fiscal_code && errors.fiscal_code}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="telephone">Telephone</label>
							<Field name="telephone" placeholder="Telephone" />
							<p className="error-message">
								{touched.telephone && errors.telephone}
							</p>
						</div>
						<div
							className={`form-field-container ${
								values.country !== 'IT' ? 'hidden' : ''
							}`}
						>
							<label htmlFor="destination-code">
								Destination Code
							</label>
							<Field
								name="destination_code"
								placeholder="Destination Code"
							/>
							<p className="error-message">
								{touched.destination_code &&
									errors.destination_code}
							</p>
						</div>
						<button>Summary</button>
					</Form>
				)}
			</Formik>
		</div>
	);
}
