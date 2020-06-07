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
	 */
	public function run() {
		add_action( 'wp_ajax_cancel_template', array( $this, 'load_cancel_template' ) );
	}

	/**
	 * Popup window template displayed on license "cancel" button click.
	 */
	public function load_cancel_template() {
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			include 'Templates/cancel_template.php';
			$html = ob_get_clean();
			wp_send_json_success( $html );
			wp_die();
		}
		$html = 'Error';
		wp_send_json_error( $html );
		wp_die();
	}

}
