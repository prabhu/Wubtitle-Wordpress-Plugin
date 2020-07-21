<?php
/**
 * This file describes how to include the cancel template.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Wubtitle\Dashboard
 */

namespace Wubtitle\Dashboard;

/**
 * This class handle the cancel template.
 */
class CancelPage {
	/**
	 * Init class actions
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'wp_ajax_cancel_template', array( $this, 'load_cancel_template' ) );
	}

	/**
	 * Popup window template displayed on license "cancel" button click.
	 *
	 * @return void
	 */
	public function load_cancel_template() {
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			wp_enqueue_style( 'wubtitle_font_family', 'https://fonts.googleapis.com/css?family=Days+One|Open+Sans&display=swap', array(), WUBTITLE_VER );
			wp_enqueue_style( 'wubtitle_style_template', WUBTITLE_URL . 'assets/css/payment_template.css', array(), WUBTITLE_VER );
			wp_enqueue_script( 'wubtitle_stripe_script', 'https://js.stripe.com/v3/', array(), WUBTITLE_VER, true );
			wp_enqueue_script( 'wubtitle_payment_script', WUBTITLE_URL . 'assets/payment/payment_template.js', array(), WUBTITLE_VER, true );
			wp_localize_script(
				'wubtitle_payment_script',
				'WP_GLOBALS',
				array(
					'adminAjax'   => admin_url( 'admin-ajax.php' ),
					'nonce'       => wp_create_nonce( 'itr_ajax_nonce' ),
					'wubtitleEnv' => defined( 'WP_WUBTITLE_ENV' ) ? esc_html( WP_WUBTITLE_ENV ) : '',
				)
			);
			include 'Templates/cancel_template.php';
			$html = ob_get_clean();
			wp_send_json_success( $html );
		}
		$html = 'Error';
		wp_send_json_error( $html );
	}

}
