<?php
/**
 * This file implements new coumns to media library.
 *
 * @author     Alessio Catania
 * @since      0.1.0
 * @package    Wubtitle\Gutenberg
 */

namespace Wubtitle\MediaLibrary;

/**
 * This class implements new coumns to media library.
 */
class ListingSubtitles {
	/**
	 * Init class actions
	 */
	public function run() {
		add_filter( 'manage_media_columns', array( $this, 'wubtitle_status_column' ) );
		add_action( 'manage_media_custom_column', array( $this, 'wubtitle_status_value' ), 10, 2 );
		add_action( 'admin_print_styles-upload.php', array( $this, 'wubtitle_column_width' ) );
		add_action( 'pre_get_posts', array( $this, 'wubtitle_exclude_subtitle_file' ) );
	}
	/**
	 * Aggiungo una colonna
	 *
	 * @param array $cols colonne media library.
	 */
	public function wubtitle_status_column( $cols ) {
		$cols['wubtitle_status'] = __( 'Subtitle', 'wubtitle' );
		return $cols;
	}
	/**
	 * Mostro lo stato dei sottotitoli.
	 *
	 * @param string $column_name nome colonna.
	 * @param int    $id_media id dell'attachment.
	 */
	public function wubtitle_status_value( $column_name, $id_media ) {
		$all_status = array(
			'pending'  => __( 'Generating', 'wubtitle' ),
			'draft'    => __( 'Draft', 'wubtitle' ),
			'enabled'  => __( 'Published', 'wubtitle' ),
			'notvideo' => '__',
			'notfound' => __( 'None', 'wubtitle' ),
			'error'    => __( 'Error', 'wubtitle' ),
		);
		$status     = get_post_meta( $id_media, 'wubtitle_status', true );
		$status     = '' === $status ? 'notfound' : $status;
		$mime_type  = explode( '/', get_post_mime_type( $id_media ) )[0];
		$status     = ( 'video' !== $mime_type ) ? 'notvideo' : $status;
		if ( 'wubtitle_status' === $column_name ) {
			echo esc_html( $all_status[ $status ] );
		}
	}
	/**
	 *  Faccio l'enqueue dello stile che devo dare alla nuova colonna.
	 */
	public function wubtitle_column_width() {
		wp_enqueue_style( 'wubtitle_column_style', plugins_url( '../../src/css/columnStyle.css', __FILE__ ), null, true );
	}
	/**
	 * Esclude i file sottotitoli dalla libreria media.
	 *
	 * @param WP_Query $query instanza di WP_QUERY.
	 */
	public function wubtitle_exclude_subtitle_file( $query ) {
		if ( is_admin() && 'attachment' === $query->get( 'post_type' ) ) {
			$query->set( 'meta_key', 'is_subtitle' );
			$query->set( 'meta_compare', 'NOT EXISTS' );
		}
	}
}
