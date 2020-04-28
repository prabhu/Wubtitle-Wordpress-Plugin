<?php
/**
 * This file describes handle WP_Cron functions.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Core
 */

namespace Ear2Words\Core;

/**
 * This class handle WP_Cron functions.
 */
class Cron {
	/**
	 * Init class actions.
	 */
	public function run() {
		add_action( 'e2w_cron', array( $this, 'get_remote_data' ) );
		add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );
		register_activation_hook( EAR2WORDS_FILE_URL, array( $this, 'schedule_cron' ) );
		register_deactivation_hook( EAR2WORDS_FILE_URL, array( $this, 'unschedule_cron' ) );
		add_action( 'init', array( $this, 'schedule_cron' ) );
	}

	/**
	 * .
	 */
	public function schedule_cron() {
		if ( ! wp_next_scheduled( 'e2w_cron' ) ) {
			wp_schedule_event( time(), 'five_seconds', 'e2w_cron' );
		}
	}


	/**
	 * Disattiva cron.
	 */
	public function unschedule_cron() {
		// when the last event was scheduled.
		$timestamp = wp_next_scheduled( '' );
		// unschedule previous event if any.
		wp_unschedule_event( $timestamp, '' );
	}



	/**
	 * Cron.
	 *
	 * @param array $schedules parametro.
	 */
	public function add_cron_interval( $schedules ) {
		$schedules['five_seconds'] = array(
			'interval' => 999,
			'display'  => esc_html__( 'Every Five Seconds', 'ear2words' ),
		);
		return $schedules;
	}



	/**
	 * Fetch info.
	 */
	public function get_remote_data() {
		$license_key = get_option( 'ear2words_license_key' );

		$response = wp_remote_post(
			ENDPOINT . 'get/info/test',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8',
					'licenseKey'   => $license_key,
				),
			)
		);

		$code_response = wp_remote_retrieve_response_code( $response );
		if ( 200 === $code_response ) {
			$body_response = json_decode( wp_remote_retrieve_body( $response ) );
			update_option( 'ear2words_plan', $body_response->data->plan );
			update_option( 'ear2words_expiration_date', $body_response->data->expirationDate );
			update_option( 'ear2words_is_first_month', $body_response->data->isFirstMonth );
			update_option( 'ear2words_is_canceling', $body_response->data->isCanceling );
			update_option( 'ear2words_total_jobs', $body_response->data->totalJobs );
			update_option( 'ear2words_total_seconds', $body_response->data->totalSeconds );
		}
	}
}
