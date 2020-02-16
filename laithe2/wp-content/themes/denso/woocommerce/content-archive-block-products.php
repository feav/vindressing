<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}
// type: deal, bestseller, new, toprated, recommended
if ( !isset($type) || !isset($parent_slug) ) {
	return;
}

$term = get_term_by( 'slug', $parent_slug, 'product_cat' );
if ( empty( $term ) || is_wp_error( $term ) ) {
	return;
}

switch ($type) {
	case 'deal':
		$product_type = 'on_sale';
		break;
	case 'bestseller':
		$product_type = 'best_selling';
		break;
	case 'new':
		$product_type = 'recent_product';
		break;
	case 'toprated':
		$product_type = 'top_rate';
		break;
	case 'recommended':
		$product_type = 'recommended';
		break;
	default:
		$product_type = 'featured_product';
		break;
}

$title = denso_get_config( 'products_'.$type.'_title' );
$number = denso_get_config( 'products_'.$type.'_number', 12 );
$columns = denso_get_config( 'products_'.$type.'_columns', 4 );
$layout_type = denso_get_config( 'products_'.$type.'_layout', 'grid' );
$rows = denso_get_config( 'products_'.$type.'_rows', 1 );
$item_style = denso_get_config( 'products_'.$type.'_style', 'inner' );
$show_view_more = denso_get_config( 'products_'.$type.'_show_view_more', false );
$view_more = denso_get_config( 'products_'.$type.'_view_more' );


$categories = array();
denso_get_all_subcategories_levels($term->term_id, $parent_slug, $categories);
$loop = denso_get_products($categories, $product_type, 1, $number );
if ( $loop->have_posts() ) : ?>

	<div class="widget product-top widget-<?php echo esc_attr($type); ?>">
		<?php if ($title) { ?>
			<div class="widget-title">
				<h3><?php echo sprintf($title, $term->name); ?></h3>
				<?php if ($show_view_more) { ?>
					<a href="" class="view-more"><?php echo sprintf($view_more, $term->name); ?></a>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="widget-content">
			<div class="<?php echo esc_attr( $layout_type ); ?>-wrapper">
				<?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'loop' => $loop, 'columns' => $columns, 'rows' => $rows , 'product_item' => $item_style ) ); ?>
			</div>
		</div>
	</div>

<?php endif; ?>