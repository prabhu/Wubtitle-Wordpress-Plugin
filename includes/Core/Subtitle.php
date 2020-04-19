<?php
/**
 * This file describes handle operation on subtitle.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Core
 */

namespace Ear2Words\Core;

/**
 * This class handle subtitles.
 */
class Subtitle {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'delete_attachment', array( $this, 'delete_subtitle' ) );
	}

	/**
	 * Handle the subtitle.
	 *
	 * @param int $id_deleted_attachment parametri del file.
	 */
	public function delete_subtitle( $id_deleted_attachment ) {
		$releted_vtt = get_post_meta( $id_deleted_attachment, 'ear2words_subtitle', true );
		wp_delete_attachment( $releted_vtt );
	}

}
