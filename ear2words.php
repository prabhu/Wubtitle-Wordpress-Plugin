<?php
/**
 * Plugin Name:     Ear2Words
 * Plugin URI:      PLUGIN SITE HERE
 * Description:     Plugin che crea dei sottotitoli per ogni video
 * Author:          CTMobi
 * Author URI:      YOUR SITE HERE
 * Text Domain:     ear2words
 * Domain Path:     /languages
 * Version:         0.1.0
 *
 * @package         Ear2words
 */

// Your code starts here.
//
defined( 'ABSPATH' ) || exit;
define( 'EAR2WORDS_FILE_URL', __FILE__ );
define( 'EAR2WORDS_DIR', plugin_dir_path( __FILE__ ) );
define( 'EAR2WORDS_URL', plugin_dir_url( __FILE__ ) );
define( 'EAR2WORDS_NAME', dirname( plugin_basename( __FILE__ ) ) );
define( 'EAR2WORDS_VER', '1.0' );
define( 'ENDPOINT', 'https://u9ferhvmk9.execute-api.eu-west-1.amazonaws.com/collaudo/' );
if ( file_exists( dirname( __FILE__ ) . '/vendor/autoload.php' ) ) {
	require_once dirname( __FILE__ ) . '/vendor/autoload.php';
}

if ( class_exists( 'Ear2Words\\Loader' ) ) {
	Ear2Words\Loader::init();
}
