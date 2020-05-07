<?php
/**
 * This file describes handle Youtube functions.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\YouTube
 */

namespace Ear2Words\YouTube;

// phpcs:disable
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
		add_action( 'wp_ajax_get_info_yt', array( $this, 'get_info' ) );
	}

	public function get_info() {
		$id_video = $_POST['id'];

		$get_info_url = "https://www.youtube.com/get_video_info?video_id=$id_video";

		$file_info = array();
		$file      = file_get_contents( $get_info_url );
		parse_str( $file, $file_info );

		$url = json_decode( $file_info['player_response'] )->captions->playerCaptionsTracklistRenderer->captionTracks[0]->baseUrl;

		wp_send_json_success( $url . '&fmt=json3&xorb=2&xobt=3&xovt=3' );
		wp_die();
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
					<button id="youtube-button" class="button-primary" >
						<?php echo esc_html( 'Get Subtitle' ); ?>
					</button>
					<div id="video-embed">
					</div>
					<hr>
					<div id="text">
					</div>
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
			wp_enqueue_script( 'wp-util' );
			wp_enqueue_script( 'youtube_scripts', EAR2WORDS_URL . '/src/youtube/youtube_script.js', array( 'wp-util' ), EAR2WORDS_VER, true );
			wp_localize_script(
				'youtube_scripts',
				'youtube_object',
				array(
					'ajax_url'  => admin_url( 'admin-ajax.php' ),
					'ajaxnonce' => wp_create_nonce( 'itr_ajax_nonce' ),
				)
			);
		}
	}

}

// phpcs:enable
