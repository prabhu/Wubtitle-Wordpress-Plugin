<?php
/**
 * In this file is implemented the functions performed when the plugin is activated.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Wubtitle\Api
 */

namespace Wubtitle\Core;

/**
 * This class implements the functions performed when the plugin is activated.
 */
class Activation {
	/**
	 * Init class action.
	 *
	 * @return void
	 */
	public function run() {
		register_activation_hook( WUBTITLE_FILE_URL, array( $this, 'wubtitle_activation_license_key' ) );
	}

	/**
	 * When the plugin is activated calls the endpoint to receive the license key.
	 *
	 * @return void
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
			WUBTITLE_ENDPOINT . 'key/create',
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
			update_option( 'wubtitle_free', $response_body->data->isFree, false );
			update_option( 'wubtitle_license_key', $response_body->data->licenseKey, false );
			$plans          = $response_body->data->plans;
			$wubtitle_plans = array_reduce( $plans, array( $this, 'plans_reduce' ), array() );
			update_option( 'wubtitle_all_plans', $wubtitle_plans, false );
		}
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
		$accumulator[ $item->rank ] = array(
			'name'         => $item->name,
			'stripe_code'  => $item->id,
			// phpcs:disable 
			// warning camel case
			'totalJobs'    => $item->totalJobs,
			'totalSeconds' => $item->totalSeconds,
			// phpcs:enable
			'price'        => $item->price,
			'dot_list'     => $item->dotlist,
			'icon'         => $item->icon,
		);
		return $accumulator;
	}
}
