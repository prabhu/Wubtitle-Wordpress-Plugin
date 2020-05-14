<?php
/**
 * This file extends media library.
 *
 * @author     Alessio Catania
 * @since      1.0.0
 * @package    Ear2Words\Gutenberg
 */

namespace Ear2Words\MediaLibrary;

use Ear2Words\Loader;

/**
 * Classe che estende la media library aggiungendo la sezione per le trascrizioni
 */
class TrascriptionsExtends {
	/**
	 * Setup delle action.
	 */
	public function run() {
		add_filter( 'embed_oembed_html', array( $this, 'ear2words_embed_shortcode' ), 10, 4 );
		add_action( 'media_buttons', array( $this, 'add_my_media_button' ), 15 );
		add_action( 'wp_enqueue_media', array( $this, 'include_media_button_js_file' ) );
		add_filter( 'media_upload_tabs', array( $this, 'my_upload_tab' ) );
		add_action( 'media_upload_videotranscriptions', array( $this, 'add_my_new_form' ) );
	}
	/**
	 * Fa l'override dello shortcode.
	 *
	 * @param string $html html dello shortcode nativo.
	 */
	public function ear2words_embed_shortcode( $html ) {
		$html .= '<p> prova transcription </p>';
		return $html;
	}
	/**
	 * Aggiunge il form.
	 */
	public function add_my_new_form() {
		wp_iframe( array( $this, 'my_new_form' ) );
	}
	/**
	 * Render del frame.
	 */
	public function my_new_form() {
		// phpcs:disable
		echo media_upload_header(); // This function is used for print media uploader headers etc.
		ob_start();
		?>
				<div style="padding: 16px; display: block;">
					<input style="padding: 12px 40px 12px 14px; width: 100%;" id="embed-url-field" type="url" placeholder="Inserisci dall'URL">
					<button id="wub-add-transcriptions" class="button-primary"> Add Transcriptions </button>
				</div>
				<script type="text/javascript">
					const addTranscription = document.getElementById("wub-add-transcriptions");
					if (addTranscription) {
						addTranscription.addEventListener("click", () => {
							window.close();
						});
					}
				</script>
		<?php
		echo ob_get_clean();
		// phpcs:enable
	}
	/**
	 * Aggiunge un tab alla modale del media upload.
	 */
	public function my_upload_tab() {
		$tabs                        = array();
		$tabs['videotranscriptions'] = __( 'Transcriptions', 'ear2words' );
		return $tabs;
	}
	/**
	 * Include il file javascript.
	 */
	public function include_media_button_js_file() {
		//phpcs:disable
		wp_enqueue_script( 'media_button', EAR2WORDS_URL . '/src/editor/media_button.js' );
		//phpcs:enable
	}
	/**
	 * Aggiunge il bottone custom.
	 */
	public function add_my_media_button() {
		echo '<a href="#" id="insert-my-media" class="button">Add transcription</a>';
	}
}
