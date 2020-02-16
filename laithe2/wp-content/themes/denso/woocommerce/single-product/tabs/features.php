<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$features = get_post_meta( $post->ID, 'apus_product_features', true );
?>
<div class="features">
	<h3 class="title"><?php echo esc_html__('Technical Specifications','denso') ?></h3>
	<?php echo trim($features); ?>
</div>