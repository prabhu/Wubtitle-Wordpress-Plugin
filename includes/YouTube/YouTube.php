<?php
/**
 * This file describes handle Youtube functions.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\YouTube
 */

namespace Ear2Words\YouTube;

/**
 * This class handle YouTube functions.
 */
class YouTube {
	/**
	 * Init class actions.
	 */
	public function run() {
		add_action( 'admin_menu', array( $this, 'create_settings_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'e2w_youtube_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'ear2words_youtube_style' ) );
	}

	/**
	 * Crea un nuova voce nel menu della dashbord
	 */
	public function create_settings_menu() {
		add_menu_page( __( 'YouTube', 'ear2words' ), __( 'Youtube', 'ear2words' ), 'manage_options', 'ear2words_youtube', array( $this, 'render_youtube_page' ), 'dashicons-format-status', 82 );
	}



	/**
	 * Crea la pagina dei settings
	 */
	public function render_youtube_page() {
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>			
			<div class="postbox">
				<h2 class="hndle ui-sortable-handle e2w-title" ><span><?php esc_html_e( 'Youtube', 'ear2words' ); ?></span></h2>
				<div class="inside">
					<input type="text" id="youtube-url">
					<button id="youtube-iframe-button" class="button-primary" >
						<?php echo esc_html( 'Carica Video' ); ?>
					</button>
					<hr>
					<div id="video-embed">
					</div>
					<button id="youtube-subtitles-button" class="button-primary" >
						<?php echo esc_html( 'Get subtitles' ); ?>
					</button>
				</div>
			</div>
		</div>
		</form>
		<?php
	}


	/**
	 *  Faccio l'enqueue dello style per i settings.
	 */
	public function ear2words_youtube_style() {
		wp_enqueue_style( 'ear2words_youtube_style', EAR2WORDS_URL . 'src/youtube/youtube_style.css', null, true );
	}


	/**
	 * Includo gli script.
	 *
	 * @param string $hook valore presente nell'hook admin_enqueue_scripts.
	 */
	public function e2w_youtube_scripts( $hook ) {
		if ( 'toplevel_page_ear2words_youtube' === $hook ) {
			wp_enqueue_script( 'youtube_scripts', EAR2WORDS_URL . '/src/youtube/youtube_script.js', null, EAR2WORDS_VER, true );
		}
	}

}
