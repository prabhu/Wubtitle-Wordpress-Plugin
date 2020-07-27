<?php
/**
 * In this file is created a new endpoint for the license key validation
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
 * This class manages the endpoint for the license key validation.
 */
class ApiLicenseValidation {
	/**
	 * Init class action.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'register_license_validation_route' ) );
		add_action( 'rest_api_init', array( $this, 'register_reset_invalid_license_route' ) );
	}

	/**
	 * Creates new REST route.
	 *
	 * @return void
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
	 * Creates new REST route.
	 *
	 * @return void
	 */
	public function register_reset_invalid_license_route() {
		register_rest_route(
			'wubtitle/v1',
			'/reset-user',
			array(
				'methods'  => 'GET',
				'callback' => array( $this, 'get_init_data' ),
			)
		);
	}


	/**
	 * JWT Authentication and reset user data.
	 *
	 * @param \WP_REST_Request $request valori della richiesta.
	 * @return WP_REST_Response|array<mixed>
	 */
	public function get_init_data( $request ) {
		$headers  = $request->get_headers();
		$jwt      = $headers['jwt'][0];
		$password = get_option( 'wubtitle_password' );
		try {
			JWT::decode( $jwt, $password, array( 'HS256' ) );
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
		$params = $request->get_param( 'data' );
		update_option( 'wubtitle_free', $params['isFree'], false );
		update_option( 'wubtitle_license_key', $params['licenseKey'], false );
		$plans          = $params['plans'];
		$wubtitle_plans = array_reduce( $plans, array( $this, 'plans_reduce' ), array() );
		update_option( 'wubtitle_all_plans', $wubtitle_plans, false );

		$message = array(
			'data' => array(
				'status' => '200',
				'title'  => 'Success',
			),
		);

		return $message;
	}

	/**
	 * JWT Authentication.
	 *
	 * @param \WP_REST_Request $request valori della richiesta.
	 * @return WP_REST_Response|array<string,array<string,array<int,mixed>>>
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
	 * Gets uuid jobs and returns it.
	 *
	 * @return array<string,array<string,array<int,mixed>>>
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

	/**
	 * Callback function array_reduce
	 *
	 * @param mixed $accumulator empty array.
	 * @param mixed $item object to reduce.
	 *
	 * @return mixed
	 */
	public function plans_reduce( $accumulator, $item ) {
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		// warning camel case.
		$accumulator[ $item->rank ] = array(
			'name'         => $item->name,
			'stripe_code'  => $item->id,
			'totalJobs'    => $item->totalJobs,
			'totalSeconds' => $item->totalSeconds,
			'price'        => $item->price,
			'dot_list'     => $item->dotlist,
			'icon'         => $item->icon,
		);
		return $accumulator;
	}
}
