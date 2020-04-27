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
		add_action( 'e2w_cron', array( $this, 'get_remote_subscription_info' ) );
		register_deactivation_hook( EAR2WORDS_FILE_URL, array( $this, 'cron_deactivate' ) );
		if ( ! wp_next_scheduled( 'e2w_cron' ) ) {
			wp_schedule_event( time(), 'five_seconds', 'e2w_cron' );
		}
		add_filter( 'cron_schedules', array( $this, 'add_cron_interval' ) );
	}

	/**
	 * Cron.
	 *
	 * @param array $schedules parametro.
	 */
	public function add_cron_interval( $schedules ) {
		$schedules['five_seconds'] = array(
			'interval' => 900,
			'display'  => esc_html__( 'Every Five Seconds', 'ear2words' ),
		);
		return $schedules;
	}

	/**
	 * Disattiva cron.
	 */
	public function cron_deactivate() {
		// when the last event was scheduled.
		$timestamp = wp_next_scheduled( 'get_subscription_info_cron_job' );
		// unschedule previous event if any.
		wp_unschedule_event( $timestamp, 'get_subscription_info_cron_job' );
	}

	/**
	 * Fetch info.
	 */
	public function get_remote_subscription_info() {
		update_option( 'test_ettore', time() );
	}
}
