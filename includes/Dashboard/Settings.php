<?php
/**
 * This file implements Settings.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Wubtitle\Dashboard
 */

namespace Wubtitle\Dashboard;

use Wubtitle\Loader;

/**
 * This class describes Settings.
 */
class Settings {
	/**
	 * Init class actions
	 *
	 * @return void
	 */
	public function run() {
		add_action( 'admin_menu', array( $this, 'create_settings_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
		add_action( 'admin_init', array( $this, 'init_settings_field' ) );
		add_action( 'update_option_wubtitle_license_key', array( $this, 'check_license' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'e2w_settings_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wubtitle_settings_style' ) );
		add_action( 'admin_notices', array( $this, 'check_notice_stripe' ) );
	}

	/**
	 * Create a new dashboard menu item.
	 *
	 * @return void
	 */
	public function create_settings_menu() {
		// TODO: Cambiare $icon_url e $position (attualmente subito dopo "Impostazioni") quando verranno date indicazioni UX.
		add_menu_page( __( 'Wubtitle Settings', 'wubtitle' ), __( 'Wubtitle', 'wubtitle' ), 'manage_options', 'wubtitle_settings', array( $this, 'render_settings_page' ), 'dashicons-format-status', 81 );
	}

	/**
	 *  Enqueue settings style.
	 *
	 * @return void
	 */
	public function wubtitle_settings_style() {
		wp_enqueue_style( 'wubtitle_settings_style', WUBTITLE_URL . 'src/css/settingsStyle.css', array(), true );
	}

	/**
	 * Renders the setting page.
	 *
	 * @return void
	 */
	public function render_settings_page() {
		Loader::get( 'cron' )->get_remote_data();
		$plans        = array(
			'plan_0'              => __( 'Free Plan', 'wubtitle' ),
			'plan_HBBbNjLjVk3w4w' => __( 'Standard Plan', 'wubtitle' ),
			'plan_HBBS5I9usXvwQR' => __( 'Elite Plan', 'wubtitle' ),
		);
		$plan_saved   = get_option( 'wubtitle_plan' );
		$current_plan = array_key_exists( $plan_saved, $plans ) ? $plans[ $plan_saved ] : '';
		$seconds_max  = get_option( 'wubtitle_total_seconds' );
		$jobs_max     = get_option( 'wubtitle_total_jobs' );
		$seconds      = get_option( 'wubtitle_seconds_done' );
		if ( ! $seconds ) {
			$seconds = 0; }
		$jobs                     = empty( get_option( 'wubtitle_jobs_done' ) ) ? 0 : get_option( 'wubtitle_jobs_done' );
		$wubtitle_expiration_date = get_option( 'wubtitle_expiration_date' );
		$friendly_expiration_date = date_i18n( get_option( 'date_format' ), $wubtitle_expiration_date );
		$wubtitle_is_canceling    = get_option( 'wubtitle_is_canceling' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
			<div class="form-header">
				<img class="logo" src="<?php echo esc_url( WUBTITLE_URL . 'src/img/logo.svg' ); ?>">
				<?php
				settings_errors();
				submit_button();
				?>
			</div>
			<div class="postbox">
				<h2 class="hndle ui-sortable-handle e2w-title" ><span><?php esc_html_e( 'Licensing', 'wubtitle' ); ?></span></h2>
				<div class="inside">
					<div class="plan-state">
						<?php echo esc_html( $current_plan ); ?>
					</div>
					<div class="plan-renewal">
						<?php
						$this->render_plan_renewal( $plan_saved, $wubtitle_is_canceling, $friendly_expiration_date );
						?>
					</div>
					<p style="font-weight:400">
					<?php
					esc_html_e( 'Generated video subtitles: ', 'wubtitle' );
					echo esc_html( $jobs . '/' . $jobs_max );
					?>
					</p>
					<p style="font-weight:400">
					<?php
					esc_html_e( 'Video time spent: ', 'wubtitle' );
					echo esc_html( date_i18n( 'H:i:s', $seconds ) . '/' . date_i18n( 'H:i:s', $seconds_max ) );
					esc_html_e( ' hours', 'wubtitle' );
					?>
					</p>
						<?php
						settings_fields( 'wubtitle_settings' );
						do_settings_sections( 'wubtitle-settings' );
						?>
					<div class="plan-update">
						<?php
						$this->render_plan_update( $wubtitle_is_canceling );
						?>
					</div>
				</div>
			</div>
		</div>
		</form>
		<?php
	}

	/**
	 * Checks GET parameters and relative notice to the user.
	 *
	 * @return void
	 */
	public function check_notice_stripe() {
		$message = false;
		// phpcs:disable
		if ( empty( $_GET['notices-code'] ) || isset( $_GET['settings-updated'] ) ) {
			return;
		}
		switch ( $_GET['notices-code'] ) {
			case 'payment':
				$message = __( 'Payment successful', 'wubtitle' );
				break;
			case 'update':
				$message = __( 'Payment information updated', 'wubtitle' );
				break;
			case 'reset':
				$message = __( 'License key sent, check your email!', 'wubtitle' );
				break;
			case 'delete':
				$message = __( 'Unsubscription successful', 'wubtitle' );
				break;
			case 'reactivate':
				$message = __( 'Reactivation of the plan successful', 'wubtitle' );
				break;
		}
		if ( ! $message ) {
			return;
		}
		?>
		 <div class="notice notice-success is-dismissible">
			 <p> <?php echo esc_html( $message ); ?></p>
		 </div>
		<?php
		// phpcs:enable
	}


	/**
	 * Render name plan in render settengs method
	 *
	 * @param string  $plan stripe plan code.
	 * @param boolean $cancelling state of user plan.
	 * @param string  $date renewal date.
	 * @return void
	 */
	private function render_plan_renewal( $plan, $cancelling, $date ) {
		if ( 'plan_0' !== $plan && ! $cancelling ) {
			echo esc_html( __( 'Automatic renewal: ', 'wubtitle' ) . $date );
		} elseif ( 'plan_0' !== $plan && $cancelling ) {
			echo esc_html( __( 'You requested the subscription cancellation. Your plan will be valid until  ', 'wubtitle' ) . $date );
		}
	}

	/**
	 * Render name plan in render settengs method
	 *
	 * @param boolean $cancelling state of user plan.
	 * @return void
	 */
	private function render_plan_update( $cancelling ) {
		if ( ! $cancelling && ! get_option( 'wubtitle_free' ) ) {
			?>
			<a href="#" id="cancel-license-button" style="text-decoration: underline; color:red; margin-right:10px;" >
				<?php esc_html_e( 'Unsubscribe', 'wubtitle' ); ?>
			</a>
			<a href="#" id="update-plan-button" style="text-decoration: underline" >
				<?php esc_html_e( 'Update email or payment detail', 'wubtitle' ); ?>
			</a>
			<a href="#" id="modify-plan" style="text-decoration: underline; margin-left: 10px;" >
				<?php esc_html_e( 'Modify plan', 'wubtitle' ); ?>
			</a>
			<?php
		} elseif ( $cancelling ) {
			?>
			<a href="#" id="reactivate-plan-button" style="text-decoration: underline;" >
				<?php esc_html_e( 'Reactivate plan', 'wubtitle' ); ?>
			</a>
			<?php
		}
	}


	/**
	 * Adds a new setting.
	 *
	 * @return void
	 */
	public function init_settings() {
		register_setting(
			'wubtitle_settings',
			'wubtitle_license_key',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
	}

	/**
	 * Checks license
	 *
	 * @return void
	 */
	public function check_license() {
		$submitted_license = get_option( 'wubtitle_license_key' );

		$validation = $this->remote_request( $submitted_license );

		if ( $validation['error'] && ! $validation['verified'] ) {
			$error_messages = array(
				'EXPIRED' => __( 'Unable to update. Expired product license.', 'wubtitle' ),
				'INVALID' => __( 'Unable to update. Invalid product license.', 'wubtitle' ),
				'4xx'     => __( 'An error occurred while updating licence. Please try again in a few minutes.', 'wubtitle' ),
				'5xx'     => __( 'Could not contact the server.', 'wubtitle' ),
				'xxx'     => __( 'An error occurred.', 'wubtitle' ),
			);

			add_settings_error(
				'wubtitle_license_key',
				esc_attr( 'invalid_license' ),
				$error_messages[ $validation['error'] ],
				'error'
			);
			remove_action( 'update_option_wubtitle_license_key', array( $this, 'check_license' ) );
			update_option( 'wubtitle_license_key', null );
		} elseif ( $validation['verified'] ) {
			add_settings_error(
				'wubtitle_license_key',
				esc_attr( 'invalid_license' ),
				__( 'Valid product license. Subscription plan updated.', 'wubtitle' ),
				'success'
			);
		}
	}

	/**
	 * Remote licence key check request.
	 *
	 * @param string $license_key input license key.
	 *
	 * @return array<string>
	 */
	private function remote_request( $license_key ) {
		$headers = array(
			'Content-Type' => 'application/json; charset=utf-8',
		);

		$body = array(
			'data' => array(
				'licenseKey' => $license_key,
			),
		);

		$response = wp_remote_post(
			ENDPOINT . 'license/check',
			array(
				'method'  => 'POST',
				'headers' => $headers,
				'body'    => wp_json_encode( $body ),
			)
		);

		$retrieved = json_decode( wp_remote_retrieve_body( $response ), true );
		$status    = wp_remote_retrieve_response_code( $response );

		$validation = array();

		$validation['verified'] = $retrieved['data']['verified'];

		// xxx handles a generic error, 4xx and 5xx handles all 400 or 500 errors.
		$validation['error'] = 'xxx';
		if ( 200 === $status && ! $validation['verified'] ) {
			$validation['error'] = $retrieved['data']['errorType'];
		} elseif ( 500 <= $status && 600 > $status ) {
			$validation['error'] = '5xx';
		} elseif ( 400 <= $status && 500 > $status ) {
			$validation['error'] = '4xx';
		}

		return $validation;
	}

	/**
	 * This function handles the setup of wubtitle_settings fields.
	 *
	 * @return void
	 */
	public function init_settings_field() {
		add_settings_section( 'wubtitle-main-settings', '', function(){}, 'wubtitle-settings' );
		if ( 'plan_HBBS5I9usXvwQR' !== get_option( 'wubtitle_plan' ) ) {
			add_settings_field(
				'buy-license-button',
				__( 'Unlock more features!', 'wubtitle' ),
				array( $this, 'upgrade_button' ),
				'wubtitle-settings',
				'wubtitle-main-settings',
				array(
					'name'  => __( 'Upgrade', 'wubtitle' ),
					'class' => 'upgrade-button',
				)
			);
		}
		add_settings_field(
			'wubtitle-license-key',
			__( 'License Number', 'wubtitle' ),
			array( $this, 'input_field' ),
			'wubtitle-settings',
			'wubtitle-main-settings',
			array(
				'type'        => 'text',
				'name'        => 'wubtitle_license_key',
				'placeholder' => __( 'License key', 'wubtitle' ),
				'class'       => 'input-license-key',
				'description' => __( 'Please enter the license key you received after successful checkout', 'wubtitle' ),
			)
		);
	}

	/**
	 * Create an input component needed in the form.
	 *
	 * @param array<string> $args input parameters.
	 *
	 * @return void
	 */
	public function input_field( $args ) {
		$option = '';
		if ( ! get_option( 'wubtitle_free' ) ) {
			$option = get_option( $args['name'], '' );
		}
		?>
		<input class="regular-text" type="<?php echo esc_attr( $args['type'] ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>" value="<?php echo esc_attr( $option ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>">
		<?php
		if ( ! get_option( 'wubtitle_free' ) && empty( get_option( 'wubtitle_license_key' ) ) ) :
			?>
			<a href="#" id="reset-license" style="text-decoration: underline" >
				<?php esc_html_e( 'Reset license key', 'wubtitle' ); ?>
			</a>
			<?php
		endif;
		?>
		<p class="description"><?php echo esc_html( $args['description'] ); ?></p>
		<?php
	}
	/**
	 * Upgrade button.
	 *
	 * @param array<string> $args input parameters.
	 *
	 * @return void
	 */
	public function upgrade_button( $args ) {
		?>
		<button id="buy-license-button" class="button-primary" >
			<?php echo esc_html( $args['name'] ); ?>
		</button>
		<p style="display:inline; margin-left:4px;"> <?php esc_html_e( 'now!', 'wubtitle' ); ?> </p>
		<?php
	}

	/**
	 * Enqueue scripts.
	 *
	 * @param string $hook value admin_enqueue_scripts hook.
	 *
	 * @return void
	 */
	public function e2w_settings_scripts( $hook ) {
		if ( 'toplevel_page_wubtitle_settings' === $hook ) {
			wp_enqueue_script( 'wp-util' );
			wp_enqueue_script( 'settings_scripts', WUBTITLE_URL . '/src/payment/settings_script.js', array( 'wp-util' ), WUBTITLE_VER, true );
			wp_localize_script(
				'settings_scripts',
				'settings_object',
				array(
					'ajax_url'  => admin_url( 'admin-ajax.php' ),
					'ajaxnonce' => wp_create_nonce( 'itr_ajax_nonce' ),
				)
			);
		}
	}
}
