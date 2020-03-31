<?php
/**
 * Questo file implementa la chiamata http.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

/**
 * Questa classe implementa la chiamata alla APIGateway,
 */
class ApiRequest {
	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'wp_ajax_submitVideo', array( $this, 'send_request' ) );
		add_action( 'wp_ajax_nopriv_submitVideo', array( $this, 'send_request' ) );
	}
	/**
	 * Get media attachment.
	 *
	 * @param integer $id_attachment id del video.
	 */
	public function get_media_metadata( $id_attachment ) {
		return wp_get_attachment_metadata( $id_attachment );
	}

	/**
	 * Verifica la validazione
	 *
	 *  @param array  $array post.
	 *  @param string $license_key licenza utente.
	 */
	public function sanitize_input( $array, $license_key ) {
		if ( ! isset( $array['id_attachment'] ) || ! isset( $array['src_attachment'] ) || empty( $license_key ) ) {
			return false;
		}
		$array['id_attachment']  = sanitize_text_field( wp_unslash( $array['id_attachment'] ) );
		$array['src_attachment'] = sanitize_text_field( wp_unslash( $array['src_attachment'] ) );
		$array['check']          = true;
		return $array;
	}
	/**
	 *  Creo il body della richiesta.
	 *
	 * @param int    $id_attachment id del video.
	 * @param string $src_attachment url del video.
	 */
	public function set_body_request( $id_attachment, $src_attachment ) {
		$id_attachment = (int) $id_attachment;
		$video_data    = $this->get_media_metadata( $id_attachment );
		if ( ! is_numeric( $id_attachment ) || $video_data['filesize'] <= 0 || $video_data['length'] <= 0 || ! filter_var( $src_attachment, FILTER_VALIDATE_URL ) ) {
			return false;
		}
		$body = array(
			'data' => array(
				'attachmentId' => (int) $id_attachment,
				'url'          => $src_attachment,
				'size'         => $video_data['filesize'],
				'duration'     => $video_data['length'],
			),
		);
		return $body;
	}
	/**
	 * Da qui invierò la richiesta HTTP.
	 */
	public function send_request() {
		$license_key = get_option( 'ear2words_license_key' );
		if ( ! isset( $_POST['_ajax_nonce'] ) ) {
			wp_send_json_error( 'Errore, richiesta non valida' );
		}
		$nonce = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
		if ( ! check_ajax_referer( 'itr_ajax_nonce', $nonce ) ) {
			wp_send_json_error( 'Errore, richiesta non valida' );
		}
		$data_attachment = $this->sanitize_input( $_POST, $license_key );
		if ( ! $data_attachment ) {
			wp_send_json_error( 'Errore, richiesta non valida' );
		}
			$subtitle = get_post_meta( $data_attachment['id_attachment'], 'ear2words_subtitle_video' );
		if ( ! empty( $subtitle ) ) {
			wp_send_json_error( 'Errore,sottotitoli già esistenti per il video selezionato' );
		}
			$body = $this->set_body_request( $data_attachment['id_attachment'], $data_attachment['src_attachment'] );
		if ( ! $body ) {
			wp_send_json_error( 'Errore, richiesta non valida' );
		}
			$response = wp_remote_post(
				ENDPOINT_URL,
				array(
					'method'  => 'POST',
					'headers' => array(
						'licenseKey'   => $license_key,
						'Content-Type' => 'application/json; charset=utf-8',
					),
					'body'    => wp_json_encode( $body ),
				)
			);
			wp_send_json_success( $response['response']['code'] );
	}
}
