<?php
/**
 * This file implements.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

/**
 * This class describes.
 */
class ApiCancelSubscription {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'wp_ajax_cancel_subscription', array( $this, 'cancel_subscription' ) );
	}

	/**
	 * Crea.
	 */
	public function cancel_subscription() {
		// TODO: ottenere license key dal db.

		// TODO: creare header per la chiamata api con licenza.

		// TODO: mandare richiesta POST.

		// TODO: Fare check response.
	}

}
