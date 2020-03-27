<?php
/**
 * This file implements Settings.
 *
 * @author     Nicola Palermo
 * @since      0.1.0
 * @package    Ear2Words\Dashboard
 */

namespace Ear2Words\Core;

/**
 * This class describes Settings.
 */
class AddMeta {
	/**
	 * Init class actions
	 */
	public function run() {
		add_action( 'save_post', array( $this, 'add_uuid_meta' ) );
	}

	/**
	 * Crea un nuova voce nel menu della dashbord
	 */
	public function add_uuid_meta($post_id) {
		// TODO: implementare uuid
	}
}
