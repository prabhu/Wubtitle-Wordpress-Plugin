<?php
/**
 * Questo file crea un nuovo endpoint per la validazione della license key.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;
use WP_REST_Controller;
use WP_REST_Response;
use WP_Error;

/**
 * Questa classe gestisce l'endpoint per la validazione della license key
 */
class ApiLicenseValidation extends WP_REST_Controller {
	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'register_license_validation_route' ));
	}

	/**
	 * Crea nuova rotta REST
	 */
	public function register_license_validation_route() {
		register_rest_route( 'ear2words/v1', '/job-list/(?P<licensekey>[a-zA-Z0-9-]+)', array(
			'methods' => 'GET',
			'callback' => array( $this, 'get_job_list' ),
		) );
	}

	/**
	 * Ottiene gli uuid dei post
	 */
	public function get_job_list( $request ) {
		$params = $request->get_params();
		$request_license_key = $params['licensekey'];
		$db_license_key = get_option( 'ear2words_license_key' );

		if ( $request_license_key !== $db_license_key ) {
			// TODO: Gestire messaggio di errore
			return new WP_Error( 'code', __( 'message', 'text-domain' ) );
		} else {
			// query dei post che hanno uuid fra i meta	
			$args = array(
				// TODO: Cambiare in "media", attualmente ho inserito manualmente il meta ai fini del test
				'post_type'   => 'post',
				'meta_key'    => 'uuid',
			);
			$posts = get_posts( $args );
			$job_list = array();
			foreach ($posts as  $post) {
				$job_list[] = get_post_meta( $post->ID, 'uuid', true );
			}
			return new WP_REST_Response( $job_list, 200 );
		}		
	}
}




