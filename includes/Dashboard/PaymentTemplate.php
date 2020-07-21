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
		add_action( 'wp_ajax_custom_form_template', array( $this, 'load_custom_form' ) );
	}


	/**
	 * Popup window template displayed on license buying button click.
	 *
	 * @return void
	 */
	public function change_plan_template() {
		$map_plans     = array(
			'plan_0'              => 0,
			'plan_HBBbNjLjVk3w4w' => 1,
			'plan_HBBS5I9usXvwQR' => 2,
		);
		$plan          = get_option( 'wubtitle_plan' );
		$current_plan  = $map_plans[ $plan ];
		$plan          = get_option( 'wubtitle_wanted_plan' );
		$wanted_plan   = $map_plans[ $plan ];
		$includes_file = 'Templates/downgrade_plan_template.php';
		if ( $wanted_plan > $current_plan ) {
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
		$plan_rank = get_option( 'wubtitle_plan_rank' );
		$plans     = get_option( 'wubtitle_all_plans' );
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			$current_plan = $plans[ $plan_rank ];
			wp_enqueue_script( 'wubtitle_stripe_form', WUBTITLE_URL . 'build_form/index.js', array( 'wp-element', 'wp-i18n' ), WUBTITLE_VER, true );
			wp_set_script_translations( 'wubtitle_stripe_form', 'wubtitle', WUBTITLE_DIR . 'languages' );
			wp_localize_script(
				'wubtitle_stripe_form',
				'WP_GLOBALS',
				array(
					'pricePlan'   => $current_plan['price'],
					'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
					'ajaxNonce'   => wp_create_nonce( 'itr_ajax_nonce' ),
					'wubtitleEnv' => defined( 'WP_WUBTITLE_ENV' ) ? esc_html( WP_WUBTITLE_ENV ) : '',
				)
			);
			wp_enqueue_style( 'wubtitle_style_form', WUBTITLE_URL . 'assets/css/stripeStyle.css', array(), WUBTITLE_VER );
			wp_enqueue_style( 'wubtitle_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css', array(), WUBTITLE_VER );
			include 'Templates/update_template.php';
			$html = ob_get_clean();
			wp_send_json_success( $html );
		}
		$html = 'Error';
		wp_send_json_error( $html );
	}
	/**
	 * Load stripe custom form template.
	 *
	 * @return void
	 */
	public function load_custom_form() {
		if ( ! isset( $_POST['_ajax_nonce'], $_POST['planRank'] ) ) {
			wp_send_json_error( __( 'An error occurred. Please try again in a few minutes.', 'wubtitle' ) );
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		check_ajax_referer( 'itr_ajax_nonce', $nonce );
		if ( current_user_can( 'manage_options' ) ) {
			ob_start();
			$plan_rank   = sanitize_text_field( wp_unslash( $_POST['planRank'] ) );
			$plans       = get_option( 'wubtitle_all_plans' );
			$wanted_plan = $plans[ $plan_rank ];
			wp_enqueue_script( 'wubtitle_stripe_form', WUBTITLE_URL . 'build_form/index.js', array( 'wp-element', 'wp-i18n' ), WUBTITLE_VER, true );
			wp_localize_script(
				'wubtitle_stripe_form',
				'WP_GLOBALS',
				array(
					'pricePlan'   => $wanted_plan['price'],
					'planId'      => $wanted_plan['stripe_code'],
					'namePlan'    => $wanted_plan['name'],
					'ajaxUrl'     => admin_url( 'admin-ajax.php' ),
					'ajaxNonce'   => wp_create_nonce( 'itr_ajax_nonce' ),
					'wubtitleEnv' => defined( 'WP_WUBTITLE_ENV' ) ? esc_html( WP_WUBTITLE_ENV ) : '',
				)
			);
			wp_enqueue_style( 'wubtitle_style_form', WUBTITLE_URL . 'assets/css/stripeStyle.css', array(), WUBTITLE_VER );
			wp_enqueue_style( 'wubtitle_font_awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.0/css/font-awesome.min.css', array(), WUBTITLE_VER );
			include 'Templates/custom_form.php';
			$html = ob_get_clean();
			wp_send_json_success( $html );
		}
		$html = 'Error';
		wp_send_json_error( $html );
	}
}
