<?php
/**
 * This file handle metadata.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Core;

/**
 * Gestisce i metadati.
 */
class AddMeta {
	/**
	 * Init class actions.
	 */
	public function run() {
		add_action( 'save_post', array( $this, 'add_uuid_meta' ) );
	}

	/**
	 * Crea un nuova voce nel menu della dashbord.
	 *
	 * @param array $post_id valori della richiesta.
	 */
	public function add_uuid_meta( $post_id ) {
		// TODO: implementare uuid.
	}
}
