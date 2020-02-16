<?php
class ImageSwatch_Functions {
    public static function getSwatchImage($term_id,$product_id = 0)  {
        $attachment_id = absint( get_woocommerce_term_meta( $term_id, 'thumbnail_id', true ) );
        if($product_id) {
            $product_swatch = get_post_meta($product_id,'imageswatch_images',true);
            if(is_array($product_swatch)) {
                foreach($product_swatch as $attribute => $term) {
                    if(isset($term[$term_id]) && $term[$term_id] > 0) {
                        $attachment_id = $term[$term_id];
                    }
                }
            }
        }
        $image = false;
        if((int)$attachment_id == 0) {
            $attachment_id = absint( get_woocommerce_term_meta( $term_id, 'thumbnail_id', true ) );
        }
        if((int)$attachment_id > 0) {
            $image = wp_get_attachment_thumb_url( $attachment_id );
        }
        return $image;
    }
}
