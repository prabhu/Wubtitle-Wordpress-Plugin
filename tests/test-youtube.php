<?php
/**
 * Test YouTube
 *
 * @package Ear2Words
*/

use \Ear2words\Core\Sources\YouTube;

/**
* Test ricezione trascrizione.
*/
class TestYoutubeTranscript extends WP_UnitTestCase {
	/**
	 * Setup function.
	 */
	public function SetUp(){
		parent::setUp();
        $this->instance = new YouTube;
		$this->id_video = "DxHd4_i_tS0";
	}
	/**
	 * Test callback dell'endpoint che riceve i job falliti.
	 */
	 public function test_get_subtitle(){
		$id_video = $this->id_video;
        $get_info_url = "https://www.youtube.com/get_video_info?video_id=$id_video";

        $file = file_get_contents( $get_info_url );

		$this->assertIsString($file);

        $file_info = array();
        parse_str( $file, $file_info ); 

        $this->assertArrayHasKey('player_response',$file_info);

        $url = json_decode( $file_info['player_response'] )->captions->playerCaptionsTracklistRenderer->captionTracks[0]->baseUrl . '&fmt=json3&xorb=2&xobt=3&xovt=3';

		$response = wp_remote_get( $url );		

		$this->assertArrayHasKey('body',$response);

		$body = json_decode( $response['body'] );

		$this->assertIsObject($body);

		$this->assertObjectHasAttribute('events', $body);
	 }

}
