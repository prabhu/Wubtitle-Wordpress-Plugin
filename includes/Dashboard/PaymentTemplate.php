<?php
/**
 * This file handles the payment template.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Wubtitle\Dashboard
 */

namespace Wubtitle\Dashboard;

/**
 * This class handles Payment Templates.
 */
class PaymentTemplate {
	/**
	 * Init class actions
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'wp_ajax_payment_template', array( $this, 'load_payment_template' ) );
		add_action( 'wp_ajax_update_template', array( $this, 'load_update_template' ) );
		add_action( 'wp_ajax_change_plan_template', array( $this, 'change_plan_template' ) );
	}


	/**
	 * Popup window template displayed on license buying button click.
	 *
	 * @return void
	 */
	public function change_plan_template() {
		$plan_rank        = get_option( 'wubtitle_plan_rank' );
		$wanted_plan_rank = get_option( 'wubtitle_wanted_plan_rank' );
		$includes_file    = 'Templates/downgrade_plan_template.php';
		if ( $wanted_plan_rank > $plan_rank ) {
			$includes_file = 'Templates/upgrade_plan_template.php';
		}
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			include $includes_file;
			$html = ob_get_clean();
			wp_send_json_success( $html );
		}
		$html = 'Error';
		wp_send_json_error( $html );
	}
	/**
	 * Load the payment template
	 *
	 * @return void
	 */
	public function load_payment_template() {
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			include 'Templates/payment_template.php';
			$html = ob_get_clean();
			wp_send_json_success( $html );
		}
		$html = 'Error';
		wp_send_json_error( $html );
	}
	/**
	 * Load the update template.
	 *
	 * @return void
	 */
	public function load_update_template() {
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			include 'Templates/update_template.php';
			$html = ob_get_clean();
			wp_send_json_success( $html );
		}
		$html = 'Error';
		wp_send_json_error( $html );
	}

}
