<?php
/**
 * Questo file implementa la chiamata http.
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

use \Ear2words\Core\Sources\YouTube;

/**
 * Questa classe gestisce il custom hook ajax
 */
class ApiGetTranscript {

	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'wp_ajax_get_transcript', array( $this, 'get_transcript' ) );
	}

	/**
	 * Get transcript.
	 */
	public function get_transcript() {
		if ( isset( $_POST['id'] ) && isset( $_POST['source'] ) && isset( $_POST['from'] ) ) {
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['id'] ) ) );
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['source'] ) ) );
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['from'] ) ) );

			$id_video = sanitize_text_field( wp_unslash( $_POST['id'] ) );

			$source = sanitize_text_field( wp_unslash( $_POST['source'] ) );

			$from = sanitize_text_field( wp_unslash( $_POST['from'] ) );

			switch ( $source ) {
				case 'youtube':
					$video_source = new YouTube();
					break;
				case 'media':
					return;
				default:
					return;
			}
			$transcript = $video_source->get_subtitle( $id_video, $from );

			wp_send_json_success( $transcript );
			wp_die();
		}
		wp_die();
	}

}
