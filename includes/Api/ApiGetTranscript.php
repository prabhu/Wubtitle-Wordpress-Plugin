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
		// phpcs:disable
		if ( isset( $_POST['id'] ) && isset( $_POST['source'] ) && isset( $_POST['from'] ) ) {
		
			$id_video = sanitize_text_field( wp_unslash( $_POST['id'] ) );

			$source = sanitize_text_field( wp_unslash( $_POST['source'] ) );

			$from = sanitize_text_field( wp_unslash( $_POST['from'] ) );
		// phpcs:enable

			switch ( $source ) {
				case 'youtube':
					$video_source = new YouTube();
					break;
				case 'media':
					return;
				default:
					return;
			}

			$response = $video_source->send_job_to_backend( $id_video );

			$response_code = wp_remote_retrieve_response_code( $response );

			if ( 201 !== $response_code ) {
				wp_send_json_success( $this->handle_backend_error( $response_code ) );
				wp_die();
			}

			$transcript = $video_source->get_subtitle( $id_video, $from );

			wp_send_json_success( $transcript );
			wp_die();
		}
		wp_die();
	}

	/**
	 * Gestisce il messaggio d'errore.
	 *
	 * @param int $response_code response code della chiamata al backend.
	 */
	public function handle_backend_error( $response_code ) {
		switch ( $response_code ) {
			case 400:
				return __( 'Some issues with the request. Try again in a few minutes or contact the support.', 'ear2words' );
			case 401:
				return __( 'Unauthorized. Check your license key.', 'ear2words' );
			case 403:
				return __( 'Forbidden. Check your license key.', 'ear2words' );
			case 429:
				return __( 'Too Many Requests. Try again in a few minutes.', 'ear2words' );
			default:
				return __( 'Error.', 'ear2words' );
		}
	}

}
