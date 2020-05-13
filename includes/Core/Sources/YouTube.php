<?php
/**
 * This file describes handle operation on subtitle.
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Core
 */

namespace Ear2Words\Core\Sources;

/**
 * This class handle subtitles.
 */
class YouTube implements \Ear2Words\Core\VideoSource {

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
	 * Effettua la chiamata all'endpoint.
	 *
	 * @param string $id_video id del video youtube.
	 */
	public function get_subtitle( $id_video ) {
		$response = $this->send_job_to_backend( $id_video );

		$response_code = wp_remote_retrieve_response_code( $response );

		if ( 201 !== $response_code ) {
			$get_info_url = "https://www.youtube.com/get_video_info?video_id=$id_video";

			$file_info = array();

			// TODO: warning di phpcs: mi invita ad usare wp_remote_get ma non funziona.
            // phpcs:disable
            $file      = file_get_contents( $get_info_url );
            // phpcs:enable
			parse_str( $file, $file_info );

			$url = json_decode( $file_info['player_response'] )->captions->playerCaptionsTracklistRenderer->captionTracks[0]->baseUrl . '&fmt=json3&xorb=2&xobt=3&xovt=3';

			$response = wp_remote_get( $url );

			$text = '';
			foreach ( json_decode( $response['body'] )->events as $event ) {
				if ( $event->segs ) {
					foreach ( $event->segs as $seg ) {
						$text .= $seg->utf8;
					}
				}
			}

			return $text;
		}
		return 'Error';
	}

}
