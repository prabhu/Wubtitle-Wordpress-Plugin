<?php
/**
 * This file describes the transcript custom post type.
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Core\CustomPostTypes
 */

namespace Ear2Words\Core\CustomPostTypes;

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

		add_action( 'add_meta_boxes', array( $this, 'add_youtube_box' ) );

		add_action( 'save_post', array( $this, 'save_postdata' ) );

		add_filter( 'use_block_editor_for_post_type', array( $this, 'disable_gutenberg' ), 10, 2 );
	}

	/**
	 * Disable Gutenber for transcript post type.
	 *
	 * @param string $current_status gutenberg status.
	 * @param string $post_type post type transcript.
	 */
	public function disable_gutenberg( $current_status, $post_type ) {
		if ( 'transcript' === $post_type ) {
			return false;
		}
		return $current_status;
	}

	/**
	 * Aggiunge custom box per meta value source.
	 */
	public function add_source_box() {
		add_meta_box(
			'source_meta_box',
			__( 'Source', 'ear2words' ),
			array( $this, 'source_box_html' ),
			'transcript'
		);
	}

	/**
	 * Aggiunge custom box per meta value id video.
	 */
	public function add_youtube_box() {
		if ( get_post_meta( get_the_ID(), '_transcript_source', true ) === 'youtube' || ! get_post_meta( get_the_ID(), '_transcript_source', true ) ) {
			add_meta_box(
				'youtube_meta_box',
				__( 'Youtube', 'ear2words' ),
				array( $this, 'youtube_box_html' ),
				'transcript'
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
			<input type="hidden" id="nonce" name="nonce" value="<?php echo esc_html( wp_create_nonce( 'nonce' ) ); ?>">
		<?php
	}

	/**
	 * Render del box video youtube.
	 */
	public function youtube_box_html() {
		?>
			<label for="url"><?php echo esc_html( __( 'ID Video', 'ear2words' ) ); ?>
				<div>
					<input type="text" id="youtube-url" name="url" placeholder="<?php echo esc_html( __( 'Insert video ID', 'ear2words' ) ); ?>" value="<?php echo esc_html( get_post_meta( get_the_ID(), '_transcript_youtube_id', true ) ); ?>">
					<div id="youtube-button" class="button button-primary"><?php echo esc_html( __( 'Get transcript', 'ear2words' ) ); ?></div>
					<span id="message"><!-- from JS --></span>					
				</div>
			</label>
		<?php
	}

	/**
	 * Init class actions.
	 *
	 *  @param string $post_id renewal date.
	 */
	public function save_postdata( $post_id ) {
		if ( array_key_exists( 'source', $_POST ) || array_key_exists( 'url', $_POST ) && isset( $_POST['nonce'] ) && wp_verify_nonce( sanitize_key( $_POST['nonce'] ) ) ) {
			update_post_meta(
				$post_id,
				'_transcript_youtube_id',
				sanitize_text_field( wp_unslash( $_POST['url'] ) )
			);
			update_post_meta(
				$post_id,
				'_transcript_source',
				sanitize_text_field( wp_unslash( $_POST['source'] ) )
			);
		}
	}


	/**
	 * Add new scedule cron.
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
			'featured_image'           => __( 'Featured image for this Transcript', 'ear2words' ),
			'set_featured_image'       => __( 'Set featured image for this Transcript', 'ear2words' ),
			'remove_featured_image'    => __( 'Remove featured image for this Transcript', 'ear2words' ),
			'use_featured_image'       => __( 'Use as featured image for this Transcript', 'ear2words' ),
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
			'label'                 => __( 'Transcripts', 'ear2words' ),
			'labels'                => $labels,
			'description'           => __( 'Video Transcripts', 'ear2words' ),
			'public'                => false,
			'publicly_queryable'    => false,
			'show_ui'               => true,
			'show_in_rest'          => true,
			'rest_base'             => '',
			'rest_controller_class' => 'WP_REST_Posts_Controller',
			'has_archive'           => false,
			'show_in_menu'          => true,
			'show_in_nav_menus'     => true,
			'delete_with_user'      => false,
			'exclude_from_search'   => true,
			'capability_type'       => 'post',
			'map_meta_cap'          => true,
			'hierarchical'          => false,
			'rewrite'               => array(
				'slug'       => 'transcript',
				'with_front' => true,
			),
			'query_var'             => true,
			'menu_position'         => 83,
			'menu_icon'             => 'dashicons-format-chat',
			'supports'              => array( 'title', 'editor', 'revisions' ),
		);

		register_post_type( 'transcript', $args );
	}

}

