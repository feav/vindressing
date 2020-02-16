<?php
/**
 * Widget API: adiva_Recent_Posts class
 */

class Adiva_Recent_Posts extends WP_Widget {

	/**
	 * Sets up a new Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'                   => 'widget_recent_post_with_image',
			'description'                 => esc_html__( 'An advanced widget that gives you total control over the output of your site’s most recent Posts', 'adiva' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'recent-posts-with-image', esc_html__( 'JMS - Recent Posts', 'adiva' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_post_with_image';
	}

	/**
	 * Outputs the content for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Recent Posts widget instance.
	 */
	public function widget( $args, $instance ) {
        extract($args);

        echo '' . $before_widget;

        if( ! empty( $instance['title'] ) ) {
            echo '' . $before_title . apply_filters( 'widget_title',  $instance['title'], $instance, $this->id_base ) . $after_title;
        }

        // Get the recent posts query.
        $offset              = ( isset( $instance['offset'] ) ) ? $instance['offset'] : 0;
        $posts_per_page      = ( isset( $instance['limit'] ) ) ? $instance['limit'] : 5;
        $orderby             = ( isset( $instance['orderby'] ) ) ? $instance['orderby'] : 'date';
        $order               = ( isset( $instance['order'] ) ) ? $instance['order'] : 'DESC';
        $thumb_height        = ( isset( $instance['thumb_height'] ) ) ? $instance['thumb_height'] : 45;
        $thumb_width         = ( isset( $instance['thumb_width'] ) ) ? $instance['thumb_width'] : 45;
        $thumb         		 = ( isset( $instance['thumb'] ) ) ? $instance['thumb'] : true;
        $comment_count       = ( isset( $instance['comment_count'] ) ) ? $instance['comment_count'] : true;
        $date         		 = ( isset( $instance['date'] ) ) ? $instance['date'] : true;

        $query = array(
            'offset'              => $offset,
            'posts_per_page'      => $posts_per_page,
            'orderby'             => $orderby,
            'order'               => $order
        );

        $posts = new WP_Query( $query );

        ?>
        <?php if ( $posts->have_posts() ): ?>
            <ul class="adiva-recent-posts-list">
                <?php while ( $posts->have_posts() ) : $posts->the_post(); ?>
                    <li>
                        <?php if ( $thumb ): ?>
                            <?php if ( has_post_thumbnail() ): ?>
                                <a class="recent-posts-thumbnail" href="<?php echo esc_url( get_permalink() ); ?>"  rel="bookmark">
                                    <?php echo adiva_get_post_thumbnail( array( $thumb_width, $thumb_height ) ); ?>
                                </a>
                            <?php endif ?>
                        <?php endif ?>
                        <div class="recent-posts-info">
                            <h5 class="entry-title"><a href="<?php echo esc_url( get_permalink() ) ?>" rel="bookmark"><?php echo esc_html( get_the_title() ); ?></a></h5>

                            <?php if ( $date ): ?>
                                <?php $date = get_the_date(); ?>
                                <time class="recent-posts-time" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( $date ); ?></time>
                            <?php endif ?>

                            <?php
                            if ( $comment_count ) {
                                if ( get_comments_number() == 0 ) {
                                        $comments = esc_html__( 'No Comments', 'adiva' );
                                    } elseif ( get_comments_number() > 1 ) {
                                        $comments = sprintf( esc_html__( '%s Comments', 'adiva' ), get_comments_number() );
                                    } else {
                                        $comments = esc_html__( '1 Comment', 'adiva' );
                                    }
                                echo '<a class="recent-posts-comment" href="' . get_comments_link() . '">' . $comments . '</a>';
                            }
                            ?>
                        </div>
                    </li>

                <?php endwhile; wp_reset_postdata(); ?>

            </ul>
        <?php endif ?>

        <?php
        echo '' . $after_widget;
	}

	/**
	 * Handles updating the settings for the current Recent Posts widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {
        $instance                     = $old_instance;
        $instance['title']            = sanitize_text_field( $new_instance['title'] );
        $instance['limit']            = intval( $new_instance['limit'] );
        $instance['offset']           = intval( $new_instance['offset'] );
        $instance['order']            = stripslashes( $new_instance['order'] );
        $instance['orderby']          = stripslashes( $new_instance['orderby'] );
        $instance['date']             = isset( $new_instance['date'] ) ? (bool) $new_instance['date'] : '';
        $instance['comment_count']    = isset( $new_instance['comment_count'] ) ? (bool) $new_instance['comment_count'] : '';
        $instance['thumb']            = isset( $new_instance['thumb'] ) ? (bool) $new_instance['thumb'] : '';
        $instance['thumb_height']     = intval( $new_instance['thumb_height'] );
        $instance['thumb_width']      = intval( $new_instance['thumb_width'] );

        return $instance;
	}

	/**
	 * Outputs the settings form for the Recent Posts widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
        $defaults = array(
            'title'             => esc_html__( 'Recent Posts', 'adiva' ),
            'limit'            => 5,
            'offset'           => 0,
            'order'            => 'DESC',
            'orderby'          => 'date',
            'thumb'            => true,
            'thumb_height'     => 45,
            'thumb_width'      => 45,
            'date'             => true,
            'comment_count'    => true,
        );
        $instance = wp_parse_args( (array) $instance, $defaults );

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>">
                <?php esc_html_e( 'Title', 'adiva' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>">
                <?php esc_html_e( 'Order', 'adiva' ); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'order' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order' )); ?>" style="width:100%;">
                <option value="DESC" <?php selected( $instance['order'], 'DESC' ); ?>><?php esc_html_e( 'Descending', 'adiva' ) ?></option>
                <option value="ASC" <?php selected( $instance['order'], 'ASC' ); ?>><?php esc_html_e( 'Ascending', 'adiva' ) ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ) ; ?>">
                <?php esc_html_e( 'Orderby', 'adiva' ); ?>
            </label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'orderby' ) ) ; ?>" name="<?php echo esc_attr( $this->get_field_name( 'orderby' ) ); ?>" style="width:100%;">
                <option value="ID" <?php selected( $instance['orderby'], 'ID' ); ?>><?php esc_html_e( 'ID', 'adiva' ) ?></option>
                <option value="author" <?php selected( $instance['orderby'], 'author' ); ?>><?php esc_html_e( 'Author', 'adiva' ) ?></option>
                <option value="title" <?php selected( $instance['orderby'], 'title' ); ?>><?php esc_html_e( 'Title', 'adiva' ) ?></option>
                <option value="date" <?php selected( $instance['orderby'], 'date' ); ?>><?php esc_html_e( 'Date', 'adiva' ) ?></option>
                <option value="modified" <?php selected( $instance['orderby'], 'modified' ); ?>><?php esc_html_e( 'Modified', 'adiva' ) ?></option>
                <option value="rand" <?php selected( $instance['orderby'], 'rand' ); ?>><?php esc_html_e( 'Random', 'adiva' ) ?></option>
                <option value="comment_count" <?php selected( $instance['orderby'], 'comment_count' ); ?>><?php esc_html_e( 'Comment Count', 'adiva' ) ?></option>
                <option value="menu_order" <?php selected( $instance['orderby'], 'menu_order' ); ?>><?php esc_html_e( 'Menu Order', 'adiva' ) ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>">
                <?php esc_html_e( 'Number of posts to show', 'adiva' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' )); ?>" type="number" step="1" min="-1" value="<?php echo esc_attr( (int)$instance['limit'] ); ?>" />
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>">
                <?php esc_html_e( 'Offset', 'adiva' ); ?>
            </label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'offset' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'offset' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_attr( (int) $instance['offset'] ); ?>" />
            <small><?php esc_html_e( 'The number of posts to skip', 'adiva' ); ?></small>
        </p>

        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb' ) ); ?>" type="checkbox" <?php checked( $instance['thumb'] ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'thumb' ) ); ?>">
                <?php esc_html_e( 'Display Thumbnail', 'adiva' ); ?>
            </label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>">
                <?php esc_html_e( 'Thumbnail (width)', 'adiva' ); ?>
            </label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thumb_width' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_width' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_attr( (int)$instance['thumb_width'] ); ?>"/>
		</p>
		<p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>">
                <?php esc_html_e( 'Thumbnail (height)', 'adiva' ); ?>
            </label>
            <input id="<?php echo esc_attr( $this->get_field_id( 'thumb_height' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'thumb_height' ) ); ?>" type="number" step="1" min="0" value="<?php echo esc_attr( (int)$instance['thumb_height'] ); ?>" />
        </p>

        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'comment_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'comment_count' ) ); ?>" type="checkbox" <?php checked( $instance['comment_count'] ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'comment_count' ) ); ?>">
                <?php esc_html_e( 'Display Comment Count', 'adiva' ); ?>
            </label>
        </p>

        <p>
            <input id="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'date' ) ); ?>" type="checkbox" <?php checked( $instance['date'] ); ?> />
            <label for="<?php echo esc_attr( $this->get_field_id( 'date' ) ); ?>">
                <?php esc_html_e( 'Display Date', 'adiva' ); ?>
            </label>
        </p>
        <?php
	}
}

function adiva_register_widget_recent_posts() {
	register_widget( 'Adiva_Recent_Posts' );
}
add_action( 'widgets_init', 'adiva_register_widget_recent_posts' );
