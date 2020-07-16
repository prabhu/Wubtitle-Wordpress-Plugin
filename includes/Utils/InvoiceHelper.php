<?php
/**
 * Helper for create a invoice.
 *
 * @author     Alessio Catania
 * @since      1.0.0
 * @package    Wubtitle\Utils
 */

namespace Wubtitle\Utils;

/**
 * Class helper for invoice
 */
class InvoiceHelper {

	/**
	 * European Countries file contents
	 *
	 * @var array<string>|\WP_Error
	 */
	private $eu_countries_file = array();

	/**
	 * Init file contents
	 *
	 * @return void
	 */
	public function run() {
		$this->eu_countries_file = wp_remote_get( WUBTITLE_URL . 'build_form/europeanCountries.json' );
	}

	/**
	 * Build a array containing the invoice data.
	 *
	 * @param object $invoice_object invoice data object.
	 *
	 * @return array<string>|false
	 */
	public function build_invoice_array( $invoice_object ) {
		$eu_countries = json_decode( wp_remote_retrieve_body( $this->eu_countries_file ) );
		if ( ! isset( $invoice_object->invoice_name, $invoice_object->invoice_lastname, $invoice_object->invoice_email, $invoice_object->telephone, $invoice_object->address, $invoice_object->city, $invoice_object->country ) ) {
			return false;
		}
		$invoice_details = array(
			'Name'      => $invoice_object->invoice_name,
			'LastName'  => $invoice_object->invoice_lastname,
			'Email'     => $invoice_object->invoice_email,
			'Telephone' => $invoice_object->telephone,
			'Address'   => $invoice_object->address,
			'City'      => $invoice_object->city,
			'Country'   => $invoice_object->country,
		);

		if ( ! empty( $invoice_object->company_name ) ) {
			$invoice_details['CompanyName'] = $invoice_object->company_name;
		}
		if ( ! in_array( $invoice_object->country, $eu_countries, true ) ) {
			return $invoice_details;
		}
		if ( empty( $invoice_object->fiscal_code ) && empty( $invoice_object->vat_code ) ) {
			return false;
		}
		if ( 'IT' === $invoice_object->country ) {
			$invoice_details = $this->italian_invoice( $invoice_details, $invoice_object );
		}
		if ( ! isset( $invoice_details['FiscalCode'] ) && isset( $invoice_object->vat_code ) ) {
			$invoice_details['VatCode'] = $invoice_object->vat_code;
		}
		return $invoice_details;
	}

	/**
	 * Function for add fields for italian invoice
	 *
	 * @param array<string> $invoice_details array content init value.
	 * @param object        $invoice_object invoice data object.
	 *
	 * @return array<string>
	 */
	public function italian_invoice( $invoice_details, $invoice_object ) {
		if ( isset( $invoice_object->cap, $invoice_object->province ) ) {
			$invoice_details['PostCode'] = $invoice_object->cap;
			$invoice_details['Province'] = $invoice_object->province;
		}
		if ( empty( $invoice_object->company_name ) && isset( $invoice_object->fiscal_code ) ) {
			$invoice_details['FiscalCode'] = $invoice_object->fiscal_code;
		}
		return $invoice_details;
	}
}
