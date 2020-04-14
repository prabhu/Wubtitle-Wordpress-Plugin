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
	 * L'array che contiene il nome e l'instanza della classe.
	 *
	 * @var array
	 */
	private static $services = array();
	/**
	 * Istanzia le classi Principali
	 */
	public static function init() {
		load_plugin_textdomain( 'ear2words', false, EAR2WORDS_NAME . '/languages' );
		$classes = array(
			'gutenber'               => Gutenberg\VideoBlock::class,
			'settings'               => Dashboard\Settings::class,
			'request'                => Api\ApiRequest::class,
			'license_validation'     => Api\ApiLicenseValidation::class,
			'custom_media_library'   => MediaLibrary\ListingSubtitles::class,
			'subtitle'               => Core\Subtitle::class,
			'store_subtitle'         => Api\ApiStoreSubtitle::class,
			'extented_media_library' => MediaLibrary\MediaLibraryExtented::class,
		);

		foreach ( $classes as $key => $class ) {
			$instance = new $class();
			self::bind( $key, $instance );
			if ( method_exists( $instance, 'run' ) ) {
				$instance->run();
			}
		}
	}
	/**
	 *  Crea una singola instanza della classe.
	 *
	 * @param string $key nome instanza.
	 * @param class  $item instanza della classe.
	 */
	public static function bind( $key, $item ) {
		( self::$services )[ $key ] = $item;
	}
	/**
	 * Restituisce l'istanza della classe.
	 *
	 * @param string $key nome instanza.
	 */
	public static function get( $key ) {
		if ( ! isset( self::$services[ $key ] ) ) {
			return false;
		}

		return ( self::$services )[ $key ];
	}
}
