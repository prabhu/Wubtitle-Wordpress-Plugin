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
		load_plugin_textdomain( 'ear2words', false, EAR2WORDS_NAME . '/languages' );
		$classes = array(
			'gutenber'             => Gutenberg\VideoBlock::class,
			'settings'             => Dashboard\Settings::class,
			'request'              => Api\ApiRequest::class,
			'license_validation'   => Api\ApiLicenseValidation::class,
			'custom_media_library' => MediaLibrary\ListingSubtitles::class,
			'subtitle'             => Core\Subtitle::class,
			'store_subtitle'       => Api\ApiStoreSubtitle::class,
			'payment_template'     => Dashboard\PaymentTemplate::class,
		);

		foreach ( $classes as $class ) {
			$instance = new $class();
			if ( method_exists( $instance, 'run' ) ) {
				$instance->run();
			}
		}
	}
}
