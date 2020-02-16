<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $product;
$attribute_keys = array_keys( $attributes );

if(!$imageswatch_attrs = explode(",", get_option('jms_imageswatch_attributes')))
{
    $imageswatch_attrs = array();
}
$default = array();
$allow_swatch = false;
foreach($imageswatch_attrs as $s) {
    if($s != '') {
        $allow_swatch = true;
    }
}
do_action( 'woocommerce_before_add_to_cart_form' ); ?>
<form class="variations_form cart" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo esc_attr( json_encode( $available_variations ) ) ?>">
	<?php do_action( 'woocommerce_before_variations_form' ); ?>

	<?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
		<p class="stock out-of-stock"><?php _e( 'This product is currently out of stock and unavailable.', 'woocommerce' ); ?></p>
	<?php else : ?>
		<div class="variations">
				<?php foreach ( $attributes as $attribute_name => $options ) :?>
					<div class="attribute-wrap">
						<h4 class="attribute-name"><?php echo wc_attribute_label( $attribute_name ); ?></h4>
						<div class="attribute-variations">
                            <div <?php if(isset($imageswatch_attrs) && in_array($attribute_name, $imageswatch_attrs) && taxonomy_exists( $attribute_name )): ?>style="display: none;" <?php endif; ?>>
							<?php
								$selected = isset( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) ? wc_clean( $_REQUEST[ 'attribute_' . sanitize_title( $attribute_name ) ] ) : $product->get_variation_default_attribute( $attribute_name );
								wc_dropdown_variation_attribute_options( array( 'options' => $options, 'attribute' => $attribute_name, 'product' => $product, 'selected' => $selected ) );
							?>
                            </div>
							<?php $name =  $attribute_name; if(in_array($name, $imageswatch_attrs)): ?>
                                <div class="attribute-variations-content" >
                                    <div id="<?php echo esc_attr( sanitize_title( $name ) ); ?>" class="imageswatch-variations">
                                        <?php

                                        if ( is_array( $options ) ) {

                                            if ( isset( $_REQUEST[ 'attribute_' . sanitize_title( $name ) ] ) ) {
                                                $selected_value = $_REQUEST[ 'attribute_' . sanitize_title( $name ) ];
                                            } elseif ( isset( $selected_attributes[ sanitize_title( $name ) ] ) ) {
                                                $selected_value = $selected_attributes[ sanitize_title( $name ) ];
                                                $default[sanitize_title( $name )] = $selected_value;
                                            } else {
                                                $selected_value = '';
                                            }

                                            // Get terms if this is a taxonomy - ordered
                                            if ( taxonomy_exists( $name ) ) {
                                                $terms = wc_get_product_terms( $product->get_id(), $name, array( 'fields' => 'all' ) );
                                                foreach ( $terms as $term ) {
                                                    if ( ! in_array( $term->slug, $options ) ) {
                                                        continue;
                                                    }
                                                    $class = ( sanitize_title( $selected_value ) == sanitize_title( $term->slug ) ) ? 'selected':'';

                                                    $thumbnail_id = absint( get_woocommerce_term_meta( $term->term_id, 'thumbnail_id', true ) );
                                                    $image = ImageSwatch_Functions::getSwatchImage($term->term_id,$product->get_id());
                                                    if ( $image ) {
														$style = 'background-image: url('.$image.'); background-size: cover; text-indent: -999em;';
                                                    } else {
                                                        $style = '';
                                                    }
                                                    echo '<a option-value="' . esc_attr( $term->slug ) . '" data-toggle="tooltip" title="'.$term->name.'" class="imageswatch-variation ' . $class . '  ' . esc_attr( $term->slug ) . '" ><span style="'.$style.'" >' . apply_filters( 'woocommerce_variation_option_name', $term->name ) . '</span></a>';
                                                }
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php echo end( $attribute_keys ) === $attribute_name ? '<a class="reset_variations" href="#">' . __( 'Clear', 'woocommerce' ) . '</a>' : ''; ?>
						</div>
					</div>
		        <?php endforeach;?>
		</div>

		<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

		<div class="single_variation_wrap" style="display:none;">
			<?php
				/**
				 * woocommerce_before_single_variation Hook
				 */
				do_action( 'woocommerce_before_single_variation' );

				/**
				 * woocommerce_single_variation hook. Used to output the cart button and placeholder for variation data.
				 * @since 2.4.0
				 * @hooked woocommerce_single_variation - 10 Empty div for variation data.
				 * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
				 */
				do_action( 'woocommerce_single_variation' );

				/**
				 * woocommerce_after_single_variation Hook
				 */
				do_action( 'woocommerce_after_single_variation' );
			?>
		</div>

		<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php endif; ?>

	<?php do_action( 'woocommerce_after_variations_form' ); ?>

	<script type="text/javascript">
	(function($) {
        "use strict";
        <?php if ( ! empty( $available_variations )) : ?>
        function loadImages(productId,option) {
            if( !$('#product-'+productId+' .images').hasClass('processing')) {
                $.ajax({
                    type: 'post',
                    url: imageswatch_ajax_url,
                    data: {action:'imageswatch_load_images',product_id:productId,option:option},
                    dataType: 'html',
                    beforeSend:function(){
                        $('#product-'+productId+' .images').addClass('processing');
                    },
                    success:function(data){
                        $('#product-'+productId+' .images').removeClass('processing');
                    }
                });
            }
		}
		$(document).ready(function(){
			<?php if ( ! empty( $available_variations ) && $allow_swatch ) : ?>
            var attributes = [<?php foreach ( $attributes as $name => $options ): ?> '<?php echo esc_attr( sanitize_title( $name ));?>', <?php endforeach; ?>];
            var $variation_form = $('.variations_form');
            var $product_variations = $variation_form.data( 'product_variations' );
            $('a.imageswatch-variation').on('click touchstart',function(event){
                if($(this).hasClass('disable')) {
                    return;
                }
				var current = $(this);
                if(!current.hasClass('selected')) {
                    var value = current.attr('option-value');
                    var selector_name = current.closest('div').attr('id');
                    if(selector_name == attributes[0]) {
                        $('div.imageswatch-variations a').each(function(){
                            $(this).removeClass('selected');
                        });
                    }
                    if($("select#"+selector_name).find('option[value="'+value+'"]').length > 0) {
                        $(this).closest('div').children('a').each(function(){
                            $(this).removeClass('selected');
							$(this).removeClass('disable');
                        });
						if(!$(this).hasClass('selected')) {
                            current.addClass('selected');

                            $("select#"+selector_name).val(value);
                            $variation_form.trigger( 'wc_variation_form' );
                            $variation_form.trigger( 'check_variations' );

                        }
                    } else {
                        current.addClass('disable');
                    }
                }

            });
            $variation_form.on('wc_variation_form', function() {
                $( this ).on( 'click', '.reset_variations', function( event ) {
                    $('div.imageswatch-variations a').each(function(){
                        $(this).removeClass('selected');
                    });
                    loadImages('<?php echo (int)$product->get_id(); ?>','null');
                });
            });
            $variation_form.on('reset_data',function(){
                $variation_form.find( '.variations select').each(function(){
                    if($(this).val() == '') {
                        var id = $(this).attr('id');
                        $('div#'+id+' a').removeClass('selected');
                    }
                });
            });
            <?php endif; ?>
			<?php if(get_option('jms_imageswatch_tooltips')): ?>
            $('[data-toggle="tooltip"]').tooltip();
            <?php endif; ?>
		});
        <?php endif; ?>
	} )( jQuery );
	</script>
</form>

<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>
