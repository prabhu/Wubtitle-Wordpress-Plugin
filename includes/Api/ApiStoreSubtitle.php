<?php
/**
 * Questo file crea un nuovo endpoint per lo store del file .
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Api
 */

namespace Ear2Words\Api;

use \Firebase\JWT\JWT;

/**
 * Questa classe gestisce lo store dei file vtt.
 */
class ApiStoreSubtitle {
	/**
	 * Init class action.
	 */
	public function run() {
		add_action( 'rest_api_init', array( $this, 'register_store_subtitle_route' ) );
	}

	/**
	 * Crea nuova rotta REST.
	 */
	public function register_store_subtitle_route() {
		register_rest_route(
			'ear2words/v1',
			'/store-subtitle',
			array(
				'methods'  => 'POST',
				'callback' => array( $this, 'auth_and_get_subtitle' ),
			)
		);
	}

	/**
	 * Autenticazione JWT.
	 *
	 * @param array $request valori della richiesta.
	 */
	public function auth_and_get_subtitle( $request ) {
		$headers        = $request->get_headers();
		$jwt            = $headers['jwt'][0];
		$params         = $request->get_param( 'data' );
		$db_license_key = get_option( 'ear2words_license_key' );
		try {
			JWT::decode( $jwt, $db_license_key, array( 'HS256' ) );
		} catch ( \Exception $e ) {
			$error = array(
				'errors' => array(
					'status' => '403',
					'title'  => 'Authentication Failed',
					'source' => $e->getMessage(),
				),
			);
			return $error;
		}
		return $this->get_subtitle( $params );
	}

	/**
	 * Ottiene.
	 *
	 * @param array $params parametri del file.
	 */
	public function get_subtitle( $params ) {
		$url           = $params['url'];
		$id_attachment = $params['attachmentId'];

		$temp_file = download_url( $url );

		if ( is_wp_error( $temp_file ) ) {
			wp_send_json_success( array( 'message' => 'invalid url' ) );
		}

		$file = array(
			'name'     => basename( $url ),
			// TODO: ho fatto il test con "image/jpg", non ho trovato vvt, dovrebbe essere text.
			'type'     => 'text/plain',
			'tmp_name' => $temp_file,
			'error'    => 0,
			'size'     => filesize( $temp_file ),
		);

		$overrides = array(
			'test_form' => false,
		);

		$results = wp_handle_sideload( $file, $overrides );

		if ( ! empty( $results['error'] ) ) {
			wp_send_json_success( $results['error'] );
		}

		update_post_meta( $id_attachment, 'ear2words_status', 'done' );

		wp_send_json_success( array( 'message' => 'file ricevuto' ) );
	}
}




