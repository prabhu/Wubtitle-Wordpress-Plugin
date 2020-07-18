import { useState } from 'react';
import * as Yup from 'yup';
import { __ } from '@wordpress/i18n';
import { Formik, Form, Field } from 'formik';
import countries from '../data/countries.json';
import provinces from '../data/provinces.json';
import euCountries from '../data/europeanCountries.json';

export default function CheckoutForm(props) {
	const { invoicePreValues, handleSubmit, error } = props;
	const [loading, setLoading] = useState(false);
	const requiredMessage = __('Required', 'wubtitle');
	const DisplayingErrorMessagesSchema = Yup.lazy((values) => {
		const yupObject = {
			invoice_name: Yup.string().required(requiredMessage),
			invoice_email: Yup.string()
				.email(__('Invalid email', 'wubtitle'))
				.required(requiredMessage),
			invoice_lastname: Yup.string().required(requiredMessage),
			telephone: Yup.string()
				.required(requiredMessage)
				.matches('^[0-9]*$', __('Only numbers', 'wubtitle')),
			address: Yup.string().required(requiredMessage),
			city: Yup.string().required(requiredMessage),
			country: Yup.string().required(requiredMessage),
		};
		if (!euCountries.includes(values.country)) {
			return Yup.object().shape(yupObject);
		}
		if (values.company_name) {
			yupObject.vat_code = Yup.string().required(requiredMessage);
		} else if (values.country === 'IT') {
			yupObject.fiscal_code = Yup.string()
				.required(requiredMessage)
				.length(
					16,
					__('Fiscal Code must be exactly 16 characters', 'wubtitle')
				);
			yupObject.vat_code = Yup.string()
				.required(requiredMessage)
				.length(
					11,
					__('Vat Code must be exactly 11 characters', 'wubtitle')
				);
		}
		if (values.country === 'IT') {
			yupObject.cap = Yup.string()
				.required(requiredMessage)
				.length(
					5,
					__('Postal Code must be exactly 5 characters', 'wubtitle')
				)
				.matches('^[0-9]*$', 'Only numbers');
			yupObject.province = Yup.string().required(requiredMessage);
			yupObject.destination_code = Yup.string().length(
				7,
				__('Destination Code must be exactly 7 characters', 'wubtitle')
			);
		}
		return Yup.object().shape(yupObject);
	});
	let initValues = {
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
	};
	if (invoicePreValues) {
		initValues = {
			...invoicePreValues,
		};
	}

	return (
		<div className="wrapper-form">
			<Formik
				initialValues={initValues}
				validationSchema={DisplayingErrorMessagesSchema}
				onSubmit={(values) => {
					setLoading(true);
					handleSubmit(values);
					setLoading(false);
				}}
			>
				{({ errors, touched, values }) => (
					<Form>
						<h2> Billing Details</h2>

						<div className="form-field-container">
							<label htmlFor="invoiceName">
								{__('Name', 'wubtitle')}
							</label>
							<Field name="invoice_name" placeholder="Name" />
							<p className="error-message">
								{touched.invoice_name && errors.invoice_name}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="invoiceLastname">
								{__('Lastname', 'wubtitle')}
							</label>
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
							<label htmlFor="companyName">
								{__('Company Name', 'wubtitle')}
							</label>
							<Field
								name="company_name"
								placeholder="Company Name"
							/>
							<p className="error-message">
								{touched.company_name && errors.company_name}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="country">
								{__('Country', 'wubtitle')}
							</label>
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
							<label htmlFor="province">
								{__('Province', 'wubtitle')}
							</label>
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
							<label htmlFor="city">
								{__('City', 'wubtitle')}
							</label>
							<Field name="city" placeholder="City" />
							<p className="error-message">
								{touched.city && errors.city}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="address">
								{__('Address', 'wubtitle')}
							</label>
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
							<label htmlFor="cap">
								{__('Postal Code', 'wubtitle')}
							</label>
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
							<label htmlFor="vatCode">
								{__('Vat Code', 'wubtitle')}
							</label>
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
							<label htmlFor="fiscalCode">
								{__('Fiscal Code', 'wubtitle')}
							</label>
							<Field
								name="fiscal_code"
								placeholder="Fiscal Code"
							/>
							<p className="error-message">
								{touched.fiscal_code && errors.fiscal_code}
							</p>
						</div>
						<div className="form-field-container">
							<label htmlFor="telephone">
								{__('Telephone', 'wubtitle')}
							</label>
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
								{__('Destination Code', 'wubtitle')}
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
						<div className="error-message-container" role="alert">
							<p className="error-message">{error}</p>
						</div>
						<button>
							{loading && (
								<i className="fa fa-refresh fa-spin loading-margin" />
							)}
							{__('Summary', 'wubtitle')}
						</button>
					</Form>
				)}
			</Formik>
		</div>
	);
}