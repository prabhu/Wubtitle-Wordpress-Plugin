<?php
/**
 * This file describes handle operation on subtitle.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Dashboard;

/**
 * This class handle subtitles.
 */
class PaymentTemplate {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'wp_ajax_payment_template', array( $this, 'load_template' ) );
	}

	/**
	 * Include template.
	 */
	public function load_template() {
		include 'Templates/payment_template.php';
		wp_die();
	}

	/**
	 * Stripe JS
	 */
	public function load_scripts() {
		wp_enqueue_script( 'stripe_js', 'https://js.stripe.com/v3/', '', '0.1.0', true );
	}

}
