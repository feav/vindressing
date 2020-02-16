<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register and load the widget

class JmsAjaxSearch_Widget_NoCats extends WP_Widget {
    public function __construct() {
        add_action( 'widgets_init', array( $this, 'wpb_load_jmsajaxsearch_widget' ));
        parent::__construct(

        // Base ID of your widget
            'wpb_widget_nocats',

            // Widget name will appear in UI
            __('Ajax Search No Categories', 'jmsajaxsearch'),

            // Widget description
            array( 'description' => __( 'Widget Ajax Search No Categories', 'jmsajaxsearch' ), )
        );
    }

    public function wpb_load_jmsajaxsearch_widget() {
        register_widget( 'JmsAjaxSearch_Widget_NoCats' );
    }
    public function widget( $args, $instance ) {
        ?>
        <div id="jms_ajax_search" class="widget">
            <div class="search-wrapper adv_active">
                <form method="get" id="searchbox" class="search-form">
                    <div class="input-wrapper">
                        <input type="hidden" name="controller" value="search" />
                        <input type="hidden" name="orderby" value="position" />
                        <input type="hidden" name="orderway" value="desc" />
                        <input type="text" id="ajax_search" class="search-field jms_ajax_search" name="s" placeholder="<?php echo esc_attr_e('Search', 'jmsajaxsearch'); ?>" />
                        <button type="submit" class="search-submit"><i class="sl icon-magnifier"></i></button>
                    </div>
                </form>
                <div id="search_result" class="search_result">
                </div>
            </div>
        </div>
        <?php
    }
}
