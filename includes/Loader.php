<?php
/**
 * Effettua il bootstrap del plugin
 *
 * @package Ear2Words
 */

namespace Ear2Words;

/**
 * This class describes the plugin loader.
 */
class Loader {

	/**
	 * Istanzia le classi Principali
	 */
	public static function init() {
		// Inserire qui le classi da istanziare.
		$classes = array(
			'gutenber'           => Gutenberg\VideoBlock::class,
			'settings'           => Dashboard\Settings::class,
			'request'            => Api\ApiRequest::class,
			'license_validation' => Api\ApiLicenseValidation::class,
		);

		foreach ( $classes as $class ) {
			$instance = new $class();
			if ( method_exists( $instance, 'run' ) ) {
				$instance->run();
			}
		}
	}
}
