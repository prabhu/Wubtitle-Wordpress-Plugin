<?php
/**
 * This file extends media library.
 *
 * @author     Alessio Catania
 * @since      1.0.0
 * @package    Wubtitle\Gutenberg
 */

namespace Wubtitle\MediaLibrary;

use Wubtitle\Loader;

/**
 * Classe che estende la media library aggiungendo la sezione per le trascrizioni
 */
class TrascriptionsExtends {
	/**
	 * Setup delle action.
	 */
	public function run() {
		add_action( 'media_buttons', array( $this, 'add_transcriptions_media_button' ), 15 );
		add_action( 'wp_enqueue_media', array( $this, 'include_transcription_modal_script' ) );
		add_action( 'admin_notices', array( $this, 'wubtitle_admin_notice' ) );
	}
	/**
	 * Aggiunge un div per inserire dinamicamente da javascript delle notice
	 */
	public function wubtitle_admin_notice() {
		$screen = get_current_screen();
		if ( ! $screen || 'post' !== $screen->base ) {
			return;
		}
		echo '<div id="wubtitle-notice" class="notice notice-error" style="display:none"></div>';
	}
	/**
	 * Include il file javascript.
	 */
	public function include_transcription_modal_script() {
		wp_enqueue_script( 'transcription_modal_script', WUBTITLE_URL . '/src/editor/transcriptionModalScript.js', null, 'transcription_script', true );
		wp_set_script_translations( 'transcription_modal_script', 'wubtitle', WUBTITLE_DIR . 'languages' );
		wp_localize_script(
			'transcription_modal_script',
			'wubtitle_object_modal',
			array(
				'ajaxnonce' => wp_create_nonce( 'itr_ajax_nonce' ),
			)
		);
	}
	/**
	 * Aggiunge il bottone custom.
	 */
	public function add_transcriptions_media_button() {
		echo '<a href="#" id="insert-my-media" class="button">' . esc_html( __( 'Add transcription', 'wubtitle' ) ) . '</a>';
	}
}
