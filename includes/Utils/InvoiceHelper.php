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
	 * Init delle action
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'wp_ajax_check_vat_code', array( $this, 'check_vat_code' ) );
	}

	/**
	 * Calls the backend endpoint to check vat code.
	 *
	 * @return void
	 */
	public function check_vat_code() {
		if ( ! isset( $_POST['_ajax_nonce'], $_POST['price_plan'], $_POST['vat_code'], $_POST['country'] ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$nonce    = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		$price    = (float) sanitize_text_field( wp_unslash( $_POST['price_plan'] ) );
		$vat_code = sanitize_text_field( wp_unslash( $_POST['vat_code'] ) );
		$country  = sanitize_text_field( wp_unslash( $_POST['country'] ) );
		check_ajax_referer( 'itr_ajax_nonce', $nonce );
		$body        = array(
			'data' => array(
				'vatCode'     => $vat_code,
				'price'       => $price,
				'countryCode' => $country,
			),
		);
		$license_key = get_option( 'wubtitle_license_key' );
		if ( empty( $license_key ) ) {
			wp_send_json_error( __( 'Error. The product license key is missing.', 'wubtitle' ) );
		}
		$response      = wp_remote_post(
			WUBTITLE_ENDPOINT . 'stripe/customer/tax',
			array(
				'method'  => 'POST',
				'headers' => array(
					'licenseKey'   => $license_key,
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode( $body ),
			)
		);
		$code_response = wp_remote_retrieve_response_code( $response );
		$message       = array(
			'400' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'401' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'403' => __( 'Access denied', 'wubtitle' ),
			'500' => __( 'Could not contact the server', 'wubtitle' ),
			''    => __( 'Could not contact the server', 'wubtitle' ),
		);
		if ( 200 !== $code_response ) {
			wp_send_json_error( $message[ $code_response ] );
		}
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );
		$tax           = $response_body->data->taxAmount;
		wp_send_json_success( $tax );
	}

	/**
	 * Build a array containing the invoice data.
	 *
	 * @param object $invoice_object invoice data object.
	 *
	 * @return array<string>|false
	 */
	public function build_invoice_array( $invoice_object ) {
		$eu_countries_file = wp_remote_get( WUBTITLE_URL . 'build_form/europeanCountries.json' );
		$eu_countries      = json_decode( wp_remote_retrieve_body( $eu_countries_file ) );
		if ( ! isset( $invoice_object->invoice_name, $invoice_object->invoice_lastname, $invoice_object->invoice_email, $invoice_object->telephone, $invoice_object->prefix_telephone, $invoice_object->address, $invoice_object->city, $invoice_object->country ) ) {
			return false;
		}
		$invoice_details = array(
			'Name'            => $invoice_object->invoice_name,
			'LastName'        => $invoice_object->invoice_lastname,
			'Email'           => $invoice_object->invoice_email,
			'Telephone'       => $invoice_object->telephone,
			'TelephonePrefix' => $invoice_object->prefix_telephone,
			'Address'         => $invoice_object->address,
			'City'            => $invoice_object->city,
			'Country'         => $invoice_object->country,
		);

		if ( ! in_array( $invoice_object->country, $eu_countries, true ) ) {
			if ( ! empty( $invoice_object->company_name ) ) {
				$invoice_details['CompanyName'] = $invoice_object->company_name;
			}
			return $invoice_details;
		}
		if ( ! empty( $invoice_object->company_name ) ) {
			$invoice_details['CompanyName'] = $invoice_object->company_name;
			if ( empty( $invoice_object->vat_code ) ) {
				return false;
			}
			$invoice_details['VatCode'] = $invoice_object->vat_code;
		}
		if ( 'IT' === $invoice_object->country ) {
			$invoice_details = $this->italian_invoice( $invoice_details, $invoice_object );
		}
		return $invoice_details;
	}

	/**
	 * Function for add fields for italian invoice
	 *
	 * @param array<string> $invoice_details array content init value.
	 * @param object        $invoice_object invoice data object.
	 *
	 * @return array<string>|false
	 */
	public function italian_invoice( $invoice_details, $invoice_object ) {
		if ( empty( $invoice_object->cap ) || empty( $invoice_object->province ) ) {
			return false;
		}
		$invoice_details['PostCode'] = $invoice_object->cap;
		$invoice_details['Province'] = $invoice_object->province;
		if ( ! empty( $invoice_object->fiscal_code ) ) {
			$invoice_details['FiscalCode'] = $invoice_object->fiscal_code;
		}
		if ( ! empty( $invoice_object->destination_code ) ) {
			$invoice_details['DestinationCode'] = $invoice_object->destination_code;
		}
		return $invoice_details;
	}
	/**
	 * Calls the aws endpoint to receive the invoice data.
	 *
	 * @return array<mixed>|false
	 */
	public function get_invoice_data() {
		$license_key = get_option( 'wubtitle_license_key' );
		if ( empty( $license_key ) ) {
			wp_send_json_error( __( 'Error. The product license key is missing.', 'wubtitle' ) );
		}
		$response      = wp_remote_post(
			WUBTITLE_ENDPOINT . 'stripe/customer/invoice-details',
			array(
				'method'  => 'POST',
				'headers' => array(
					'licenseKey' => $license_key,
				),
			)
		);
		$code_response = wp_remote_retrieve_response_code( $response );
		if ( 200 !== $code_response ) {
			return false;
		}
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );
		if ( ! isset( $response_body->data->invoiceDetails, $response_body->data->paymentDetails ) ) {
			return false;
		}
		$invoice_details = $response_body->data->invoiceDetails;
		$payment_details = $response_body->data->paymentDetails;
		$invoice_data    = array(
			'invoice_name'     => $invoiceDetails->Name,
			'invoice_email'    => $invoiceDetails->Email,
			'invoice_lastname' => $invoiceDetails->LastName,
			'telephone'        => $invoiceDetails->Telephone,
			'prefix_telephone' => $invoiceDetails->TelephonePrefix,
			'company_name'     => $invoiceDetails->CompanyName,
			'address'          => $invoiceDetails->Address,
			'cap'              => $invoiceDetails->PostCode,
			'city'             => $invoiceDetails->City,
			'province'         => $invoiceDetails->Province,
			'country'          => $invoiceDetails->Country,
			'vat_code'         => $invoiceDetails->VatCode,
			'fiscal_code'      => $invoiceDetails->FiscalCode,
			'destination_code' => $invoiceDetails->DestinationCode,
		);
		$payment_data    = array(
			'name'            => $paymentDetails->name,
			'email'           => $paymentDetails->email,
			'expiration'      => $paymentDetails->expiration,
			'cardNumber'      => $paymentDetails->card,
			'paymentMethodId' => $paymentDetails->paymentMethodId,
		);
		return array(
			'invoice_data' => $invoice_data,
			'payment_data' => $payment_data,
		);
	}
}
