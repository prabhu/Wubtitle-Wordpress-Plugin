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
		add_action( 'init', array( $this, 'load_template_payment_template' ) );
	}



	/**
	 * Include template.
	 */
	public function load_template() {
		include 'Templates/payment_template.php';
		wp_die();
	}

	/**
	 * Include template.
	 */
	public function load_template_payment_template() {
		$url_path = trim( wp_parse_url( add_query_arg( array() ), PHP_URL_PATH ), '/' );
		if ( 'ear2words/payment-success' === $url_path ) {
			include 'Templates/success_template.php';
			wp_die();
		}
	}


	/**
	 * Include template.
	 */
	public function create_url_success_payment() {
		add_rewrite_rule( '^ear2words/success/?', 'index.php/pagamento-completato', 'top' );
	}

	/**
	 * Stripe JS
	 */
	public function load_scripts() {
		wp_enqueue_script( 'stripe_js', 'https://js.stripe.com/v3/', '', '0.1.0', true );
	}

}
