<?php

$atts  = array_merge( array(
	'title'	=> '',
	'description' => '',
	'style' => ''
), $atts);
extract( $atts );

?>
<div class="widget widget-newletter clearfix <?php echo esc_attr($style); ?>" >
 	
    <div class="widget-content"> 
		<?php
			if ( function_exists( 'mc4wp_show_form' ) ) {
			  	try {
			  	    $form = mc4wp_get_form(); 
					mc4wp_show_form( $form->ID );
				} catch( Exception $e ) {
				 	esc_html_e( 'Please create a newsletter form from Mailchip plugins', 'denso' );	
				}
			}
		?>
	</div>
</div>