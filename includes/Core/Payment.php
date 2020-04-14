<?php
/**
 * This file describes handle payment.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Core
 */

namespace Ear2Words\Core;

/**
 * This class handle payment.
 */
class Payment {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'admin_menu', array( $this, 'create_payment_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_stripe_scripts' ) );
	}

	/**
	 * Crea un nuova voce nel menu della dashbord
	 */
	public function create_payment_menu() {
		add_menu_page( __( 'Payment', 'ear2words' ), __( 'Payment', 'ear2words' ), 'manage_options', 'ear2words_payment', array( $this, 'render_payment_page' ), 'dashicons-format-status', 82 );
	}

	/**
	 * Crea un nuova voce nel menu della dashbord
	 */
	public function enqueue_stripe_scripts() {
		wp_enqueue_style( 'stripe_form_css', plugins_url( '../../src/css/payment.css', __FILE__ ), null, true );
		wp_enqueue_script( 'stripe_form_js', plugins_url( '../../build/index.js', __FILE__ ), array( 'wp-compose', 'wp-data', 'wp-element', 'wp-hooks', 'wp-api-fetch', 'wp-components', 'wp-block-editor', 'wp-edit-post', 'wp-i18n' ), 'stripe_form', false );
	}

	/**
	 * Crea la pagina dei settings
	 */
	public function render_payment_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php settings_errors(); ?>
			<div id="payment-form"><!-- Entry point JS --></div>
		</div>
		<?php
	}


	/**
	 * Handle payment.
	 */
	public function handle_payment() {
		// TODO: payment.
	}



}
