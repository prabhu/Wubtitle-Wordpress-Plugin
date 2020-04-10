<?php
/**
 * Class Subtitle Test
 *
 * @package Ear2words
 */

use Ear2Words\Core\Subtitle;

/**
 * Subtitle test case.
 */
class TestSubtitle extends WP_Ajax_UnitTestCase {

	/**
	 * Setup del test
	 */
	public function setUp() {
		parent::setUp();
		$this->instance = new Subtitle();
		$this->instance->run();
		$attachment_data = array(
			'guid'           => 'http://wordpress01.local/wp-content/uploads/2020/04/sottotitoli.vtt',
			'post_mime_type' => 'text/vtt',
			'post_title'     => 'sottotitoli',
			'post_content'   => '',
			'meta_input'     => array(
				'is_subtitle'    => 'true',
			)
		);
		$this->subtitle_id = $this->factory()->attachment->create($attachment_data,'/sottotitoli.vtt',1);
		$attachment_data = array(
			'guid'           => 'http://wordpress01.local/wp-content/uploads/2020/04/video.mp4',
			'post_mime_type' => 'video/mp4',
			'post_title'     => 'video',
			'post_content'   => '',
			'meta_input'     => array(
				'ear2words_subtitle' => $this->subtitle_id
			)
		);
		$this->video_id = $this->factory()->attachment->create($attachment_data,'/video.mp4',2);		
	}
	
	public function test_delete_attachment_and_subtitle() {
		$delete = wp_delete_attachment( $this->video_id);
		$this->assertInstanceOf('WP_Post',$delete);
		$subtitle_post = get_post($this->subtitle_id);
		$this->assertNull($subtitle_post);
	}

}