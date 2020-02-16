<?php

/**
 * Jms Social Widget
 */
class Adiva_Social_Network extends WP_Widget {

    public function __construct() {
		$widget_ops = array(
			'classname'   => 'widget_social_network',
			'description' => esc_html__( 'Link to Facebook, Twitter, Instagram, Youtube,..', 'adiva' ),
		);
		parent::__construct( 'social-network', esc_html__('JMS - Social Network', 'adiva'), $widget_ops );
    }

    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );
        $facebook  = $instance['facebook'];
        $twitter   = $instance['twitter'];
        $instagram = $instance['instagram'];
        $gplus     = $instance['gplus'];
        $youtube   = $instance['youtube'];
        $linkedin  = $instance['linkedin'];
        $pinterest = $instance['pinterest'];

        echo '' . $args['before_widget'];

        if ( ! empty( $title ) ) {
            echo '' . $args['before_title'] . $title . $args['after_title'];
        }

        ?>
        <ul class="social-network">
            <?php if ( !empty($facebook) ) : ?>
                <li><a href="<?php echo esc_url($facebook); ?>" class="facebook"><span class="fa fa-facebook-square"></span></a></li>
            <?php endif; ?>

            <?php if ( !empty($twitter) ) : ?>
                <li><a href="<?php echo esc_url($twitter); ?>" class="twitter"><span class="fa fa-twitter-square"></span></a></li>
            <?php endif; ?>

            <?php if ( !empty($gplus) ) : ?>
                <li><a href="<?php echo esc_url($gplus); ?>" class="gplus"><span class="fa fa-google-plus-square"></span></a></li>
            <?php endif; ?>

            <?php if ( !empty($youtube) ) : ?>
                <li><a href="<?php echo esc_url($youtube); ?>" class="youtube"><span class="fa fa-youtube-square"></span></a></li>
            <?php endif; ?>

            <?php if ( !empty($instagram) ) : ?>
                <li><a href="<?php echo esc_url($instagram); ?>" class="instagram"><span class="fa fa-instagram"></span></a></li>
            <?php endif; ?>

            <?php if ( !empty($linkedin) ) : ?>
                <li><a href="<?php echo esc_url($linkedin); ?>" class="linkedin"><span class="fa fa-linkedin-square"></span></a></li>
            <?php endif; ?>

            <?php if ( !empty($pinterest) ) : ?>
                <li><a href="<?php echo esc_url($pinterest); ?>" class="pinterest"><span class="fa fa-pinterest-square"></span></a></li>
            <?php endif; ?>
        </ul>
        <?php
        echo '' . $args['after_widget'];
    }

    public function form( $instance ) {
        if ( isset( $instance[ 'title' ] ) ) {
    	   $title = $instance[ 'title' ];
    	} else {
    	   $title = esc_html__( 'Social Channels', 'adiva' );
    	}

        if ( isset( $instance['facebook'] ) ) {
            $facebook = $instance['facebook'];
        } else {
            $facebook = '#';
        }

        if ( isset( $instance['twitter'] ) ) {
            $twitter = $instance['twitter'];
        } else {
            $twitter = '#';
        }

        if ( isset( $instance['gplus'] ) ) {
            $gplus = $instance['gplus'];
        } else {
            $gplus = '#';
        }

        if ( isset( $instance['youtube'] ) ) {
            $youtube = $instance['youtube'];
        } else {
            $youtube = '';
        }

        if ( isset( $instance['instagram'] ) ) {
            $instagram = $instance['instagram'];
        } else {
            $instagram = '#';
        }

        if ( isset( $instance['linkedin'] ) ) {
            $linkedin = $instance['linkedin'];
        } else {
            $linkedin = '';
        }

        if ( isset( $instance['pinterest'] ) ) {
            $pinterest = $instance['pinterest'];
        } else {
            $pinterest = '#';
        }

        ?>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'title' )); ?>"><?php esc_html_e( 'Title', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'title' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'title' )); ?>" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'facebook' )); ?>"><?php esc_html_e( 'Facebook', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'facebook' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'facebook' )); ?>" value="<?php echo esc_attr( $facebook ); ?>">
            <em><?php esc_html_e('Link to Facebook. Leave blank if no link is needed.', 'adiva'); ?></em>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'twitter' )); ?>"><?php esc_html_e( 'Twitter', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'twitter' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'twitter' )); ?>" value="<?php echo esc_attr( $twitter ); ?>">
            <em><?php esc_html_e('Link to Facebook. Leave blank if no link is needed.', 'adiva'); ?></em>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'gplus' )); ?>"><?php esc_html_e( 'Google Plus', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'gplus' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'gplus' )); ?>" value="<?php echo esc_attr( $gplus ); ?>">
            <em><?php esc_html_e('Link to Facebook. Leave blank if no link is needed.', 'adiva'); ?></em>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'youtube' )); ?>"><?php esc_html_e( 'Youtube', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'youtube' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'youtube' )); ?>" value="<?php echo esc_attr( $youtube ); ?>">
            <em><?php esc_html_e('Link to Facebook. Leave blank if no link is needed.', 'adiva'); ?></em>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'instagram' )); ?>"><?php esc_html_e( 'Instagram', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'instagram' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'instagram' )); ?>" value="<?php echo esc_attr( $instagram ); ?>">
            <em><?php esc_html_e('Link to Facebook. Leave blank if no link is needed.', 'adiva'); ?></em>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'linkedin' )); ?>"><?php esc_html_e( 'LinkedIn', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'linkedin' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'linkedin' )); ?>" value="<?php echo esc_attr( $linkedin ); ?>">
            <em><?php esc_html_e('Link to Facebook. Leave blank if no link is needed.', 'adiva'); ?></em>
        </p>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id( 'pinterest' )); ?>"><?php esc_html_e( 'Pinterest', 'adiva' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr($this->get_field_id( 'pinterest' )); ?>" name="<?php echo esc_attr($this->get_field_name( 'pinterest' )); ?>" value="<?php echo esc_attr( $pinterest ); ?>">
            <em><?php esc_html_e('Link to Facebook. Leave blank if no link is needed.', 'adiva'); ?></em>
        </p>

        <?php
    }

    public function update( $new_instance, $old_instance ) {
        $instance = array();
	    $instance['title']     = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ): '';
        $instance['facebook']  = ( ! empty( $new_instance['facebook'] ) ) ? strip_tags( $new_instance['facebook'] ): '';
        $instance['twitter']   = ( ! empty( $new_instance['twitter'] ) ) ? strip_tags( $new_instance['twitter'] ): '';
        $instance['gplus']     = ( ! empty( $new_instance['gplus'] ) ) ? strip_tags( $new_instance['gplus'] ): '';
        $instance['youtube']   = ( ! empty( $new_instance['youtube'] ) ) ? strip_tags( $new_instance['youtube'] ): '';
        $instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? strip_tags( $new_instance['instagram'] ): '';
        $instance['linkedin']  = ( ! empty( $new_instance['linkedin'] ) ) ? strip_tags( $new_instance['linkedin'] ): '';
        $instance['pinterest'] = ( ! empty( $new_instance['pinterest'] ) ) ? strip_tags( $new_instance['pinterest'] ): '';
	    return $instance;
    }
}


function adiva_register_social() {
    register_widget( 'Adiva_Social_Network' );
}
add_action( 'widgets_init', 'adiva_register_social' );
