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
			'/job-list/(?P<licensekey>[a-zA-Z0-9-]+)',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_job_list' ),
			)
		);
	}

	/**
	 * Ottiene gli uuid dei post.
	 *
	 * @param array $request valori della richiesta.
	 */
	public function get_job_list( $request ) {
		$params              = $request->get_params();
		$request_license_key = $params['licensekey'];
		$db_license_key      = get_option( 'ear2words_license_key' );
		if ( $request_license_key !== $db_license_key ) {
			return new WP_Error( 'invalid_license_key', __( 'Invalid license key. Check your key.', 'ear2words' ), array( 'status' => 401 ) );
		}
		$args     = array(
			'post_type'      => 'attachment',
			'posts_per_page' => -1,
			'meta_key'       => 'ear2words_job_uuid',
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




