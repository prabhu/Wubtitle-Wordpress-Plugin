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

		return $response;
	}

}
