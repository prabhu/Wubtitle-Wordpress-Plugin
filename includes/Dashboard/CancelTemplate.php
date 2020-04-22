<?php
/**
 * This file describes handle Templates.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Dashboard;

/**
 * This class handle Cancel Templates .
 */
class CancelTemplate {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'wp_ajax_cancel_template', array( $this, 'load_cancel_template' ) );
	}

	/**
	 * Include il template che viene caricato nella finestra popup per la cancellazione della licenza al click del bottone "cancella".
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
