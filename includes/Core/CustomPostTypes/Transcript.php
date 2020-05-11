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
			'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt', 'trackbacks', 'custom-fields', 'revisions', 'author', 'page-attributes', 'post-formats' ),
			'taxonomies'            => array( 'category', 'post_tag' ),
		);

		register_post_type( 'transcript', $args );
	}


}
