<?php
/**
 * This file handles the dashboard widgets
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Dashboard;

/**
 * This class handles the necessary methods to handle the widgets in the dashboard.
 */
class Widgets {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'wp_dashboard_setup', array( $this, 'add_e2w_dashboard_widget' ) );
	}

	/**
	 * Aggiunge un nuovo widget.
	 */
	public function add_e2w_dashboard_widget() {
		wp_add_dashboard_widget( 'e2w_dashboard_widget', __( 'E2W Widget', 'ear2words' ), array( $this, 'e2w_dashboard_widget' ) );
	}

	/**
	 * Genera il template del widget.
	 */
	public function e2w_dashboard_widget() {
		?>
		<h2>E2W widget</h2>
		<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, maxime.</p>
		<?php
	}

}
