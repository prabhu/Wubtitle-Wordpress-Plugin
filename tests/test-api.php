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
   * Effettua la chiamata al job correttamente
   */
   public function test_positive_send_request(){
     $_POST['_ajax_nonce'] = wp_create_nonce( 'itr_ajax_nonce' );
     $_POST['id_attachment'] = 1;
     $_POST['src_attachment'] = '#';
     $_POST['id_post'] = 1;
     add_option('ea2words_license_key','teststst');
     try {
         $this->_handleAjax( 'submitVideo' );
     } catch ( WPAjaxDieContinueException $e ) {}
     // Verifica che è stata lanciata l'eccezione
     $this->assertTrue( isset( $e ) );
     $response = json_decode( $this->_last_response );
     $this->assertTrue( $response->success);
     //Una volta aggiunto l'url del job da qui verificherò la risposta del job.
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
}
