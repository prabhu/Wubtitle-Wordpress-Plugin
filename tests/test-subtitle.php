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
class TestSubtitle extends WP_UnitTestCase {

	/**
	 * Setup del test
	 */
	public function setUp() {
		$this->instance = new Ear2Words\Core\Subtitle();
		
		$this->subtitle_id     = self::factory()->post->create( [
			'is_subtitle' => 'true'
		] );
		$this->video_id   = self::factory()->post->create( [
			'id' => 4,
			'meta_input' => [
				'ear2words_subtitle' => $this->subtitle_id
			]
		] );

	}


	/**
	 * Caso positivo di delete_subtitle.
	 */
	public function test_delete_attachment_and_subtitle() {
		$this->instance->run();
		wp_delete_attachment( $this->video_id );
		
		$subtitle_post = get_post($this->subtitle_id);

		$this->assertFalse($subtitle_post);
	}


}