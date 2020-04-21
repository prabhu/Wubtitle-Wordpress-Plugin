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
		add_action( 'hook', array( $this, 'callback' ) );
	}

	/**
	 * Crea.
	 */
	public function callback() {
		// TODO: callback.
	}

}
