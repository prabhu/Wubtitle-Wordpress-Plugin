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
   * Test che verifica che funziona con nonce
   */
   public function test_positive_send_request(){
     $_POST['_ajax_nonce'] = wp_create_nonce( 'itr_ajax_nonce' );
     try {
         $this->_handleAjax( 'submitVideo' );
     } catch ( WPAjaxDieContinueException $e ) {}
     // Verifica che è stata lanciata l'eccezione
     $this->assertTrue( isset( $e ) );
     $response = json_decode( $this->_last_response );
     $this->assertTrue( $response->success);
   }
   /**
    * Test che verifica che non funziona senza nonce
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
}
