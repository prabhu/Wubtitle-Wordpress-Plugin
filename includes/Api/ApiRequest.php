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
		if ( isset( $_POST['_ajax_nonce'] ) && isset( $_POST['id_attachment'] ) && isset( $_POST['src_attachment'] ) && isset( $_POST['id_post'] ) ) {
			$nonce          = sanitize_text_field( wp_unslash( $_POST['_ajax_nonce'] ) );
			$id_attachment  = sanitize_text_field( wp_unslash( $_POST['id_attachment'] ) );
			$src_attachment = sanitize_text_field( wp_unslash( $_POST['src_attachment'] ) );
			$id_post        = sanitize_text_field( wp_unslash( $_POST['id_post'] ) );
			if ( check_ajax_referer( 'itr_ajax_nonce', $nonce ) ) {
				// Come primo parametro inserirò l'url del job.
				$response = wp_remote_request(
					'#',
					array(
						'id'   => $id_attachment,
						'src'  => $src_attachment,
						'post' => $id_post,
					)
				);
				wp_send_json_success( $response );
			}
		}
		print 'Sorry, your nonce did not verify.';
		wp_die( '-1' );
	}
}
