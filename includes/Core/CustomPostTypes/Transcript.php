<?php
/**
 * This file describes handle WP_Cron functions.
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Ear2Words\Core\CustomPostTypes
 */

namespace Ear2Words\Core\CustomPostTypes;

/**
 * This class handle WP_Cron functions.
 */
class Transcript {
	/**
	 * Init class actions.
	 */
	public function run() {
		add_action( 'init', array( $this, 'register_transcript_cpt' ) );

		add_action( 'add_meta_boxes', array( $this, 'wporg_add_custom_box' ) );

		add_action( 'save_post', array( $this, 'wporg_save_postdata' ) );

		add_filter( 'use_block_editor_for_post_type', array( $this, 'prefix_disable_gutenberg' ), 10, 2 );
	}

	/**
	 * Init class actions.
	 *
	 * @param string $current_status renewal date.
	 * @param string $post_type renewal date.
	 */
	public function prefix_disable_gutenberg( $current_status, $post_type ) {
		if ( 'transcript' === $post_type ) {
			return false;
		}
		return $current_status;
	}

	/**
	 * Init class actions.
	 */
	public function wporg_add_custom_box() {
		add_meta_box(
			'wporg_box_id',
			'Custom Meta Box Title',
			array( $this, 'wporg_custom_box_html' ),
			'transcript'
		);
	}

	/**
	 * Init class actions.
	 *
	 * @param string $post renewal date.
	 */
	public function wporg_custom_box_html( $post ) {
		?>
			<label for="wporg_field">Description for this field</label>
			<select name="wporg_field" id="wporg_field" class="postbox">            	
				<?php wp_nonce_field( 'transcript_nonce' ); ?>
				<option value=""><?php echo esc_html( $post ); ?></option>
				<option value="something">Something</option>
				<option value="else">Else</option>
			</select>
		<?php
	}

	/**
	 * Init class actions.
	 *
	 *  @param string $post_id renewal date.
	 *  @param string $retrieved_nonce renewal date.
	 */
	public function wporg_save_postdata( $post_id, $retrieved_nonce ) {
		if ( array_key_exists( 'wporg_field', $_POST ) && wp_verify_nonce( $retrieved_nonce, 'transcript_nonce' ) ) {
			update_post_meta(
				$post_id,
				'_wporg_meta_key',
				sanitize_text_field( wp_unslash( $_POST['wporg_field'] ) )
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
			'add_new_item'             => __( 'Aggiungi nuovo Transcript', 'ear2words' ),
			'edit_item'                => __( 'Modifica Transcript', 'ear2words' ),
			'new_item'                 => __( 'Nuovo Transcript', 'ear2words' ),
			'view_item'                => __( 'Visualizza Transcript', 'ear2words' ),
			'view_items'               => __( 'Visualizza Transcripts', 'ear2words' ),
			'search_items'             => __( 'Cerca Transcripts', 'ear2words' ),
			'not_found'                => __( 'No Transcripts found', 'ear2words' ),
			'not_found_in_trash'       => __( 'No Transcripts found in trash', 'ear2words' ),
			'parent'                   => __( 'Genitore Transcript:', 'ear2words' ),
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
			'parent_item_colon'        => __( 'Genitore Transcript:', 'ear2words' ),
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
