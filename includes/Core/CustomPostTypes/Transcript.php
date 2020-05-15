<?php
/**
 * This file describes the transcript custom post type.
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Core\CustomPostTypes
 */

namespace Ear2Words\Core\CustomPostTypes;

use \Ear2words\Core\Sources\YouTube;
use Ear2Words\Helpers;

/**
 * This class handle the transcript custom post type methods.
 */
class Transcript {
	/**
	 * Init class actions.
	 */
	public function run() {
		add_action( 'init', array( $this, 'register_transcript_cpt' ) );

		add_action( 'add_meta_boxes', array( $this, 'add_source_box' ) );

		add_filter( 'content_save_pre', array( $this, 'transcript_content' ), 99 );

		add_action( 'save_post_transcript', array( $this, 'save_postdata' ) );
	}

	/**
	 * Aggiunge custom box per meta value source.
	 */
	public function add_source_box() {
		$helpers = new Helpers();
		if ( ! $helpers->is_gutenberg_active() ) {
			add_meta_box(
				'source_meta_box',
				__( 'Source', 'ear2words' ),
				array( $this, 'source_box_html' ),
				'transcript',
				'normal',
				'high',
				array(
					'__back_compat_meta_box' => true,
				)
			);
		}
	}


	/**
	 * Render del box source.
	 */
	public function source_box_html() {
		?>
			<p>
				<?php echo esc_html( __( 'Source:', 'ear2words' ) ); ?> 
				<?php echo get_post_meta( get_the_ID(), '_transcript_source', true ) ? esc_html( get_post_meta( get_the_ID(), '_transcript_source', true ) ) : esc_html( 'youtube' ); ?>
			</p>

			<input type="hidden" id="source" name="source" value="<?php echo get_post_meta( get_the_ID(), '_transcript_source', true ) ? esc_html( get_post_meta( get_the_ID(), '_transcript_source', true ) ) : esc_html( 'youtube' ); ?>">

			<input type="text" id="youtube-url" name="url" placeholder="<?php echo esc_html( __( 'Insert video ID', 'ear2words' ) ); ?>" value="<?php echo esc_html( get_post_meta( get_the_ID(), '_transcript_youtube_id', true ) ); ?>">
		<?php
	}

	/**
	 * Check and generate content.
	 *
	 *  @param string $content contenuto ritornato dall'hook content_save_pre.
	 */
	public function transcript_content( $content ) {
		if ( isset( $_POST['url'] ) && isset( $_POST['source'] ) && ! $content ) {
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['url'] ) ) );
			wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['source'] ) ) );

			switch ( $_POST['source'] ) {
				case 'youtube':
					$video_source = new YouTube();
					break;
				case 'media':
					return;
				default:
					return;
			}
			$content = $video_source->get_subtitle( sanitize_text_field( wp_unslash( $_POST['url'] ) ), 'transcript_post_type' );
			return $content;
		}
		return $content;
	}


	/**
	 * Update option hook callback.
	 *
	 *  @param string $post_id id del post.
	 */
	public function save_postdata( $post_id ) {
		if ( ! wp_is_post_autosave( $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['source'] ) || ! isset( $_POST['url'] ) ) {
			return;
		}

		wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['url'] ) ) );
		wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['source'] ) ) );

		update_post_meta(
			$post_id,
			'_myplugin_book_isbn',
			sanitize_text_field( wp_unslash( $_POST['url'] ) )
		);
		update_post_meta(
			$post_id,
			'_transcript_source',
			sanitize_text_field( wp_unslash( $_POST['source'] ) )
		);
	}


	/**
	 * Registra un nuovo post type.
	 */
	public function register_transcript_cpt() {
		$labels = array(
			'name'                     => __( 'Transcripts', 'ear2words' ),
			'singular_name'            => __( 'Transcript', 'ear2words' ),
			'menu_name'                => __( 'Transcripts', 'ear2words' ),
			'all_items'                => __( 'All transcripts', 'ear2words' ),
			'add_new'                  => __( 'Add new', 'ear2words' ),
			'add_new_item'             => __( 'Add new transcript', 'ear2words' ),
			'edit_item'                => __( 'Edit transcript', 'ear2words' ),
			'new_item'                 => __( 'New transcript', 'ear2words' ),
			'view_item'                => __( 'View transcript', 'ear2words' ),
			'view_items'               => __( 'View transcripts', 'ear2words' ),
			'search_items'             => __( 'Search transcripts', 'ear2words' ),
			'not_found'                => __( 'No Transcripts found', 'ear2words' ),
			'not_found_in_trash'       => __( 'No Transcripts found in trash', 'ear2words' ),
			'parent'                   => __( 'Parent transcript:', 'ear2words' ),
			'archives'                 => __( 'Transcript archives', 'ear2words' ),
			'insert_into_item'         => __( 'Insert into Transcript', 'ear2words' ),
			'uploaded_to_this_item'    => __( 'Upload to this Transcript', 'ear2words' ),
			'filter_items_list'        => __( 'Filter Transcripts list', 'ear2words' ),
			'items_list_navigation'    => __( 'Transcripts list navigation', 'ear2words' ),
			'items_list'               => __( 'Transcripts list', 'ear2words' ),
			'attributes'               => __( 'Transcripts attributes', 'ear2words' ),
			'name_admin_bar'           => __( 'Transcript', 'ear2words' ),
			'item_published'           => __( 'Transcript published', 'ear2words' ),
			'item_published_privately' => __( 'Transcript published privately.', 'ear2words' ),
			'item_reverted_to_draft'   => __( 'Transcript reverted to draft.', 'ear2words' ),
			'item_scheduled'           => __( 'Transcript scheduled', 'ear2words' ),
			'item_updated'             => __( 'Transcript updated.', 'ear2words' ),
			'parent_item_colon'        => __( 'Parent transcript:', 'ear2words' ),
		);

		$args = array(
			'label'            => __( 'Transcripts', 'ear2words' ),
			'labels'           => $labels,
			'description'      => __( 'Video Transcripts', 'ear2words' ),
			'show_ui'          => true,
			'show_in_rest'     => true,
			'delete_with_user' => false,
			'map_meta_cap'     => true,
			'hierarchical'     => false,
			'menu_position'    => 83,
			'menu_icon'        => 'dashicons-format-chat',
			'supports'         => array( 'title', 'editor', 'revisions' ),
		);

		register_post_type( 'transcript', $args );
	}

}

