<?php
/**
 * This file handles Youtube functions.
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\YouTube
 */

namespace Ear2Words\YouTube;

/**
 * This class handles YouTube functions.
 */
class YouTube {
	/**
	 * Init class actions.
	 */
	public function run() {
		add_action( 'admin_enqueue_scripts', array( $this, 'e2w_youtube_scripts' ) );
		add_action( 'wp_ajax_get_info_yt', array( $this, 'get_info' ) );
	}

	/**
	 * Effettua la chiamata all'endpoint.
	 *
	 * @param string $id_video il body della richiesta da inviare.
	 */
	public function send_job_to_backend( $id_video ) {
		$response = wp_remote_post(
			ENDPOINT . 'job/create',
			array(
				'method'  => 'POST',
				'headers' => array(
					'licenseKey'   => get_option( 'ear2words_license_key' ),
					'Content-Type' => 'application/json; charset=utf-8',
				),
				'body'    => wp_json_encode(
					array(
						'source' => 'YOUTUBE',
						'data'   => array(
							'youtubeId' => $id_video,
						),
					)
				),
			)
		);
		return $response;
	}

	/**
	 * Init class actions.
	 */
	public function get_info() {
		if ( isset( $_POST['id'] ) && isset( $_POST['nonce'] ) ) {
			wp_verify_nonce( sanitize_key( $_POST['nonce'] ) );

			$id_video = sanitize_text_field( wp_unslash( $_POST['id'] ) );

			$response = $this->send_job_to_backend( $id_video );

			$response_code = wp_remote_retrieve_response_code( $response );

			if ( 201 !== $response_code ) {
				$get_info_url = "https://www.youtube.com/get_video_info?video_id=$id_video";

				$file_info = array();

				// TODO: phpcs mi invita ad usare wp_remote_get ma non funziona.
 				// phpcs:disable
				$file      = file_get_contents( $get_info_url );
				// phpcs:enable
				parse_str( $file, $file_info );

				$url = json_decode( $file_info['player_response'] )->captions->playerCaptionsTracklistRenderer->captionTracks[0]->baseUrl;

				wp_send_json_success( $url . '&fmt=json3&xorb=2&xobt=3&xovt=3' );
				wp_die();
			}
			wp_send_json_success( 'error' );
			wp_die();
		}
	}


	/**
	 * Includo gli script.
	 *
	 * @param string $hook valore presente nell'hook admin_enqueue_scripts.
	 */
	public function e2w_youtube_scripts( $hook ) {
		if ( 'post.php' === $hook || 'post-new.php' === $hook ) {
			wp_enqueue_script( 'wp-util' );
			wp_enqueue_script( 'youtube_scripts', EAR2WORDS_URL . '/src/youtube/youtube_script.js', array( 'wp-util' ), EAR2WORDS_VER, true );
			wp_localize_script(
				'youtube_scripts',
				'youtube_object',
				array(
					'ajax_url'  => admin_url( 'admin-ajax.php' ),
					'ajaxnonce' => wp_create_nonce( 'itr_ajax_nonce' ),
				)
			);
		}
	}

}
