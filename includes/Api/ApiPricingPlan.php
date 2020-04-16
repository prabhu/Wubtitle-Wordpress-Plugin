<?php
/**
 * Questo file implementa la chiamata http per la scelta del pricing Plan.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

/**
 * Questa classe implementa la chiamata all'endpoint per il pricing Plan
 */
class ApiPricingPlan {
	/**
	 * Init delle action
	 */
	public function run() {
		add_action( 'wp_ajax_submit_plan', array( $this, 'send_plan' ) );
	}
	/**
	 *  Creo il body della richiesta.
	 *
	 * @param string $pricing_plan pricing plan.
	 * @param string $site_url url del sito.
	 */
	public function set_body_request( $pricing_plan, $site_url ) {
		if ( ! is_string( $pricing_plan ) || ! filter_var( $site_url, FILTER_VALIDATE_URL ) ) {
			return false;
		}
		$body = array(
			'data' => array(
				'planId'    => $pricing_plan,
				'domainUrl' => $site_url,
			),
		);
		return $body;
	}
	/**
	 * Riceve i dati da javascript e li invia all'endpoint.
	 */
	public function send_plan() {
		$site_url = get_site_url();
		if ( ! isset( $_POST['_ajax_nonce'] ) || ! isset( $_POST['pricing_plan'] ) || ! isset( $site_url ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'ear2words' ) );
		}
		$pricing_plan = sanitize_text_field( wp_unslash( $_POST['pricing_plan'] ) );
		$site_url     = sanitize_text_field( wp_unslash( $site_url ) );
		$nonce        = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		if ( ! check_ajax_referer( 'itr_ajax_nonce', $nonce ) ) {
			wp_send_json_error( __( 'Error, invalid request', 'ear2words' ) );
		}
		$body          = $this->set_body_request( $pricing_plan, $site_url );
		$response      = wp_remote_post(
			ENDPOINT . 'stripe/session/create',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode( $body ),
			)
		);
		$code_response = $this->check_response( $response ) ? $response['response']['code'] : '500';
		$message       = array(
			'400' => __( 'An error occurred. Please try again in a few minutes', 'ear2words' ),
			'401' => __( 'An error occurred. Please try again in a few minutes', 'ear2words' ),
			'403' => __( 'Access denied', 'ear2words' ),
			'500' => __( 'Could not contact the server', 'ear2words' ),
		);
		if ( 201 !== $code_response ) {
			wp_send_json_error( $message[ $code_response ] );
		}
		$response_body = json_decode( $response['body'] );
		$session_id    = $response_body->data->sessionId;
		wp_send_json_success( $session_id );
	}
	/**
	 * Verifico che la chiamta non sia andata in errore.
	 *
	 * @param array | WP_ERROR $response risposta chiamata.
	 */
	private function check_response( $response ) {
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
