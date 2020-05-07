<?php
/**
 * This file handles the dashboard widgets
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Dashboard;

use Ear2Words\Loader;

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
		Loader::get( 'cron' )->get_remote_data();
		?>
		<h2> <?php echo esc_html( __( 'Your plan: ', 'ear2words' ) . $this->current_plan() ); ?></h2>
		<p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Tempora, maxime.</p>
		<?php
	}

	/**
	 * Genera.
	 */
	private function current_plan() {
		$plans        = array(
			'plan_0'              => __( 'Free Plan', 'ear2words' ),
			'plan_HBBbNjLjVk3w4w' => __( 'Standard Plan', 'ear2words' ),
			'plan_HBBS5I9usXvwQR' => __( 'Elite Plan', 'ear2words' ),
		);
		$plan_saved   = get_option( 'ear2words_plan' );
		$current_plan = array_key_exists( $plan_saved, $plans ) ? $plans[ $plan_saved ] : '';
		return esc_html( $current_plan );
	}

}
