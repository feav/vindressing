<?php
/**
 * Ultimate WooCommerce Auction Shortcode
 *
 */
 
class UWA_Shortcode extends WC_Shortcodes {

		private static $instance;	
	/**
	* Returns the *Singleton* instance of this class.
	*
	* @return Singleton The *Singleton* instance.
	*/
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
	
	public function __construct() {			
		
		add_shortcode( 'uwa_new_auctions', array( $this, 'uwa_new_auctions_fun' ) );		
	  
	}	
	/**
	 * New Auction shortcode  
	 * [uwa_new_auctions days_when_added="10" columns="4" orderby="date" order="desc/asc" show_expired="yes/no"]	 
	 *	 
	 */
	 public function uwa_new_auctions_fun( $atts ) {

		global $woocommerce_loop, $woocommerce;
		$timezone = get_option('timezone_string') ? get_option('timezone_string') : "UTC";		
		date_default_timezone_set( $timezone);

		extract(shortcode_atts(array(
			'per_page' 	=> '12',
			'columns' 	=> '4',
			'orderby' => 'date',
			'order' => 'desc',
			'days_when_added' =>'12',
			'show_expired' =>'yes'
		), $atts));

		$meta_query = $woocommerce->query->get_meta_query();
		if($show_expired == 'no'){
        	$meta_query []= array(
							'key'     => 'woo_ua_auction_closed',
							'compare' => 'NOT EXISTS',
							);
        }
		
		
		$days_when_added_pera = "-".$days_when_added." days";				
		
		$args = array(
			'post_type'	=> 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' => $per_page,
			'orderby' => $orderby,
			'order' => $order,
			'meta_query' => $meta_query,
			'date_query' => array(
                    array(
							'after' => date('Y-m-d', strtotime($days_when_added_pera)) 
                    ),

                ),
			'tax_query' => array(array('taxonomy' => 'product_type' , 'field' => 'slug', 'terms' => 'auction')),
			'auction_arhive' => TRUE
		);

		ob_start();
		$products = new WP_Query( $args );
		$woocommerce_loop['columns'] = $columns;
		if ( $products->have_posts() ) : ?>

			<?php woocommerce_product_loop_start(); ?>

				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>
	   <?php else : ?>
            <?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif;

		wp_reset_postdata();

		return '<div class="woocommerce">' . ob_get_clean() . '</div>';
	}
	
}

UWA_Shortcode::get_instance();