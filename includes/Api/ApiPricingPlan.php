<?php
/**
 * In this file is implemented stripe related functions.
 *
 * @author     Alessio Catania
 * @since      1.0.0
 * @package    Wubtitle\Api
 */

namespace Wubtitle\Api;

/**
 * This class implements stripe related functions.
 */
class ApiPricingPlan {
	/**
	 * Init delle action
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'wp_ajax_submit_plan', array( $this, 'send_plan' ) );

		add_action( 'wp_ajax_reset_license', array( $this, 'reset_license' ) );
		add_action( 'wp_ajax_reactivate_plan', array( $this, 'reactivate_plan' ) );
		add_action( 'wp_ajax_update_payment_method', array( $this, 'update_payment_method' ) );
		add_action( 'wp_ajax_change_plan', array( $this, 'change_plan' ) );
	}
	/**
	 * Calls the backend endpoint to confirm the plan change.
	 *
	 * @return void
	 */
	public function change_plan() {
		$wanted_plan = get_option( 'wubtitle_wanted_plan' );
		if ( ! isset( $_POST['_ajax_nonce'] ) || empty( $wanted_plan ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		check_ajax_referer( 'itr_ajax_nonce', $nonce );
		$body        = array(
			'data' => array(
				'planId' => $wanted_plan,
			),
			'type' => 'plan',
		);
		$license_key = get_option( 'wubtitle_license_key' );
		if ( empty( $license_key ) ) {
			wp_send_json_error( __( 'Unable to create subtitles. The product license key is missing.', 'wubtitle' ) );
		}
		$response      = wp_remote_post(
			WUBTITLE_ENDPOINT . 'stripe/customer/update',
			array(
				'method'  => 'POST',
				'headers' => array(
					'licenseKey'   => $license_key,
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode( $body ),
			)
		);
		$code_response = $this->is_successful_response( $response ) ? wp_remote_retrieve_response_code( $response ) : '500';
		$message       = array(
			'400' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'401' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'403' => __( 'Access denied', 'wubtitle' ),
			'500' => __( 'Could not contact the server', 'wubtitle' ),
			''    => __( 'Could not contact the server', 'wubtitle' ),
		);
		if ( 200 !== $code_response ) {
			wp_send_json_error( $message[ $code_response ] );
		}
		delete_option( 'wubtitle_amount_preview' );
		delete_option( 'wubtitle_wanted_plan' );
		wp_send_json_success();
	}
	/**
	 * Calls the endpoint for plan reactivation.
	 *
	 * @return void
	 */
	public function reactivate_plan() {
		if ( ! isset( $_POST['_ajax_nonce'] ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		check_ajax_referer( 'itr_ajax_nonce', $nonce );
		$license_key = get_option( 'wubtitle_license_key' );
		if ( empty( $license_key ) ) {
			wp_send_json_error( __( 'Unable to create subtitles. The product license key is missing.', 'wubtitle' ) );
		}
		update_option( 'wubtitle_is_reactivating', true );
		$response      = wp_remote_post(
			WUBTITLE_ENDPOINT . 'stripe/customer/reactivate',
			array(
				'method'  => 'POST',
				'headers' => array(
					'licenseKey' => $license_key,
				),
			)
		);
		$code_response = $this->is_successful_response( $response ) ? wp_remote_retrieve_response_code( $response ) : '500';
		$message       = array(
			'400' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'403' => __( 'Access denied', 'wubtitle' ),
			'500' => __( 'Could not contact the server', 'wubtitle' ),
		);
		if ( 200 !== $code_response ) {
			wp_send_json_error( $message[ $code_response ] );
		}
		wp_send_json_success();
	}
	/**
	 *  Creates request body.
	 *
	 * @param string $pricing_plan pricing plan.
	 * @param string $site_url site url.
	 * @return array<array<string>>|false
	 */
	public function set_body_request( $pricing_plan, $site_url ) {
		if ( ! is_string( $pricing_plan ) || ! filter_var( $site_url, FILTER_VALIDATE_URL ) ) {
			return false;
		}
		return array(
			'data' => array(
				'planId'    => $pricing_plan,
				'domainUrl' => $site_url,
				'siteLang'  => explode( '_', get_locale(), 2 )[0],
			),
		);
	}
	/**
	 * Gets the data from JavaScript and sends it to the endpoint.
	 *
	 * @return void
	 */
	public function send_plan() {
		$site_url = get_site_url();
		if ( ! isset( $_POST['_ajax_nonce'], $_POST['pricing_plan'] ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$pricing_plan = sanitize_text_field( wp_unslash( $_POST['pricing_plan'] ) );
		$site_url     = sanitize_text_field( wp_unslash( $site_url ) );
		$nonce        = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		check_ajax_referer( 'itr_ajax_nonce', $nonce );
		$body = $this->set_body_request( $pricing_plan, $site_url );
		if ( ! $body ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$url_endpoint = WUBTITLE_ENDPOINT . 'stripe/session/create';
		$license_key  = get_option( 'wubtitle_license_key' );
		if ( empty( $license_key ) ) {
			wp_send_json_error( __( 'The product license key is missing.', 'wubtitle' ) );
		}
		// If the plan is not free calls the endpoint for plan update.
		if ( ! get_option( 'wubtitle_free' ) ) {
			$url_endpoint = WUBTITLE_ENDPOINT . 'stripe/customer/update/preview';
			unset( $body['data']['siteLang'] );
			unset( $body['data']['domainUrl'] );
		}
		$response      = wp_remote_post(
			$url_endpoint,
			array(
				'method'  => 'POST',
				'headers' => array(
					'licenseKey'   => $license_key,
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode( $body ),
			)
		);
		$code_response = $this->is_successful_response( $response ) ? wp_remote_retrieve_response_code( $response ) : '500';
		$message       = array(
			'400' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'401' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'403' => __( 'Access denied', 'wubtitle' ),
			'500' => __( 'Could not contact the server', 'wubtitle' ),
			''    => __( 'Could not contact the server', 'wubtitle' ),
		);
		// 200 if it is not the first payment.
		if ( 200 === $code_response ) {
			$response_body  = json_decode( wp_remote_retrieve_body( $response ) );
			$amount_preview = $response_body->data->amountPreview;
			update_option( 'wubtitle_amount_preview', $amount_preview );
			update_option( 'wubtitle_wanted_plan', $pricing_plan );
			wp_send_json_success( 'change_plan' );
		}
		// 201 if it is the first payment
		if ( 201 !== $code_response ) {
			wp_send_json_error( $message[ $code_response ] );
		}
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );
		$client_secret = $response_body->data->clientSecret;
		wp_send_json_success( $client_secret );
	}
	/**
	 * Gets the data from JavaScript and sends it to the endpoint for payment update.
	 *
	 * @return void
	 */
	public function update_payment_method() {
		if ( ! isset( $_POST['_ajax_nonce'] ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		check_ajax_referer( 'itr_ajax_nonce', $nonce );
		$license_key = get_option( 'wubtitle_license_key' );
		if ( empty( $license_key ) ) {
			wp_send_json_error( __( 'Unable to create subtitles. The product license key is missing.', 'wubtitle' ) );
		}
		$body          = array(
			'type' => 'payment',
		);
		$response      = wp_remote_post(
			WUBTITLE_ENDPOINT . 'stripe/customer/update',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8',
					'licenseKey'   => $license_key,
				),
				'body'    => wp_json_encode( $body ),
			)
		);
		$code_response = $this->is_successful_response( $response ) ? wp_remote_retrieve_response_code( $response ) : '500';
		$message       = array(
			'400' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'401' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'403' => __( 'Access denied', 'wubtitle' ),
			'500' => __( 'Could not contact the server', 'wubtitle' ),
			''    => __( 'Could not contact the server', 'wubtitle' ),
		);
		if ( 201 !== $code_response ) {
			wp_send_json_error( $message[ $code_response ] );
		}
		$response_body = json_decode( wp_remote_retrieve_body( $response ) );
		$session_id    = $response_body->data->sessionId;
		wp_send_json_success( $session_id );
	}
	/**
	 * Gets the data from JavaScript and sends it to the endpoint for license reset.
	 *
	 * @return void
	 */
	public function reset_license() {
		$site_url = get_site_url();
		if ( ! isset( $_POST['_ajax_nonce'] ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$nonce    = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		$site_url = sanitize_text_field( wp_unslash( $site_url ) );
		check_ajax_referer( 'itr_ajax_nonce', $nonce );
		$body          = array(
			'data' => array(
				'domainUrl' => $site_url,
			),
		);
		$response      = wp_remote_post(
			WUBTITLE_ENDPOINT . 'key/fetch',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode( $body ),
			)
		);
		$code_response = $this->is_successful_response( $response ) ? wp_remote_retrieve_response_code( $response ) : '500';
		$message       = array(
			'400' => __( 'An error occurred. Please try again in a few minutes', 'wubtitle' ),
			'403' => __( 'Access denied', 'wubtitle' ),
			'500' => __( 'Could not contact the server', 'wubtitle' ),
		);
		if ( 200 !== $code_response ) {
			wp_send_json_error( $message[ $code_response ] );
		}
		wp_send_json_success();
	}
	/**
	 * Checks if the request was successful.
	 *
	 * @param array<string>|\WP_Error $response response to the request.
	 * @return bool
	 */
	private function is_successful_response( $response ) {
		if ( ! is_wp_error( $response ) ) {
			return true;
		}
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG === true ) {
			// phpcs:disable WordPress.PHP.DevelopmentFunctions
			error_log( print_r( $response->get_error_message(), true ) );
			// phpcs:enable
		}
		return false;
	}
}
