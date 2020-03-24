<?php
/**
 * Questo file implementa la chiamata http.
 *
 * @author     Alessio Catania
 * @since      2020
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
	 * Da qui invierò la richiesta HTTP.
	 */
	public function send_request() {
		$license_key = get_option( 'ear2words_license_key' );
		if ( isset( $_POST['_ajax_nonce'] ) && isset( $_POST['id_attachment'] ) && isset( $_POST['src_attachment'] ) && isset( $_POST['id_post'] ) && ! empty( $license_key ) ) {
			$nonce          = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
			$id_attachment  = sanitize_text_field( wp_unslash( $_POST['id_attachment'] ) );
			$src_attachment = sanitize_text_field( wp_unslash( $_POST['src_attachment'] ) );
			$id_post        = sanitize_text_field( wp_unslash( $_POST['id_post'] ) );
			$subtitle       = get_post_meta( $id_attachment, 'ear2words_subtitle_video' );
			$domain_name    = str_replace( 'http://', '', get_site_url() );
			if ( ! empty( $subtitle ) ) {
				wp_send_json_error( 'Errore,sottotitoli già esistenti per il video selezionato' );
			}
			if ( check_ajax_referer( 'itr_ajax_nonce', $nonce ) ) {
				$body     = array(
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
				$response = wp_remote_post(
					'https://yp0er5gu22.execute-api.eu-west-1.amazonaws.com/dev/job/create',
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
				wp_send_json_success( $response );
			}
		}
		wp_send_json_error( 'Errore, richiesta non valida' );
	}
}
