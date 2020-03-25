<?php
/**
 * This file implements Video Block.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Ear2Words\Gutenberg
 */

namespace Ear2Words\Gutenberg;

/**
 * This class describes The Gutenberg video block.
 */
class VideoBlock {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'enqueue_block_editor_assets', array( $this, 'add_subtitle_button_enqueue' ) );
	}
	/**
	 * Enqueue degli script.
	 */
	public function add_subtitle_button_enqueue() {
		wp_enqueue_script( 'add_subtitle_button-script', plugins_url( '../../build/index.js', __FILE__ ), array( 'wp-compose', 'wp-data', 'wp-element', 'wp-hooks', 'wp-api-fetch' ), 'add_subtitle_button', false );
		wp_localize_script(
			'add_subtitle_button-script',
			'ear2words_button_object',
			array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'ajaxnonce' => wp_create_nonce( 'itr_ajax_nonce' ),
			)
		);
	}
}
