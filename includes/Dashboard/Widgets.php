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
 * This class handle Payment Templates .
 */
class Widgets {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'wp_dashboard_setup', array( $this, 'add_e2w_dashboard_widget' ) );
	}

	/**
	 * Include il template.
	 */
	public function add_e2w_dashboard_widget() {
		wp_add_dashboard_widget( 'e2w_dashboard_widget', __( 'E2W Widget', 'ear2words' ), array( $this, 'e2w_dashboard_widget' ) );
	}

	/**
	 * Include il template.
	 */
	public function e2w_dashboard_widget() {
		?>
		<h2>E2W widget</h2>
		<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, maxime.</p>
		<?php
	}

}
