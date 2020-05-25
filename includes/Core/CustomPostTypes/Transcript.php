<?php
/**
 * This file describes the transcript custom post type.
 *
 * @author     Nicola Palermo
 * @since      1.0.0
 * @package    Wubtitle\Core\CustomPostTypes
 */

namespace Wubtitle\Core\CustomPostTypes;

use \Wubtitle\Core\Sources\YouTube;

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

		add_filter( 'manage_transcript_posts_columns', array( $this, 'set_custom_transcript_column' ) );
		add_action( 'manage_transcript_posts_custom_column', array( $this, 'transcript_custom_column_values' ), 10, 2 );
	}

	/**
	 * Aggiunge una nuova colonna.
	 *
	 * @param array $columns array delle colonne del post.
	 */
	public function set_custom_transcript_column( $columns ) {
		$columns['shortcode'] = __( 'Shortcode', 'wubtitle' );
		return $columns;
	}

	/**
	 * Gestisce il contenuto delle colonne.
	 *
	 * @param string $column colonna da gestire.
	 * @param int    $post_id id del post nel loop.
	 */
	public function transcript_custom_column_values( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				echo esc_html( '[transcript id=' . $post_id . ']' );
				break;
		}
	}

	/**
	 * Aggiunge custom box per meta value source.
	 */
	public function add_source_box() {
		add_meta_box(
			'source_meta_box',
			__( 'Source', 'wubtitle' ),
			array( $this, 'source_box_html' ),
			'transcript',
			'normal',
			'high',
			array(
				'__back_compat_meta_box' => true,
			)
		);
	}


	/**
	 * Render del box source.
	 *
	 * @param array $post array del post.
	 */
	public function source_box_html( $post ) {
		?>
			<p>
				<?php echo esc_html( __( 'Source:', 'wubtitle' ) ); ?>

				<?php echo $post->_transcript_source ? esc_html( $post->_transcript_source ) : esc_html( 'youtube' ); ?>
			</p>

			<input type="hidden" id="source" name="source" value="<?php echo $post->_transcript_source ? esc_attr( $post->_transcript_source ) : esc_html( 'youtube' ); ?>">

			<input type="text" id="youtube-url" name="url" placeholder="<?php echo esc_html( __( 'Insert url youtube video', 'wubtitle' ) ); ?>" value="<?php echo esc_attr( $post->_transcript_url ); ?>">

			<?php wp_nonce_field( 'transcript_data', 'transcript_nonce' ); ?>
		<?php
	}

	/**
	 * Check and generate content.
	 *
	 *  @param string $content contenuto ritornato dall'hook content_save_pre.
	 */
	public function transcript_content( $content ) {
		if ( ! isset( $_POST['url'] ) &&
			! isset( $_POST['source'] ) &&
			! isset( $_POST['transcript_nonce'] )
			) {
			return $content;
		}

		// phpcs:disable
		if ( ! wp_verify_nonce( $_POST['transcript_nonce'] , 'transcript_data' ) ) {
		// phpcs:enable
			return $content;
		}

		switch ( $_POST['source'] ) {
			case 'youtube':
				$video_source = new YouTube();
				break;
			default:
				return;
		}
		$url_video    = sanitize_text_field( wp_unslash( $_POST['url'] ) );
		$url_parts    = wp_parse_url( $url_video );
		$allowed_urls = array(
			'www.youtube.com',
			'www.youtu.be',
		);
		if ( ! in_array( $url_parts['host'], $allowed_urls, true ) ) {
			return '<p style="color:red">' . __( 'Url not a valid youtube url', 'wubtitle' ) . '</p>';
		}
		$query_params = array();
		parse_str( $url_parts['query'], $query_params );
		$id_video = $query_params['v'];

		$args  = array(
			'post_type'      => 'transcript',
			'posts_per_page' => 1,
			'meta_key'       => '_video_id',
			'meta_value'     => $id_video,
		);
		$posts = get_posts( $args );
		if ( ! empty( $posts ) ) {
			return $posts[0]->post_content;
		}

		$response = $video_source->send_job_to_backend( $id_video );

		$response_code = wp_remote_retrieve_response_code( $response );

		$message = array(
			'400' => __( 'An error occurred while creating the transcriptions. Please try again in a few minutes', 'wubtitle' ),
			'401' => __( 'An error occurred while creating the transcriptions. Please try again in a few minutes', 'wubtitle' ),
			'403' => __( 'Unable to create transcriptions. Invalid product license', 'wubtitle' ),
			'500' => __( 'Could not contact the server', 'wubtitle' ),
			'429' => __( 'Error, no more video left for your subscription plan', 'wubtitle' ),
		);
		if ( 201 !== $response_code ) {
			return '<p style="color:red">' . $message[ $response_code ] . '</p>';
		}

		$content = $video_source->get_subtitle( sanitize_text_field( wp_unslash( $_POST['url'] ) ), 'transcript_post_type' );

		$this->has_content( $content );

		return $content;
	}

	/**
	 * Check content.
	 *
	 *  @param string $content id del post.
	 */
	public function has_content( $content ) {
		if ( ! $content ) {
			return '<p style="color:red">' . __( 'Transcript not avaiable for this video.', 'wubtitle' ) . '</p>';
		}
	}

	/**
	 * Update option hook callback.
	 *
	 *  @param string $post_id id del post.
	 */
	public function save_postdata( $post_id ) {
		if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! isset( $_POST['source'] ) || ! isset( $_POST['url'] ) || ! isset( $_POST['transcript_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['transcript_nonce'] ) ), 'transcript_data' ) ) {
			return;
		}

		update_post_meta(
			$post_id,
			'_transcript_url',
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
			'name'                     => __( 'Transcripts', 'wubtitle' ),
			'singular_name'            => __( 'Transcript', 'wubtitle' ),
			'menu_name'                => __( 'Transcripts', 'wubtitle' ),
			'all_items'                => __( 'All transcripts', 'wubtitle' ),
			'add_new'                  => __( 'Add new', 'wubtitle' ),
			'add_new_item'             => __( 'Add new transcript', 'wubtitle' ),
			'edit_item'                => __( 'Edit transcript', 'wubtitle' ),
			'new_item'                 => __( 'New transcript', 'wubtitle' ),
			'view_item'                => __( 'View transcript', 'wubtitle' ),
			'view_items'               => __( 'View transcripts', 'wubtitle' ),
			'search_items'             => __( 'Search transcripts', 'wubtitle' ),
			'not_found'                => __( 'No Transcripts found', 'wubtitle' ),
			'not_found_in_trash'       => __( 'No Transcripts found in trash', 'wubtitle' ),
			'parent'                   => __( 'Parent transcript:', 'wubtitle' ),
			'archives'                 => __( 'Transcript archives', 'wubtitle' ),
			'insert_into_item'         => __( 'Insert into Transcript', 'wubtitle' ),
			'uploaded_to_this_item'    => __( 'Upload to this Transcript', 'wubtitle' ),
			'filter_items_list'        => __( 'Filter Transcripts list', 'wubtitle' ),
			'items_list_navigation'    => __( 'Transcripts list navigation', 'wubtitle' ),
			'items_list'               => __( 'Transcripts list', 'wubtitle' ),
			'attributes'               => __( 'Transcripts attributes', 'wubtitle' ),
			'name_admin_bar'           => __( 'Transcript', 'wubtitle' ),
			'item_published'           => __( 'Transcript published', 'wubtitle' ),
			'item_published_privately' => __( 'Transcript published privately.', 'wubtitle' ),
			'item_reverted_to_draft'   => __( 'Transcript reverted to draft.', 'wubtitle' ),
			'item_scheduled'           => __( 'Transcript scheduled', 'wubtitle' ),
			'item_updated'             => __( 'Transcript updated.', 'wubtitle' ),
			'parent_item_colon'        => __( 'Parent transcript:', 'wubtitle' ),
		);

		$args = array(
			'label'         => __( 'Transcripts', 'wubtitle' ),
			'labels'        => $labels,
			'description'   => __( 'Video Transcripts', 'wubtitle' ),
			'show_ui'       => true,
			'show_in_rest'  => true,
			'map_meta_cap'  => true,
			'hierarchical'  => false,
			'menu_position' => 83,
			'menu_icon'     => 'dashicons-format-chat',
			'supports'      => array( 'title', 'editor', 'revisions' ),
		);

		register_post_type( 'transcript', $args );
	}

}
