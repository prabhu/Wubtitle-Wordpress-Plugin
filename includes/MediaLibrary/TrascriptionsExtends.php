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
		add_action( 'media_buttons', array( $this, 'add_transcriptions_media_button' ), 15 );
		add_action( 'wp_enqueue_media', array( $this, 'include_transcription_modal_script' ) );
	}
	/**
	 * Fa l'override dello shortcode.
	 *
	 * @param string $html html dello shortcode nativo.
	 * @param string $url url del video.
	 * @param array  $attr attributi dello shortcode.
	 */
	public function ear2words_embed_shortcode( $html, $url, $attr ) {
		$url_parts    = wp_parse_url( $url );
		$allowed_urls = array(
			'www.youtube.com',
			'www.youtu.be',
		);
		if ( 'enable' !== $attr['transcription'] || ! in_array( $url_parts['host'], $allowed_urls, true ) ) {
			return $html;
		}
		// TODO eseguire la funzione per creare un nuovo custom posttype e inserire lo shortcode.
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
	 * Include il file javascript.
	 */
	public function include_transcription_modal_script() {
		wp_enqueue_script( 'transcription_modal_script', EAR2WORDS_URL . '/src/editor/transcriptionModalScript.js', null, 'transcription_script', true );
	}
	/**
	 * Aggiunge il bottone custom.
	 */
	public function add_transcriptions_media_button() {
		echo '<a href="#" id="insert-my-media" class="button">Add transcription</a>';
	}
}
