<?php
/**
 * This file create a new endpoint for the license key validation
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Wubtitle\Api
 */

namespace Wubtitle\Api;

use WP_Error;
use WP_REST_Response;
use \Firebase\JWT\JWT;

/**
 * This class manages endpoint for the license key validation.
 */
class ApiLicenseValidation {
	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'register_license_validation_route' ) );
	}

	/**
	 * Create new REST route.
	 */
	public function register_license_validation_route() {
		register_rest_route(
			'wubtitle/v1',
			'/job-list',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'auth_and_get_job_list' ),
			)
		);
	}

	/**
	 * JWT Authentication.
	 *
	 * @param array $request valori della richiesta.
	 */
	public function auth_and_get_job_list( $request ) {
		$headers        = $request->get_headers();
		$jwt            = $headers['jwt'][0];
		$db_license_key = get_option( 'wubtitle_license_key' );
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
	 * Get uuid jobs and returns it.
	 */
	public function get_job_list() {
		$args     = array(
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'meta_key'       => 'wubtitle_status',
			'meta_value'     => 'pending',
		);
		$media    = get_posts( $args );
		$job_list = array();
		foreach ( $media as  $file ) {
			$job_list[] = get_post_meta( $file->ID, 'wubtitle_job_uuid', true );
		}
		$data = array(
			'data' => array(
				'job_list' => $job_list,
			),
		);
		return $data;
	}
}
