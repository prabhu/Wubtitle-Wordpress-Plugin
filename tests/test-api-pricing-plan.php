<?php
use Wubtitle\Api\ApiPricingPlan;
/**
 * Class TestAPIPricingPlan
 *
 * @package Wubtitle
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
    * Test not user free
    */
    public function test_not_user_free(){
      update_option( 'wubtitle_free', false );
      try {
          $this->_handleAjax( 'check_plan_change' );
      } catch ( WPAjaxDieContinueException $e ) {}

      // Verifica che è stata lanciata l'eccezione
      $this->assertTrue( isset( $e ) );
      $response = json_decode( $this->_last_response );
      $expected = 'change_plan';
      $this->assertTrue( $response->success);
      $this->assertEqualSets($expected, $response->data);
    }
    /**
     * Fail test, license not found.
     */
     public function test_no_license_send_request(){
       $all_plans = array(
         'plan_test' => array(
           'stripe_code' => 'test_code'
         )
       );
       update_option( 'wubtitle_all_plans', $all_plans );
       $result = $this->instance->send_wanted_plan_info('plan_test');

       // Verifica che è stata lanciata l'eccezione
       $expected = 'The product license key is missing.';
       //verifica che c'è stato un'errore
       $this->assertEqualSets($expected, $result);
     }
      /**
       * Verifica che il body è stato creato correttamente
       */
       public function test_body_request(){
         $pricing_plan = 'premium';
         $site_url = get_site_url();
         $result = $this->instance->set_body_request( $pricing_plan, $site_url );
         $lang_expected = explode( '_', get_locale(), 2 )[0];
         $expected_body = array(
    			 'data' => array(
    				 'planId'    => 'premium',
    				 'domainUrl' => 'http://wordpress01.local',
             'siteLang' => $lang_expected,
    			 ),
    		 );
         $this->assertEqualSets($expected_body,$result);
       }
}
