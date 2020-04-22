<?php
/**
 * This file implements.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

/**
 * This class describes.
 */
class ApiCancelSubscription {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'wp_ajax_cancel_subscription', array( $this, 'remote_request' ) );
	}

	/**
	 * Chiamata ad endpoint remoto per richiesta cancellazione.
	 */
	public function remote_request() {
		$license_key = get_option( 'ear2words_license_key' );

		$headers = array(
			'Content-Type' => 'application/json; charset=utf-8',
			'licenseKey'   => $license_key,
		);

		$response = wp_remote_post(
			// TODO: cambiare endapoint con quello che verrà formito da Simone.
			ENDPOINT . 'stripe/subscription/cancel',
			array(
				'method'  => 'POST',
				'headers' => $headers,
			)
		);

		$status = wp_remote_retrieve_response_code( $response );

		return $status;
	}

	/**
	 * Check dello status.
	 *
	 * @param int $status response code della chiamata all'endpoint remoto.
	 */
	public function check_response( $status ) {
		if ( 200 === $status ) {
			// Cancellazione andata a buon fine.
			$message = ' Cancellazione andata a buon fine';
		} elseif ( 403 === $status ) {
			// License key non valida.
			$message = 'License key non valida';
		} elseif ( 401 === $status ) {
			// License key non esiste.
			$message = 'License key non esiste';
		}
		return $message;
	}

}
