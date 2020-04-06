<?php
/**
 * Questo file crea un nuovo endpoint per la validazione della license key.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

use WP_Error;
use WP_REST_Response;
use \Firebase\JWT\JWT;

/**
 * Questa classe gestisce l'endpoint per la validazione della license key.
 */
class ApiLicenseValidation {
	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'register_license_validation_route' ) );
	}

	/**
	 * Crea nuova rotta REST.
	 */
	public function register_license_validation_route() {
		register_rest_route(
			'ear2words/v1',
			'/job-list',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'auth_and_get_job_list' ),
			)
		);
	}

	/**
	 * Autenticazione JWT.
	 *
	 * @param array $request valori della richiesta.
	 */
	public function auth_and_get_job_list( $request ) {
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
		return $this->get_job_list();
	}

	/**
	 * Ottiene gli uuid dei post.
	 */
	public function get_job_list() {
		$args     = array(
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'meta_key'       => 'ear2words_status',
			'meta_value'     => 'pending',
		);
		$media    = get_posts( $args );
		$job_list = array();
		foreach ( $media as  $file ) {
			$job_list[] = get_post_meta( $file->ID, 'ear2words_job_uuid', true );
		}
		$data = array(
			'data' => array(
				'job_list' => $job_list,
			),
		);
		return $data;
	}
}
