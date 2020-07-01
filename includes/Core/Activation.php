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
			update_option( 'wubtitle_free', $response_body->data->isFree );
			update_option( 'wubtitle_license_key', $response_body->data->licenseKey );
			$plans          = $response_body->data->plans;
			$wubtitle_plans = array();
			foreach ( $plans as $plan ) {
				$wubtitle_plans[ $plan->rank ] = array(
					'name'         => $plan->name,
					'stripe_code'  => $plan->id,
					// phpcs:disable 
					// warning camel case
					'totalJobs'    => $plan->totalJobs,
					'totalSeconds' => $plan->totalSeconds,
					// phpcs:enable
					'price'        => $plan->price,
					'dot_list'     => $plan->dot_list,
					'icon'         => $plan->icon,
				);
			}
			update_option( 'wubtitle_all_plans', $wubtitle_plans );
		}
	}
}
