<?php
/**
 * This file implements Test.
 *
 * @author     Nicola Palermo
 * @since      2020
 * @package    Ear2Words\Core
 */

namespace Ear2Words\Core;

/**
 * This class describes Settings.
 */
class EditVideo {
	/**
	 * Init class actions
	 */
	public function run() {
        add_filter('attachment_fields_to_edit', array( $this, 'add_subtitle_field'), 10, 2);		
    }
    
	public function add_subtitle_field( $form_fields, $post ) {
        $id_releted_vtt = get_post_meta($post->ID, 'ear2words_subtitle', true);

        $file_vtt = wp_get_attachment_url( $id_releted_vtt );

        $content = file_get_contents($file_vtt);

        $form_fields['text_field'] = array(
            'class' => 'subtitle-textarea',
            'label' => 'Subtitles',
            'input' => 'textarea',
            'value' => $content,
        );
        return $form_fields;
    }

}

?>