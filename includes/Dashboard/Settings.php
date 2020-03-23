<?php
/**
 * This file implements Settings.
 *
 * @author     Nicola Palermo
 * @since      2020
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Dashboard;

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
	}

	/**
	 * Crea un nuova voce nel menu della dashbord
	 */
	public function create_settings_menu() {
		// TODO: Cambiare $icon_url e $position (attualmente subito dopo "Impostazioni") quando verranno date indicazioni UX.
		add_menu_page( 'Ear2words Settings', 'Ear2words', 'manage_options', 'ear2words_settings', array( $this, 'render_settings_page' ), 'dashicons-format-status', 81 );
	}

	/**
	 * Crea la pagina dei settings
	 */
	public function render_settings_page() {
		ob_start();
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'ear2words_settings' );
				do_settings_sections( 'ear2words-settings' );
				submit_button();
				?>
			</form>
		</div>
		<?php
		//phpcs:disable
		echo ob_get_clean();
		//phpcs:enable
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
	 * Aggiunge un nuovo campo all'impostazione precedentemente creata
	 */
	public function init_settings_field() {
		add_settings_section( 'ear2words-main-settings', 'Impostazioni licenza', null, 'ear2words-settings' );
		add_settings_field(
			'ear2words-license-key',
			'License Key',
			array( $this, 'input_field' ),
			'ear2words-settings',
			'ear2words-main-settings',
			array(
				'type'        => 'text',
				'name'        => 'ear2words_license_key',
				'placeholder' => 'License Key',
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
