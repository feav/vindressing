<?php

/*
* Plugin Name: Jms Image Swatch
* Plugin URI: http://joommasters.com
* Description: Image Swatch for WooCommerce Plugin
* Version: 1.0.0
* Author: Joommasters
* Author URI: http://joommasters.com
* License:     GPL2
* License URI: https://www.gnu.org/licenses/gpl-2.0.html
* Text Domain: jms-imageswatch
* Domain Path: /languages/
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
define('JMS_IMAGESWATCH_PATH',plugin_dir_path(__FILE__));
define('JMS_IMAGESWATCH_PLUGIN_URL', plugin_dir_url(__FILE__));
define('JMS_IMAGESWATCH_CSS_URL', JMS_IMAGESWATCH_PLUGIN_URL . 'assets/css');
define('JMS_IMAGESWATCH_JS_URL', JMS_IMAGESWATCH_PLUGIN_URL . 'assets/js');
define('JMS_IMAGESWATCH_PLUGIN_PATH' , dirname( plugin_basename( __FILE__ ) ));
define('JMS_IMAGESWATCH_ADMIN_PATH' , JMS_IMAGESWATCH_PLUGIN_PATH . 'admin');
require JMS_IMAGESWATCH_PATH.'classes/functions.php';

add_action( 'admin_menu', 'jms_imageswatch_admin_menu' );
function jms_imageswatch_admin_menu() {
    add_menu_page('Image Swatch Settings', 'Image Swatch', 'manage_options', 'jms-imageswatch', 'jmsimageswatch_page', 'dashicons-sos', 99);
}
function jmsimageswatch_page() {
	require 'admin/list_option.php';
}
add_action( 'add_meta_boxes', 'jms_imageswatch_add_setting' );
function jms_imageswatch_add_setting() {
	global $post;
    $wc_productfactory = new WC_Product_Factory();
    $product = $wc_productfactory->get_product($post->ID);
    if($post->post_type == 'product' && $product->is_type( 'variable' )) {
        add_meta_box( 'product-image-swatch-setting',__('Image Swatch Enable','jms-imageswatch'), 'jms_imageswatch_enable', 'product', 'side', 'low' );
    }
}

add_action( 'admin_enqueue_scripts', 'jms_imageswatch_admin_style' );
function jms_imageswatch_admin_style() {
    wp_enqueue_style( 'jmsimageswatch-admin-style', JMS_IMAGESWATCH_CSS_URL . '/admin.css' );
}

add_action( 'wp_enqueue_scripts', 'jms_imageswatch_style' );
function jms_imageswatch_style() {
    wp_enqueue_style( 'jmsimageswatch-style', JMS_IMAGESWATCH_CSS_URL . '/style.css' );
}
function jms_imageswatch_enable() {
    global $post;
    $imageswatch_allow = get_post_meta( $post->ID, '_imageswatch_allow', true );
    if($imageswatch_allow != 0) {
        $imageswatch_allow = 1;
    }
    $_pf = new WC_Product_Factory();
    $product = $_pf->get_product($post->ID);
    if($product->is_type( 'variable' )) {
    ?>
	<div class="imageswatch-setting">
        <select name="imageswatch_allow">
            <option <?php echo selected(0,$imageswatch_allow)?> value="0"><?php _e('Disable','jms-imageswatch'); ?></option>
			<option <?php echo selected(1,$imageswatch_allow)?> value="1"><?php _e('Enable','jms-imageswatch'); ?></option>
        </select>
    </div>
    <?php
    } else {
        return;
    }
}

add_action( 'woocommerce_process_product_meta', 'jms_imageswatch_save', 20, 2 );
function jms_imageswatch_save($post_id,$post) {
    $imageswatch_setting = 0;
    if(isset($_POST['imageswatch_allow'])) {
        $imageswatch_setting = (int)$_POST['imageswatch_allow'];
    }
	update_post_meta( $post_id, '_imageswatch_allow', $imageswatch_setting );
}

add_action( 'woocommerce_process_product_meta', 'jms_imageswatch_save_product_attribute_swatch', 1 );
function jms_imageswatch_save_product_attribute_swatch($post_id){
    $swatch = array();
    if(isset($_POST['product_swatch'])) {
        $swatch = $_POST['product_swatch'];
    }
    update_post_meta( $post_id, 'imageswatch_images', $swatch  );
}
add_action( 'woocommerce_product_write_panel_tabs', 'jms_imageswatch_create_admin_tab');
function jms_imageswatch_create_admin_tab() {
    global $post;
    $imageswatch_allow = get_post_meta( $post->ID, '_imageswatch_allow', true );
    if($imageswatch_allow != 0) {?>
        <li class="imageswatch_tab">
            <a href="#imageswatch_tab_data_ctabs">
                <?php _e('Variations Thumbnail', 'jms-imageswatch'); ?>
            </a>
        </li>
    <?php
    }
}
add_action( 'woocommerce_product_data_panels', 'jms_imageswatch_create_admin_tab_content');
function jms_imageswatch_create_admin_tab_content() {
    global $woocommerce, $post, $thepostid, $wc_product_attributes;
    $imageswatch_allow = get_post_meta( $post->ID, '_imageswatch_allow', true );
    if($imageswatch_allow != 0) {
        $attributes  = maybe_unserialize( get_post_meta( $thepostid, '_product_attributes', true ) );
		$variation = wc_get_product( $thepostid );
		$attributes = $variation->get_attributes();
        ?>
        <div id="imageswatch_tab_data_ctabs" class="panel woocommerce_options_panel">
            <?php foreach($attributes as $attr => $val): ?>

            <?php if($val['variation'] == 1 && isset($attributes[$attr])): ?>
                <?php
                    $attribute     = $attributes[ $attr ];
                    $position      = empty( $attribute['position'] ) ? 0 : absint( $attribute['position'] );
                    $taxonomy      = '';
                    $metabox_class = array();

                    if ( $attribute['is_taxonomy'] ) {
                        $taxonomy = $attribute['name'];

                        if ( ! taxonomy_exists( $taxonomy ) ) {
                            continue;
                        }

                        $attribute_taxonomy = $wc_product_attributes[ $taxonomy ];
                        $metabox_class[]    = 'taxonomy';
                        $metabox_class[]    = $taxonomy;
                        $attribute_label    = wc_attribute_label( $taxonomy );
                        $all_terms = get_terms( $taxonomy, 'orderby=name&hide_empty=0' );

                    }
                    $product_swatch = get_post_meta($post->ID,'imageswatch_images',true);
                ?>
                    <?php if ( $attribute['is_taxonomy'] ): ?>
                    <div class="options_group swatch_group">
                        <h3><strong class="attribute_name"><?php echo sanitize_text_field($attribute_label); ?></strong></h3>
                        <?php foreach ( $all_terms as $term ):  ?>
                            <?php
                                if (isset($product_swatch[esc_attr($taxonomy)][$term->term_id]) && $thumbnail_id =  $product_swatch[esc_attr($taxonomy)][$term->term_id] ) {

                                    $image = wp_get_attachment_thumb_url( $thumbnail_id );

                                } else {
                                    $image = wc_placeholder_img_src();
                                }
                            ?>
                        <?php if(has_term( absint( $term->term_id ),$taxonomy,$thepostid)): ?>
                        <span class="form-field">
                            <div  class="imageswatch-variation">
                                <label><strong><?php echo sanitize_text_field($term->name) ; ?></strong></label>
                                <img src="<?php echo esc_url( $image ); ?>" width="60px" height="60px">
                                <input type="hidden" name="is_attribute" value="1">
                                <input type="hidden" class="imageswatch_thumb_id" value="<?php echo isset($product_swatch[esc_attr($taxonomy)][$term->term_id])?$product_swatch[esc_attr($taxonomy)][$term->term_id]:''; ?>" name="product_swatch[<?php echo esc_attr($taxonomy);?>][<?php echo absint( $term->term_id ); ?>]" />
                                <a class="button imageswatch_add_image"><?php _e( 'Add Image', 'jms-imageswatch' ); ?></a>
                                <a style="<?php if($image == wc_placeholder_img_src() ):?>display: none;<?php endif; ?>" class="imageswatch_remove_image button">X</a>
                            </div>
                        </span>
                        <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                    <script type="text/javascript">
                        (function($) {
                            "use strict";
                            $( document ).on( 'click', '.imageswatch_add_image', function( event ) {
                                var imageswatch_thumb_id = $(this).closest('.imageswatch-variation').find('input.imageswatch_thumb_id').eq(0);
                                var variation_img = $(this).closest('.imageswatch-variation').find('img');
                                var imageswatch_remove_image = $(this).closest('.imageswatch-variation').find('.imageswatch_remove_image');
                                var wp_media;
                                if(!$(this).hasClass('open')) {
                                    $(this).addClass('open');
                                    event.preventDefault();
                                    if ( wp_media ) {
                                        wp_media.open();
                                        return;
                                    }

                                    wp_media = wp.media.frames.downloadable_file = wp.media({
                                        title: '<?php _e( "Choose an image for variation", "jms-imageswatch" ); ?>',
                                        button: {
                                            text: '<?php _e( "Add Image", "jms-imageswatch" ); ?>'
                                        },
                                        multiple: false
                                    });

                                    wp_media.on( 'select', function() {
                                        var attachment = wp_media.state().get( 'selection' ).first().toJSON();
                                        imageswatch_thumb_id.val( attachment.id );
                                        variation_img.attr( 'src', attachment.url );
                                        imageswatch_remove_image.show();
                                        $(this).removeClass('open');
                                    });
                                    wp_media.on( 'close', function() {
                                        $('.imageswatch_add_image').removeClass('open');
                                    });
                                    wp_media.open();
                                }

                            });

                            $( document ).on( 'click', '.imageswatch_remove_image', function() {
                                var imageswatch_thumb_id = $(this).closest('.imageswatch-variation').find('input.imageswatch_thumb_id').eq(0);
                                var variation_img = $(this).closest('.imageswatch-variation').find('img');
                                var imageswatch_remove_image = $(this).closest('.imageswatch-variation').find('.imageswatch_remove_image');
                                variation_img.attr( 'src', '<?php echo esc_js( wc_placeholder_img_src() ); ?>' );
                                imageswatch_thumb_id.val( '' );
                                imageswatch_remove_image.hide();
                                return false;
                            });
                        } )( jQuery );
                    </script>
                    <?php endif; ?>
            <?php endif; ?>
            <?php endforeach; ?>

        </div>
        <?php
    }
}
add_filter('wc_get_template','jms_imageswatch_get_template',10,5);
function jms_imageswatch_get_template($located, $template_name, $args, $template_path, $default_path) {
    if($template_name == 'single-product/add-to-cart/variable.php') {
        global $post;
        $imageswatch_allow = get_post_meta( $post->ID, '_imageswatch_allow', true );
        if($imageswatch_allow != 0) {
            if (file_exists(get_stylesheet_directory() . '/woocommerce/' . $template_name)) {
                return get_stylesheet_directory().'/woocommerce/'. $template_name;
            } else {
                global $woocommerce;
                return JMS_IMAGESWATCH_PATH . 'templates/' . $template_name;
            }
        }

    } elseif($template_name == 'cart/cart-item-data.php') {
        if (file_exists(get_stylesheet_directory() . '/jms-imageswatch/' . $template_name)) {
            return get_stylesheet_directory().'/jms-imageswatch/'. $template_name;
        } else {
            global $woocommerce;
            return JMS_IMAGESWATCH_PATH . 'templates/' . $template_name;
        }
    }
    return $located;
}


if(!function_exists('jms_imageswatch_header_script')) {
    function jms_imageswatch_header_script() {
        echo '
        <script type="text/javascript" >
            var imageswatch_ajax_url = "'.admin_url('admin-ajax.php').'";
        </script>';
    }
}
add_action( 'wp_head', 'jms_imageswatch_header_script' );
add_action('wp_ajax_imageswatch_load_images', 'jms_imageswatch_loadimages');
add_action("wp_ajax_nopriv_imageswatch_load_images", 'jms_imageswatch_loadimages');
function jms_imageswatch_loadimages() {
	$productId = esc_attr($_POST['product_id']);
    $option = esc_attr($_POST['option']);
    $_pf = new WC_Product_Factory();
    $product = $_pf->get_product($productId);
    $attributes = $product->get_attributes();
    $images = '';
    $thumb = '';
    $attachment_ids = array();

    if($thumb !='') {
        $images .= '<div class="thumbnails thumnails-'.$option.' columns-'.$columns.'">'.$thumb.'</div>';
    }
}
if(!function_exists('jms_imageswatch_woocommerce_get_item_data')) {
    function jms_imageswatch_woocommerce_get_item_data( $item_data, $cart_item) {
        $new_data = array();
        $product_id = $cart_item['product_id'];
        $_pf = new WC_Product_Factory();
        $_product = $_pf->get_product($product_id);
        $allow = false;
        if($_product->get_type() == 'variable')
        {
            $imageswatch_allow = get_post_meta( $product_id, '_imageswatch_allow', true );
            if($imageswatch_allow != 0) {
                $allow = true;
            }
        }
        if($allow) {
            foreach($item_data as $val) {
                $tmp_data = $val;
                $attribute = esc_attr( 'pa_'.strtolower($val['key']) ) ;

                $terms = wc_get_product_terms( $product_id, $attribute, array( 'fields' => 'all' ) );
                $image = false;
                foreach($terms as $t)
                {
                    if($t->name == $val['value']) {
                        $term_id = $t->term_id;
                        $image = ImageSwatch_Functions::getSwatchImage($term_id, $product_id);
                    }
                }
                $tmp_data['image'] = $image;
                $new_data[] = $tmp_data;
            }
            return $new_data;
        } else {
            return $item_data;
        }

    }
}
add_filter( 'woocommerce_get_item_data','jms_imageswatch_woocommerce_get_item_data',10,2 );
$productbox_pos = get_option('jms_imageswatch_productbox_position');
if($productbox_pos == 'after-title') {
	add_action('woocommerce_after_shop_loop_item_title','jms_imageswatch_productbox_load', 30);
} elseif($productbox_pos == 'before-title') {
	add_action('woocommerce_before_shop_loop_item_title','jms_imageswatch_productbox_load', 30);
} elseif($productbox_pos == 'before-product-box') {
	add_action('woocommerce_before_shop_loop_item', 'jms_imageswatch_productbox_load', 30);
} elseif($productbox_pos == 'after-product-box') {
	add_action('woocommerce_after_shop_loop_item', 'jms_imageswatch_productbox_load', 30);
}
function jms_imageswatch_productbox_load() {
    global $post;
    $_pf = new WC_Product_Factory();
    $product = $_pf->get_product($post->ID);
    $attributes = $product->get_attributes();
	$swatch_attribute = get_option('jms_imageswatch_productbox_attribute');
	$productbox_number = get_option('jms_imageswatch_productbox_number');
    if($product->is_type( 'variable' )) {

        $tmp = get_post_meta( $post->ID, '_imageswatch_allow', true );
        if(isset($attributes[$swatch_attribute]) && $tmp != 0) {

            $html = '';
            $tmp1 = array();
            if( $product->is_type( 'variable' )) {
                $variations = $product->get_available_variations();

                foreach($variations as $variation) {
                    $id = $variation['variation_id'];
                    if(isset($variation['attributes']['attribute_'.$swatch_attribute]) ) {

                        if( isset( $variation['image']['src']) && $variation['image']['src'] != '') {
                            $option = $variation['attributes']['attribute_'.$swatch_attribute];
                            $vari = new WC_Product_Variation($id);
                            $tmp1[$option] = $vari->get_image_id();
                        }
                    }
                }
            }
			$tmp = $tmp1;
            $attribute = $attributes[$swatch_attribute];
            $slug = array();
            $ids = array();
            if ( $attribute['is_taxonomy'] ) {
                $values = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'names' ) );
                $slug = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'slugs' ));
                $ids = wc_get_product_terms( $product->get_id(), $attribute['name'], array( 'fields' => 'ids' ));
            } else {
                $values = array_map( 'trim', explode( WC_DELIMITER, $attribute['value'] ) );
            }
            if (!empty($values)) {
                $html .= '<div class="imageswatch-list-variations"><ul>';
                $slug = array_values($slug);
                $ids = array_values($ids);
				$i = 1;
                foreach ($values as $key => $value) {
                    $image = '';
                    if(isset($slug[$key])) {
                        $sl = $slug[$key];
                        if(isset($tmp[$sl])) {
                            $attachment_ids = array_filter(explode(',',$tmp[$sl]));
                            if(!empty($attachment_ids)) {
                                $post_thumbnail_id = (int)$attachment_ids[0];
                                $size = apply_filters( 'post_thumbnail_size', 'shop_catalog' );
                                if ( $post_thumbnail_id ) {
                                    $tmp_image = wp_get_attachment_image_src($post_thumbnail_id, $size);
                                    $image = $tmp_image[0];
                                }
                            }
                        }
                        $sw_image = ImageSwatch_Functions::getSwatchImage($ids[$key], $product->get_id());
                        if(!$sw_image) {
                            $thumbnail_id = absint( get_woocommerce_term_meta( $ids[$key], 'thumbnail_id', true ) );
                            $sw_image = wp_get_attachment_thumb_url( $thumbnail_id );
                        }
                        $style = '';
                        if ( $sw_image ) {
                            $style = 'background-image: url('.$sw_image.'); background-size: cover; text-indent: -999em;';
                        }
						$product_variation_link = esc_url(get_permalink( $product->get_id() ).'?attribute_'.$swatch_attribute.'='.$slug[$key]);
                        $html .= '<li class="imageswatch-product-variation"><a href="'.$product_variation_link.'" data-thumb="'.$image.'" class="swatch-variation" style="'.$style.'">'.$value.'</a></li>';
                    } else {
                        $html .= '<li><a href="'.$product_variation_link.'">'.$value.'</a></li>';
                    }
					if( $i >= $productbox_number) break;
					$i++;
                }
                $html .= '</ul></div>';
            }
            echo balanceTags($html);
        }
    }
}
