<?php
/**
 * Questo file crea un nuovo endpoint per lo store del file .
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

/**
 * Questa classe gestisce lo store dei file vtt.
 */
class ApiStoreSubtitle {
	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'register_store_subtitle_route' ) );
	}

	/**
	 * Crea nuova rotta REST.
	 */
	public function register_store_subtitle_route() {
		register_rest_route(
			'ear2words/v1',
			'/store-subtitle',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'auth_and_get_subtitle' ),
			)
		);
	}

	/**
	 * Autenticazione JWT.
	 *
	 * @param array $request valori della richiesta.
	 */
	public function auth_and_get_subtitle( $request ) {
		$headers        = $request->get_headers();
		$jwt            = $headers['jwt'][0];
		$file           = $headers['file'][0];
		$db_license_key = get_option( 'ear2words_license_key' );
		try {
			JWT::decode( $jwt, $db_license_key, array( 'HS256' ) );
		} catch ( \Exception $e ) {
			$error = array(
				'errors' => array(
					'status' => '403',
					'title'  => 'Authentication Failed',
					'source' => $e->getMessage(),
				),
			);
			return $error;
		}
		return $this->get_subtitle($file);
	}

	/**
	 * Ottiene .
	 */
	public function get_subtitle($file) {
		// TODO: da fare
	}
}




