<?php
use Ear2Words\Api\ApiPricingPlan;
/**
 * Class TestAPIPricingPlan
 *
 * @package Ear2Words
 */

 /**
  * Classe che effettua dei test su ApiPricingPlan.
  */
class TestApiPricingPlan extends WP_Ajax_UnitTestCase {
  /**
   * Setup function.
   */
   public function SetUp(){
     parent::setUp();
     update_option('siteurl','http://wordpress01.local');
     $this->instance = new ApiPricingPlan();
   }
   /**
    * tearDown function.
    */
    public function tearDown(){
      parent::tearDown();
    }

   /**
    * Effuettua la chiamata senza nonce
    */
    public function test_negative_send_request(){
      try {
          $this->_handleAjax( 'submit_plan' );
      } catch ( WPAjaxDieContinueException $e ) {}

      // Verifica che è stata lanciata l'eccezione
      $this->assertTrue( isset( $e ) );
      $response = json_decode( $this->_last_response );
      $this->assertFalse( $response->success);
    }
      /**
       * Verifica che il body è stato creato correttamente
       */
       public function test_body_request(){
         $pricing_plan = 'premium';
         $site_url = get_site_url();
         $result = $this->instance->set_body_request( $pricing_plan, $site_url );
         $expected_body = array(
    			 'data' => array(
    				 'planId'    => 'premium',
    				 'domainUrl' => 'http://wordpress01.local',
    			 ),
    		 );
         $this->assertEqualSets($expected_body,$result);
       }
}
