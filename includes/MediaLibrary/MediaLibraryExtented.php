<?php
/**
 * This file extends media library.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Ear2Words\Gutenberg
 */

namespace Ear2Words\MediaLibrary;

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
		if ( ! wp_attachment_is( 'video', $post ) ) {
			return $form_fields;
		}
		$form_fields['regenerate_thumbnails'] = array(
			'label'         => '',
			'input'         => 'html',
			'html'          => '<a href="#" class="button-secondary button-large" title="' . esc_attr( 'Generate Subtitles' ) . '">Generate Subtitles</a>',
			'show_in_modal' => true,
			'show_in_edit'  => false,
		);
		return $form_fields;
	}
}
