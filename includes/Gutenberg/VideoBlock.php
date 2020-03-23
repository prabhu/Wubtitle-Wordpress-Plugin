<?php
/**
 * This file implements Video Block.
 *
 * @author     Alessio Catania
 * @since      2020
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
		add_action( 'enqueue_block_editor_assets', array( $this, 'myguten_enqueue' ) );
	}
	/**
	 * Enqueue degli script.
	 */
	public function myguten_enqueue() {
		wp_enqueue_script( 'myguten-script', plugins_url( '../../dist/bloks.build.js', __FILE__ ), array( 'wp-blocks' ), 'myguten', false );
		wp_localize_script(
			'myguten-script',
			'my_ajax_object',
			array(
				'ajax_url'  => admin_url( 'admin-ajax.php' ),
				'ajaxnonce' => wp_create_nonce( 'itr_ajax_nonce' ),
			)
		);
	}
}
