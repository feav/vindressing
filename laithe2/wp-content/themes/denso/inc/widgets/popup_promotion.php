<?php

class Denso_Widget_Popup_Promotion extends Apus_Widget {
    public function __construct() {
        parent::__construct(
            'apus_popup_promotion',
            esc_html__('Apus Popup Promotion Widget', 'denso'),
            array( 'description' => esc_html__( 'Show Popup Promotion', 'denso' ), )
        );
        $this->widgetName = 'popup_promotion';
        add_action('admin_enqueue_scripts', array($this, 'scripts'));
    }
    
    public function scripts() {
        wp_enqueue_script( 'apus-upload-image', APUS_THEMER_URL . 'assets/upload.js', array( 'jquery', 'wp-pointer' ), APUS_THEMER_VERSION, true );
    }

    public function getTemplate() {
        $this->template = 'popup-promotion.php';
    }

    public function widget( $args, $instance ) {
        $this->display($args, $instance);
    }
    
    public function form( $instance ) {
        $defaults = array('title' => 'Promotion', 'image' => '', 'url' => '');
        $instance = wp_parse_args( (array) $instance, $defaults );
        // Widget admin form
        ?>
        <label for="<?php echo esc_attr($this->get_field_id( 'image' )); ?>"><?php esc_html_e( 'Image:', 'denso' ); ?></label>
        <div class="screenshot">
            <?php if ( $instance['image'] ) { ?>
                <img src="<?php echo esc_url($instance['image']); ?>" style="max-width:100%" alt=""/>
            <?php } ?>
        </div>
        <input class="widefat upload_image" id="<?php echo esc_attr($this->get_field_id( 'image' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'image' )); ?>" type="hidden" value="<?php echo esc_attr($instance['image']); ?>" />
        <div class="upload_image_action">
            <input type="button" class="button add-image" value="Add">
            <input type="button" class="button remove-image" value="Remove">
        </div>
        <!-- social -->
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'url' )); ?>"><strong><?php esc_html_e('Url:', 'denso');?></strong></label>
            <input type="text" id="<?php echo esc_attr($this->get_field_id( 'url' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'url' )); ?>" value="<?php echo esc_attr( $instance['url'] ) ; ?>" class="widefat" />
        </p>
<?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['image'] = ( ! empty( $new_instance['image'] ) ) ? strip_tags( $new_instance['image'] ) : '';
        $instance['url'] = ( ! empty( $new_instance['url'] ) ) ? strip_tags( $new_instance['url'] ) : '';
        return $instance;

    }
}

register_widget( 'Denso_Widget_Popup_Promotion' );