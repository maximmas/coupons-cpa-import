<?php

function adv_active_block($order){
  $options = get_option( 'cci_options' );
	$shortcode = $options['adv_shortcode'];
	$cp_block = ( !empty( $shortcode ) ) ? do_shortcode( $shortcode ) : '';
    
  $block = "<div id='add_block' data-order='{$order}'>" . $cp_block . "</div>";
	
  return $block;
    
};
