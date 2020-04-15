<?php
/**
 * This file extends media library.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Ear2Words\Gutenberg
 */

namespace Ear2Words\MediaLibrary;

use Ear2Words\Loader;
/**
 * Classe che estende la media library
 */
class MediaLibraryExtented {
	/**
	 * Instanzia le azioni.
	 */
	public function run() {
		if ( ! $this->is_gutenberg_active() ) {
			add_action( 'attachment_fields_to_edit', array( $this, 'add_generate_subtitle_button' ), 99, 2 );
			add_filter( 'attachment_fields_to_save', array( $this, 'video_attachment_fields_to_save' ), null, 2 );
			add_filter( 'wp_video_shortcode_override', array( $this, 'ear2words_video_shortcode' ), 10, 4 );
		}
	}
	/**
	 * Verifica se gutenberg è attivo.
	 */
	private function is_gutenberg_active() {
		// Gutenberg plugin is installed and activated.
		$gutenberg = ! ( false === has_filter( 'replace_editor', 'gutenberg_init' ) );

		// Block editor since 5.0.
		$block_editor = version_compare( $GLOBALS['wp_version'], '5.0-beta', '>' );

		if ( ! $gutenberg && ! $block_editor ) {
			return false;
		}

		if ( $this->is_classic_editor_active() ) {
			$editor_option       = get_option( 'classic-editor-replace' );
			$block_editor_active = array( 'no-replace', 'block' );

			return in_array( $editor_option, $block_editor_active, true );
		}

		return true;
	}
	/**
	 * Verifica se il classic editor è attivo.
	 */
	private function is_classic_editor_active() {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		if ( is_plugin_active( 'classic-editor/classic-editor.php' ) ) {
			return true;
		}
		return false;
	}
	/**
	 *  Aggiunge il bottone
	 *
	 * @param array $form_fields campi finestra modale.
	 * @param array $post attachment.
	 */
	public function add_generate_subtitle_button( $form_fields, $post ) {
		$all_status = array(
			'pending'  => __( 'Creating', 'ear2words' ),
			'done'     => __( 'Draft', 'ear2words' ),
			'enabled'  => __( 'Enabled', 'ear2words' ),
			'disabled' => __( 'Disabled', 'ear2words' ),
		);
		if ( ! wp_attachment_is( 'video', $post ) ) {
			return $form_fields;
		}
		if ( empty( get_post_meta( $post->ID, 'ear2words_status' ) ) ) {
			$form_fields['button']          = array(
				'label' => 'Ear2Words',
				'input' => 'html',
				'html'  => '<label for="attachments-' . $post->ID . '-button"> <input type="checkbox" id="attachments-' . $post->ID . '-button" name="attachments[' . $post->ID . '][button]" value="' . $post->ID . '"/> Generate subtitles</label>',
				'value' => $post->ID,
				'helps' => 'Check for generate subtitles',
			);
			$lang                           = explode( '_', get_locale(), 2 )[0];
			$form_fields['button']['html'] .= '<select name="attachments[' . $post->ID . '][select-lang]" id="Profile Image Select">';
			$form_fields['button']['html'] .= '<option ' . selected( $lang, 'it', false ) . 'value="it">' . __( 'Italian', 'ear2words' ) . '</option>';
			$form_fields['button']['html'] .= '<option ' . selected( $lang, 'en', false ) . ' value="en">' . __( 'English', 'ear2words' ) . '</option>';
			$form_fields['button']['html'] .= '<option ' . selected( $lang, 'es', false ) . 'value="es">' . __( 'Spanish', 'ear2words' ) . '</option>';
			$form_fields['button']['html'] .= '<option ' . selected( $lang, 'de', false ) . 'value="de">' . __( 'German ', 'ear2words' ) . '</option>';
			$form_fields['button']['html'] .= '<option ' . selected( $lang, 'zh', false ) . 'value="zh">' . __( 'Chinese', 'ear2words' ) . '</option>';
			$form_fields['button']['html'] .= '<option ' . selected( $lang, 'fr', false ) . 'value="fr">' . __( 'French', 'ear2words' ) . '</option>';
			$form_fields['button']['html'] .= '</select>';
			return $form_fields;
		}
		$status                = get_post_meta( $post->ID, 'ear2words_status', true );
		$form_fields['button'] = array(
			'label' => 'Ear2Words',
			'input' => 'html',
			'html'  => '<label for="attachments-' . $post->ID . '-button">' . $all_status[ $status ] . '</label>  ',
			'value' => $post->ID,
			'helps' => 'Check for generate subtitles',
		);
		return $form_fields;
	}
	/**
	 * Esegue la chiamata all'endpoint e salva uuid e stato.
	 *
	 * @param array $post contiene i dati dell'attachment.
	 * @param array $attachment contiene i dati degli input custom.
	 */
	public function video_attachment_fields_to_save( $post, $attachment ) {
		if ( isset( $attachment['button'] ) ) {
			$data['lang']           = $attachment['select-lang'];
			$data['id_attachment']  = $post['ID'];
			$data['src_attachment'] = wp_get_attachment_url( $post['ID'] );
			$data                   = Loader::get( 'request' )->sanitize_input( $data );
			if ( ! $data ) {
				// TODO restituire il messaggio di errore.
				return;
			}
			$body = Loader::get( 'request' )->set_body_request( $data );
			if ( ! $body ) {
				// TODO restituire il messaggio di errore.
				return;
			}
			$license_key = get_option( 'ear2words_license_key' );
			if ( empty( $license_key ) ) {
				// TODO restituire il messaggio di errore.
				return;
			}
			$response = Loader::get( 'request' )->remote_post_endpoint( $body, $license_key );
			if ( 201 === $response['response']['code'] ) {
				$response_body = json_decode( $response['body'] );
				Loader::get( 'request' )->success_request_function( $post['ID'], $response_body->data->jobId );
			}
		}
	}
	/**
	 * Sovrascrive lo shortcode video aggiungendo i sottotitoli come file_get_content
	 *
	 * @param string $html html generato dallo shortcode.
	 * @param array  $attr attributi dello shortcode.
	 */
	public function ear2words_video_shortcode( $html, $attr ) {
		remove_filter( 'wp_video_shortcode_override', array( $this, 'ear2words_video_shortcode' ), 10 );
		$id_video = attachment_url_to_postid( $attr['mp4'] );
		$subtitle = get_post_meta( $id_video, 'ear2words_subtitle', true );
		// TODO quando si implementa l'editor si dovrà verificare che lo stato sia enabled.
		if ( '' !== $subtitle ) {
			$content = '<track srclang="it" label="Italian" kind="subtitles" src="' . wp_get_attachment_url( $subtitle ) . '" default>';
			$html    = wp_video_shortcode( $attr, $content );
		}
		add_filter( 'wp_video_shortcode_override', array( $this, 'ear2words_video_shortcode' ), 10, 4 );
		return $html;
	}
}
