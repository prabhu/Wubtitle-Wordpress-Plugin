<?php
/**
 * This file implements Transcription Block.
 *
 * @author     Alessio Catania
 * @since      1.0.0
 * @package    Wubtitle\Gutenberg
 */

namespace Wubtitle\Gutenberg;

/**
 * This class describes The Gutenberg Transcription Block.
 */
class TranscriptionBlock {
	/**
	 * Init class actions.
	 */
	public function run() {
		add_action( 'init', array( $this, 'create_transcription_block' ) );
	}
	/**
	 * Registra un nuovo block type.
	 */
	public function create_transcription_block() {
		wp_register_script( 'trascription_block_script', WUBTITLE_URL . '/build/index.js', array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-data' ), 'transcription_block', false );
		wp_register_style( 'stylesheet_transcription_block', WUBTITLE_URL . '/src/css/transBlockStyle.css', null, 'transcript_block_style', false );

		register_block_type(
			'wubtitle/transcription',
			array(
				'editor_style' => 'stylesheet_transcription_block',
			)
		);
	}

}
