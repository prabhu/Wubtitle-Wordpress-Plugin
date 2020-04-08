<?php
/**
 * This file implements new coumns to media library.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Ear2Words\Gutenberg
 */

namespace Ear2Words\MediaLibrary;

/**
 * This class implements new coumns to media library.
 */
class ListingSubtitles {
	/**
	 * Init class actions
	 */
	public function run() {
		add_filter( 'manage_media_columns', array( $this, 'ear2words_status_column' ) );
		add_action( 'manage_media_custom_column', array( $this, 'ear2words_status_value' ), 10, 2 );
		add_action( 'admin_print_styles-upload.php', array( $this, 'ear2words_column_width' ) );
	}
	/**
	 * Aggiungo una colonna
	 *
	 * @param array $cols colonne media library.
	 */
	public function ear2words_status_column( $cols ) {
		$cols['ear2words_status'] = 'Subtitle';
		return $cols;
	}
	/**
	 * Mostro lo stato dei sottotitoli.
	 *
	 * @param string $column_name nome colonna.
	 * @param int    $id_media id dell'attachment.
	 */
	public function ear2words_status_value( $column_name, $id_media ) {
		$all_status = array(
			'pending'  => 'Creating',
			'done'     => 'Draft',
			'enabled'  => 'Enabled',
			'disabled' => 'Disabled',
			'none'     => '__',
			'notfound' => 'No subtitles',
		);
		$status     = get_post_meta( $id_media, 'ear2words_status', true );
		$status     = '' === $status ? 'notfound' : $status;
		$mime_type  = explode( '/', get_post_mime_type( $id_media ) )[0];
		$status     = ( 'video' !== $mime_type ) ? 'none' : $status;
		if ( 'ear2words_status' === $column_name ) {
			echo esc_html( $all_status[ $status ] );
		}
	}
	/**
	 *  Faccio l'enqueue dello stile che devo dare alla nuova colonna.
	 */
	public function ear2words_column_width() {
		wp_enqueue_style( 'ear2words_column_style', plugins_url( '../../src/css/columnStyle.css', __FILE__ ), null, true );
	}
}
