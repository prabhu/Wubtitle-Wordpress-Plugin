<?php
use Ear2Words\Api\ApiRequest;
/**
 * Class TestAPI
 *
 * @package Ear2Words
 */

 /**
  * Sample test.
  */
class TestApiRequest extends WP_Ajax_UnitTestCase {
  /**
   * Setup function.
   */
   public function SetUp(){
     parent::setUp();
     update_option('siteurl','http://wordpress01.local');
   }
   /**
    * tearDown function.
    */
    public function tearDown(){
      parent::tearDown();
    }
  /**
   * Effettua la chiamata al job correttamente
   */
   public function test_positive_send_request(){
     $video_data = array(
       'filesize' => 123456789,
       'length'   => 155,
     );
     $send_request = $this->getMockBuilder(Ear2Words\Api\ApiRequest::class)->setMethods(['get_media_metadata'])->getMock();
     $send_request->expects($this->any())->method("get_media_metadata")->willReturn($video_data);
     $_POST['_ajax_nonce'] = wp_create_nonce( 'itr_ajax_nonce' );
     $_POST['id_attachment'] = 1;
     $_POST['src_attachment'] = '#';
     $_POST['id_post'] = 1;
     // $_GET['action'] = 'submitVideo';
     // $_POST['action'] = 'submitVideo';
     // $_REQUEST = array_merge( $_POST, $_GET );

     add_option('ear2words_license_key','4465c448e4bb078b96c2a3719a28ff41480c53f3e367bf3a505a8628ddb49478');
     try {
        $this->_handleAjax( 'submitVideo ');
     } catch ( WPAjaxDieContinueException $e ) {
     }
     $response = json_decode( $this->_last_response );
     error_log(print_r($response,true));
     $this->assertTrue( $response->success );
     $this->assertEquals( 201, $response->data );
   }
   /**
    * Effuettua la chiamata senza nonce
    */
    public function test_negative_send_request(){
      try {
          $this->_handleAjax( 'submitVideo' );
      } catch ( WPAjaxDieContinueException $e ) {}

      // Verifica che è stata lanciata l'eccezione
      $this->assertTrue( isset( $e ) );
      $response = json_decode( $this->_last_response );
      $this->assertFalse( $response->success);
    }
    /**
     * Effettua la chiamata senza avere una license key
     */
     public function test_nolicense_send_request(){
       $_POST['_ajax_nonce'] = wp_create_nonce( 'itr_ajax_nonce' );
       $_POST['id_attachment'] = 1;
       $_POST['src_attachment'] = '#';
       $_POST['id_post'] = 1;
       try {
           $this->_handleAjax( 'submitVideo' );
       } catch ( WPAjaxDieContinueException $e ) {}
       // Verifica che è stata lanciata l'eccezione
       $this->assertTrue( isset( $e ) );
       $response = json_decode( $this->_last_response );
       $this->assertFalse( $response->success);
     }
     /**
      * Effettua la chiamata anche se il video è già stato convertito
      */
     public function test_subtitle_already_exists_send_request(){
       $_POST['_ajax_nonce'] = wp_create_nonce( 'itr_ajax_nonce' );
       $_POST['id_attachment'] = 1;
       $_POST['src_attachment'] = '#';
       $_POST['id_post'] = 1;
       add_post_meta($_POST['id_attachment'],'ear2words_subtitle_video',1);
       add_option('ea2words_license_key','teststst');
       try {
           $this->_handleAjax( 'submitVideo' );
       } catch ( WPAjaxDieContinueException $e ) {}
       // Verifica che è stata lanciata l'eccezione
       $this->assertTrue( isset( $e ) );
       $response = json_decode( $this->_last_response );
       $this->assertFalse( $response->success);
     }
     /**
      * Effettua la chiamata al job con una license key non valida
      */
      public function test_invalid_license_send_request(){
        $_POST['_ajax_nonce'] = wp_create_nonce( 'itr_ajax_nonce' );
        $_POST['id_attachment'] = 1;
        $_POST['src_attachment'] = '#';
        $_POST['id_post'] = 1;
        add_option('ear2words_license_key','licensenonvalida');
        try {
            $this->_handleAjax( 'submitVideo' );
        } catch ( WPAjaxDieContinueException $e ) {}
        // Verifica che è stata lanciata l'eccezione
        $this->assertTrue( isset( $e ) );
        $response = json_decode( $this->_last_response );
        $this->assertTrue( $response->success );
        $this->assertEquals( 403, $response->data );
      }
}
