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
		add_action( 'init', array( $this, 'status_register_meta' ) );
	}
	/**
	 * Da qui invierò la richiesta HTTP.
	 */
	public function send_request() {
		$license_key = get_option( 'ear2words_license_key' );
		if ( ! isset( $_POST['_ajax_nonce'] ) || ! isset( $_POST['id_attachment'] ) || ! isset( $_POST['src_attachment'] ) || ! isset( $_POST['id_post'] ) ) {
			wp_send_json_error( 'Si è verificato un errore durante la creazione dei sottotitoli. Riprova di nuovo tra qualche minuto' );
		}
		if ( empty( $license_key ) ) {
			wp_send_json_error( 'Impossibile creare i sottotitoli. La  licenza del prodotto è assente' );
		}
			$nonce          = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
			$id_attachment  = sanitize_text_field( wp_unslash( $_POST['id_attachment'] ) );
			$src_attachment = sanitize_text_field( wp_unslash( $_POST['src_attachment'] ) );
			$id_post        = sanitize_text_field( wp_unslash( $_POST['id_post'] ) );
			$subtitle       = get_post_meta( $id_attachment, 'ear2words_subtitle_video' );
			$domain_name    = str_replace( 'http://', '', get_site_url() );
		if ( ! empty( $subtitle ) ) {
			wp_send_json_error( 'Impossibile creare i sottotitoli. Esistono già dei sottotitoli per questo video' );
		}
		if ( ! check_ajax_referer( 'itr_ajax_nonce', $nonce ) ) {
			wp_send_json_error( 'Si è verificato un errore durante la creazione dei sottotitoli. Riprova di nuovo tra qualche minuto' );
		}
			$body          = array(
				'data' => array(
					'article' => array(
						'id' => (int) $id_post,
					),
					'video'   => array(
						'id'  => (int) $id_attachment,
						'url' => $src_attachment,
					),
				),
			);
			$response      = wp_remote_post(
				ENDPOINT_URL,
				array(
					'method'  => 'POST',
					'headers' => array(
						'domainName'   => $domain_name,
						'licenseKey'   => $license_key,
						'Content-Type' => 'application/json; charset=utf-8',
					),
					'body'    => wp_json_encode( $body ),
				)
			);
			$code_response = $response['response']['code'];
			$message       = array(
				'401' => 'Si è verificato un errore durante la creazione dei sottotitoli. Riprova di nuovo tra qualche minuto',
				'403' => 'Impossibile creare i sottotitoli. La  licenza del prodotto non è valida',
			);
			if ( 201 !== $code_response ) {
				wp_send_json_error( $message[ $code_response ] );
			}
			update_post_meta( $id_attachment, 'ear2words_status', 'pending' );
			wp_send_json_success( $code_response );
	}

	/**
	 * Registro post meta per lo stato.
	 */
	public function status_register_meta() {
		register_post_meta(
			'attachment',
			'ear2words_status',
			array(
				'show_in_rest' => true,
				'type'         => 'string',
				'single'       => true,
			)
		);
	}
}
