<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Register and load the widget

class JmsAjaxSearch_Widget extends WP_Widget {
    public $params;
	public function __construct() {
        $this->params = new JmsAjaxSearch_Params();
		add_action( 'widgets_init', array( $this, 'wpb_load_jmsajaxsearch_widget' ));
		parent::__construct(

			// Base ID of your widget
			'wpb_widget',

			// Widget name will appear in UI
			__('Ajax Search Widget', 'jmsajaxsearch'),

			// Widget description
			array( 'description' => __( 'Widget Ajax Search Show', 'jmsajaxsearch' ), )
		);
	}

	function wpb_load_jmsajaxsearch_widget() {
	    register_widget( 'JmsAjaxSearch_Widget' );
	}

	public function widget( $args, $instance ) {
		$search_type = $this->params->get_param( 'search_type', 'product');
	?>
		<div id="jms_ajax_search" class="widget with-categories">
			<div class="search-wrapper adv_active">
				<form method="get" id="searchbox" class="search-form">
					<div class="input-wrapper">
						<input type="hidden" name="controller" value="search" />
						<input type="hidden" name="orderby" value="position" />
						<input type="hidden" name="orderway" value="desc" />
						<input type="text" id="ajax_search" name="s" class="search-field jms_ajax_search" placeholder="<?php echo esc_attr_e('Search', 'jmsajaxsearch'); ?>" />
                        <button type="submit" class="search-submit"><i class="sl icon-magnifier"></i></button>
					</div>
					<?php
						if ($search_type == 'product') {
					?>
						<div class="select-box">
							<?php
                            $helper = new JmsHelper();
                            $helper->get_categories_tree();
                            ?>
						</div>
					<?php
						}
					?>
				</form>
				<div id="search_result" class="search_result">
				</div>
			</div>
		</div>
	<?php
	}
} ?>
