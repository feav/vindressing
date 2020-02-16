<?php

$atts  = array_merge( array(
    'title' => '',
	'number' => 8,
	'columns' => 4,
	'tabs'	=> array(),
	'layout_type' => 'grid',
	'tab_style' => 'style1'
), $atts);
extract( $atts );

if ( empty($tabs) ) {
	return ;
}
$_id = denso_random_key(); 
?>
<div class="widget widget-categories-tabs widget-categories-tabs-<?php echo esc_attr($layout_type); ?> widget-products products">
    <div class="widget-title-wrapper">
        <?php if ( !empty($title) ) { ?>
        <h3 class="widget-title"><?php echo trim($title); ?></h3>
        <?php } ?>
        <div class="nav-tabs-selector">
        	<ul role="tablist" class="<?php echo (!empty($title))?'has-title ':''; ?> nav <?php echo esc_attr($tab_style); ?>" data-load="ajax">
                <?php $i = 0; foreach ($tabs as $tab) { ?>
                    <li<?php echo ($i == 0 ? ' class="active"' : ''); ?>>
                        <a href="#tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>">
                            <?php echo trim($tab->name); ?>
                        </a>
                    </li>
                <?php $i++; } ?>
            </ul>
        </div>
    </div>
	<div class="widget-content woocommerce">
		<div class="tab-content">
            <?php $i = 0; foreach ($tabs as $tab) :
                $type = isset($tab->type) ? $tab->type : 'recent_products';
                ?>
                <div id="tab-<?php echo esc_attr($_id);?>-<?php echo esc_attr($i); ?>" class="tab-pane <?php echo ($i == 0 ? 'active' : ''); ?>" data-loaded="<?php echo ($i == 0 ? 'true' : 'false'); ?>" data-number="<?php echo esc_attr($number); ?>" data-categories="<?php echo esc_attr($tab->category); ?>" data-columns="<?php echo esc_attr($columns); ?>" data-product_type="<?php echo esc_attr($type); ?>" data-layout_type="<?php echo esc_attr($layout_type); ?>">
                    <?php if ( $i == 0 ): ?>
                        <?php
                        	$categories = !empty($tab->category) ? array($tab->category) : array();
                        	$loop = denso_get_products( $categories, $type, 1, $number );
                    	?>
                        <?php wc_get_template( 'layout-products/'.$layout_type.'.php' , array( 'loop' => $loop, 'columns' => $columns ) ); ?>
                    <?php endif; ?>
                </div>
            <?php $i++; endforeach; ?>
        </div>
	</div>

</div>
