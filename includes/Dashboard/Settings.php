<?php
/**
 * This file implements Settings.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Dashboard;

use WP_Error;

/**
 * This class describes Settings.
 */
class Settings {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'admin_menu', array( $this, 'create_settings_menu' ) );
		add_action( 'admin_init', array( $this, 'init_settings' ) );
		add_action( 'admin_init', array( $this, 'init_settings_field' ) );
		add_action( 'update_option_ear2words_license_key', array( $this, 'check_license' ) );
	}

	/**
	 * Crea un nuova voce nel menu della dashbord
	 */
	public function create_settings_menu() {
		// TODO: Cambiare $icon_url e $position (attualmente subito dopo "Impostazioni") quando verranno date indicazioni UX.
		add_menu_page( __( 'Ear2words Settings', 'ear2words' ), __( 'Ear2words', 'ear2words' ), 'manage_options', 'ear2words_settings', array( $this, 'render_settings_page' ), 'dashicons-format-status', 81 );
	}

	/**
	 * Crea la pagina dei settings
	 */
	public function render_settings_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<?php settings_errors(); ?>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'ear2words_settings' );
				do_settings_sections( 'ear2words-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Aggiunge una nuova impostazione
	 */
	public function init_settings() {
		register_setting(
			'ear2words_settings',
			'ear2words_license_key',
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
	}

	/**
	 * Check license
	 */
	public function check_license() {
		$submitted_license = get_option( 'ear2words_license_key' );

		$valid_license = $this->remote_request( $submitted_license );

		if ( ! $valid_license ) {
			add_settings_error(
				'ear2words_license_key',
				esc_attr( 'invalid_license' ),
				__( 'Invalid license key', 'ear2words' ),
				'error'
			);
			remove_action( 'update_option_ear2words_license_key', array( $this, 'check_license' ) );
			update_option( 'ear2words_license_key', null );
		}
	}

	/**
	 * Chiamata ad endpoint remoto per check license key.
	 *
	 * @param string $license_key license key dell'input.
	 */
	public function remote_request( $license_key ) {

		// TODO: Aspettare che Simone completi l'issue per la creazione dell'endpoint.
		$endpoint = 'http://sites.local/wp-api-test/wp-json/wp/v2/posts';

		$headers = array(
			'Content-Type' => 'application/json; charset=utf-8',
		);

		$body = array(
			'data' => array(
				'license_key' => $license_key,
			),
		);

		$request = wp_remote_post(
			$endpoint,
			array(
				'method'  => 'POST',
				'headers' => wp_json_encode( $headers ),
				'body'    => wp_json_encode( $body ),
			)
		);

		$valid_license = false;

		if ( 200 === $request['body']['data']['status'] ) {
			$valid_license = true;
		}

		return $valid_license;
	}


	/**
	 * Aggiunge un nuovo campo all'impostazione precedentemente creata
	 */
	public function init_settings_field() {
		add_settings_section( 'ear2words-main-settings', __( 'License settings', 'ear2words' ), null, 'ear2words-settings' );
		add_settings_field(
			'ear2words-license-key',
			__( 'License key', 'ear2words' ),
			array( $this, 'input_field' ),
			'ear2words-settings',
			'ear2words-main-settings',
			array(
				'type'        => 'text',
				'name'        => 'ear2words_license_key',
				'placeholder' => __( 'License key', 'ear2words' ),
			)
		);
	}

	/**
	 * Crea un componente input da richiamare nel form
	 *
	 * @param array $args Parametri dell'input.
	 */
	public function input_field( $args ) {
		$option = get_option( $args['name'], '' );
		?>
		<input type="<?php echo esc_attr( $args['type'] ); ?>" name="<?php echo esc_attr( $args['name'] ); ?>" value="<?php echo esc_attr( $option ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>">
		<?php
	}
}
