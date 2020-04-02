<?php
/**
 * This file implements Video Block in frontend.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Ear2Words\Gutenberg
 */

namespace Ear2Words\DynamicBlocks;

/**
 * This class describes the video block in frontend.
 */
class VideoBlockFrontend {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'init', array( $this, 'gutenberg_examples_dynamic' ) );
	}
	/**
	 * Registro il block type facendo override al blocco core/video.
	 */
	public function gutenberg_examples_dynamic() {
		register_block_type(
			'core/video',
			array(
				'render_callback' => array( $this, 'gutenberg_examples_dynamic_render_callback' ),
			)
		);
	}
	/**
	 * Callback che definisce il blocco dinamico.
	 *
	 * @param array  $attributes attributi del video (id).
	 * @param string $content html generato da wordress per il blocco video standard.
	 */
	public function gutenberg_examples_dynamic_render_callback( $attributes, $content ) {
		$subtitle = get_post_meta( $attributes['id'], 'ear2words_subtitle', true );
		$video    = get_post( $attributes['id'] );
		if ( '' === $subtitle ) {
			return $content;
		}
		return sprintf(
			'<figure class="wp-block-video">
           <video controls src="' . $video->guid . '">
             <track label="Italian" kind="subtitles" srclang="it" src="' . get_site_url() . '/wp-content/uploads/' . $subtitle . '" default></video>
           </video>
       </figure>'
		);
	}
}
