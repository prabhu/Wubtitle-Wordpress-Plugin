<?php
/**
 * Questo file implementa le funzioni che vengono eseguite all'attivazione del plugin.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Wubtitle\Api
 */

namespace Wubtitle\Core;

/**
 * Questa classe mplementa le funzioni che vengono eseguite all'attivazione del plugin.
 */
class Activation {
	/**
	 * Init class action.
	 */
	public function run() {
		register_activation_hook( WUBTITLE_FILE_URL, array( $this, 'wubtitle_activation_license_key' ) );
	}

	/**
	 * All'attivazione del plugin chiama l'endpoint per ricevere la license key.
	 */
	public function wubtitle_activation_license_key() {
		$site_url      = get_site_url();
		$body          = array(
			'data' => array(
				'domainUrl' => $site_url,
				'siteLang'  => explode( '_', get_locale(), 2 )[0],
			),
		);
		$response      = wp_remote_post(
			ENDPOINT . 'key/create',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode( $body ),
			)
		);
		$code_response = wp_remote_retrieve_response_code( $response );
		if ( 201 === $code_response ) {
			$response_body = json_decode( wp_remote_retrieve_body( $response ) );
			update_option( 'wubtitle_free', $response_body->data->isFree );
			update_option( 'wubtitle_license_key', $response_body->data->licenseKey );
		}
	}
}
