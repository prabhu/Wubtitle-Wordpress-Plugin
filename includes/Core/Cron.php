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
	 * Init class actions
	 */
	public function run() {
		add_filter( 'cron_schedules', 'add_cron_interval' );
	}

	/**
	 * Handle the cron.
	 *
	 * @param int $schedules parametro di cron_schedules.
	 */
	public function add_cron_interval( $schedules ) {
		return $schedules;
	}

}
