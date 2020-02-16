<?php
global $product;
$time_sale = get_post_meta( $product->get_id(), '_sale_price_dates_to', true );
$stock_sold = ( $total_sales = get_post_meta( $product->get_id(), 'total_sales', true ) ) ? round( $total_sales ) : 0;
$stock_available = ( $stock = get_post_meta( $product->get_id(), '_stock', true ) ) ? round( $stock ) : 0;
$total_stock = $stock_sold + $stock_available;
$percentage = ( $stock_available > 0 ? round($stock_sold/$total_stock * 100) : 0 );
?>
<div class="special-product">
	<?php if ( $time_sale ) { ?>
		<div class="time">
		    <span><?php echo esc_html__( 'Start for you in: ', 'denso' ); ?></span>
		    <div class="apus-countdown clearfix" data-time="timmer"
		        data-date="<?php echo date('m', $time_sale).'-'.date('d', $time_sale).'-'.date('Y', $time_sale).'-'. date('H', $time_sale) . '-' . date('i', $time_sale) . '-' .  date('s', $time_sale) ; ?>">
		    </div>
		</div>
		<?php if ( $stock_available > 0 ) { ?>
			<div class="special-progress">
			    <div class="claimed">
			        <?php echo sprintf(__('Claimed: <strong>%s</strong>', 'denso'), $percentage . '%'); ?>
			    </div>
			    <div class="progress">
			        <span class="progress-bar" style="<?php echo esc_attr('width:' . $percentage . '%'); ?>"></span>
			    </div>
			</div>
		<?php } ?>
	<?php } ?>
</div>