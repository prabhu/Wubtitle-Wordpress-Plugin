<?php

namespace Ear2Words;

/**
 * effettua il bootstrap del plugin
 */

class Loader {

    /**
     * Istanzia le classi Principali
     */
   public static function init(){
     //Inserire qui le classi da istanziare
     $classes = [
			'gutenber' => Gutenberg\VideoBlock::class,
		 ];
     foreach ( $classes as $class ) {
       $instance = new $class();
       if ( method_exists( $instance, 'run' ) ) {
         $instance->run();
       }
     }
   }
}
