<?php
extract( $args );
extract( $instance );
$title = apply_filters('widget_title', $instance['title']);

if ( $title ) {
    echo ($before_title)  . trim( $title ) . $after_title;
}
$bcol = 12/(int)$columns;
if ($columns == 5) {
    $bcol = 'cus-5';
}
?>

<div class="instagram-widget">
<?php
    if ( $username != '' ) {
        $media_array = $this->scrape_instagram( $username );

        if ( is_wp_error( $media_array ) ) {

            echo wp_kses_post( $media_array->get_error_message() );

        } else {

            // filter for images only?
            if ( $images_only = apply_filters( 'apus_themer_instagram_widget_images_only', FALSE ) ) {
                $media_array = array_filter( $media_array, array( $this, 'images_only' ) );
            }

            // slice list down to required number
            $media_array = array_slice( $media_array, 0, $number );
            ?>
            <div class="row instagram-pics">
                <?php
                foreach ( $media_array as $item ) {
                    echo '<div class="col-md-'.esc_attr($bcol).' col-xs-4">';
                    echo '<a href="'. esc_url( $item['link'] ) .'" target="'. esc_attr( $target ) .'"><img src="'. esc_url( $item[$size] ) .'"  alt="'. esc_attr( $item['description'] ) .'" title="'. esc_attr( $item['description'] ).'"/></a>';
                    echo '</div>';
                }
                ?>
            </div>
            <?php
        }
    }
?>
</div>