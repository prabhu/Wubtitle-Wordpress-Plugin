<?php
/**
 * Class TestFilter
 *
 * @package Ear2Words
 */

 /**
  * Test filter.
  */
  class TestFilter extends WP_Ajax_UnitTestCase {
    /**
     * Setup function.
     */
     public function SetUp(){
       parent::setUp();
       //Setto come current screen una schermata admin
       set_current_screen('upload');
       //aggiungo un'attachment per i sottotitoli
       $attachment_data = array(
          'guid'           => 'http://wordpress01.local/wp-content/uploads/2020/04/sottotitoli.vtt',
          'post_mime_type' => 'text/vtt',
          'post_title'     => 'sottotitoli',
          'post_name'      => 'sottotitoli',
          'post_content'   => '',
          'post_status'    => 'inherit'
        );
        //error_log(print_r($attachment_data,true));
        $attachment_id = $this->factory()->attachment->create($attachment_data,'/sottotitoli.vtt',1);
        //setto il flag is subtitle a true
        add_post_meta($attachment_id,'is_subtitle','true');
        //aggiungo un'attachment per il video
        $attachment_data = array(
           'guid'           => 'http://wordpress01.local/wp-content/uploads/2020/04/video.mp4',
           'post_mime_type' => 'video/mp4',
           'post_title'     => 'video',
           'post_name'      => 'video',
           'post_content'   => '',
           'post_status'    => 'inherit'
         );
         $attachment_id = $this->factory()->attachment->create($attachment_data,'/video.mp4',2);
     }
     /**
      * tearDown function.
      */
      public function tearDown(){
        parent::tearDown();
      }
      /**
       * Verifica che vengono correttamente filtrati i sottotitoli.
       */
       public function test_filter_attachment(){
          $media = get_posts(array(
            'post_type' => 'attachment'
          ));
          $this->assertEqualSets(1,count($media));
          $this->assertEqualSets('video',$media[0]->post_title);
          //asserzioni, 1 solo elemento, elemento
       }
  }
