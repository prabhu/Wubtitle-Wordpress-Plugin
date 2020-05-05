<?php
/**
 * Questo file crea un nuovo endpoint per lo store del file .
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

use WP_REST_Response;
use \Firebase\JWT\JWT;

/**
 * Questa classe gestisce lo store dei file vtt.
 */
class ApiAuthUpgradePlan {
	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'register_auth_plan_upgrade_route' ) );
	}

	/**
	 * Crea nuova rotta REST.
	 */
	public function register_auth_plan_upgrade_route() {
		register_rest_route(
			'ear2words/v1',
			'/auth-plan-upgrade',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'auth_and_get_plan' ),
			)
		);
	}

	/**
	 * Autenticazione JWT.
	 *
	 * @param array $request valori della richiesta.
	 */
	public function auth_and_get_plan( $request ) {
		$headers        = $request->get_headers();
		$jwt            = $headers['jwt'][0];
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

			$response = new WP_REST_Response( $error );

			$response->set_status( 403 );

			return $response;
		}
		return $this->return_plan();
	}

	/**
	 * Ottiene il file dei sottotitoli e lo salva, inoltre aggiunge dei post meta al video.
	 */
	public function return_plan() {
		$plan_to_upgrade = get_option( 'ear2words_plan_to_upgrade' );
		update_option( '', false );

		$message = array(
			'message' => array(
				'status'    => '200',
				'title'     => 'Plan to upgrade',
				'plan_code' => $plan_to_upgrade,
			),
		);

		$response = new WP_REST_Response( $message );

		$response->set_status( 200 );

		return $response;
	}
}
