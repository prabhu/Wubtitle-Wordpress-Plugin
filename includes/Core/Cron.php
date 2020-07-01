<?php
/**
 * This file describes handle WP_Cron functions.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Wubtitle\Core
 */

namespace Wubtitle\Core;

/**
 * This class handle WP_Cron functions.
 */
class Cron {
	/**
	 * Init class actions.
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'e2w_cron', array( $this, 'get_remote_data' ) );
		register_activation_hook( WUBTITLE_FILE_URL, array( $this, 'get_remote_data' ) );
		register_deactivation_hook( WUBTITLE_FILE_URL, array( $this, 'unschedule_cron' ) );
		add_action( 'init', array( $this, 'schedule_cron' ) );
	}

	/**
	 * Add new scedule cron.
	 *
	 * @return void
	 */
	public function schedule_cron() {
		if ( ! wp_next_scheduled( 'e2w_cron' ) ) {
			wp_schedule_event( time(), 'twicedaily', 'e2w_cron' );
		}
	}


	/**
	 * Remove cron on plugin disable.
	 *
	 * @return void
	 */
	public function unschedule_cron() {
		// when the last event was scheduled.
		$timestamp = wp_next_scheduled( '' );
		// unschedule previous event if any.
		if ( $timestamp ) {
			wp_unschedule_event( $timestamp, '' );
		}
	}


	/**
	 * Get info from remote and DB update.
	 *
	 * @return void
	 */
	public function get_remote_data() {
		$license_key = get_option( 'wubtitle_license_key' );

		$response = wp_remote_post(
			WUBTITLE_ENDPOINT . 'subscription/info',
			array(
				'method'  => 'POST',
				'headers' => array(
					'Content-Type' => 'application/json; charset=utf-8',
					'licenseKey'   => $license_key,
				),
			)
		);

		$code_response = wp_remote_retrieve_response_code( $response );
		if ( 201 === $code_response ) {
			$body_response      = json_decode( wp_remote_retrieve_body( $response ) );
			$plans              = $body_response->data->plans;
			$wubtitle_plans     = array();
			$wubtitle_plan_rank = '';
			$total_jobs         = 0;
			$total_seconds      = 0;
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
				if ( $body_response->data->currentPlan === $plan->id ) {
					$wubtitle_plan_rank = $plan->rank;
					// phpcs:disable 
					// warning camel case
					$total_jobs         = $plan->totalJobs;
					$total_seconds      = $plan->totalSeconds;
					// phpcs:enable
				}
			}
			update_option( 'wubtitle_plan', $body_response->data->currentPlan, false );
			update_option( 'wubtitle_plan_rank', $body_response->data->currentPlan, false );
			update_option( 'wubtitle_all_plans', $wubtitle_plans, false );
			$is_free_plan = 0 === $wubtitle_plan_rank;
			update_option( 'wubtitle_free', $is_free_plan, false );
			update_option( 'wubtitle_expiration_date', $body_response->data->expirationDate, false );
			update_option( 'wubtitle_is_first_month', $body_response->data->isFirstMonth, false );
			update_option( 'wubtitle_is_canceling', $body_response->data->isCanceling, false );
			update_option( 'wubtitle_total_jobs', $total_jobs, false );
			update_option( 'wubtitle_total_seconds', $total_seconds, false );
			update_option( 'wubtitle_jobs_done', $body_response->data->consumedJobs, false );
			update_option( 'wubtitle_seconds_done', $body_response->data->consumedSeconds, false );
		}
	}
}
