<?php

/*-----------------------------------------------------------------------------------

	Plugin Name: Core Posts Widget
	Plugin URI: http://themeshash.com/wordpress-plugins/
	Description: A widget that displays recent posts from the blog
	Version: 1.0
	Author: Muhammad Faisal
	Author URI: http://themeshash.com/

-----------------------------------------------------------------------------------*/


// Add function to widgets_init that'll load our widget
add_action( 'widgets_init', 'th_recent_posts_widgets' );

// Register widget
function th_recent_posts_widgets() {
	register_widget( 'th_recent_posts_widget' );
}

// Widget class
class th_recent_posts_widget extends WP_Widget {


	#-------------------------------------------------------------------------------#
	#  Widget Setup
	#-------------------------------------------------------------------------------#
	
	function __construct() {

		// Widget settings
		$widget_ops = array(
			'classname' => 'th_recent_posts_widget',
			'description' => esc_html__('A widget that displays recent posts from the blog', 'themeshash')
		);

		// Widget control settings
		$control_ops = array(
			'width' => 300,
			'height' => 350,
			'id_base' => 'th_recent_posts_widget'
		);

		// Create the widget
		parent::__construct( 'th_recent_posts_widget', esc_html__('Recent Posts', 'themeshash'), $widget_ops, $control_ops );
		
	}


	#-------------------------------------------------------------------------------#
	#  Display Widget
	#-------------------------------------------------------------------------------#
	
	public function widget( $args, $instance ) {
		extract( $args );

		// Our variables from the widget settings
		$title = apply_filters('widget_title', $instance['title'] );
		$category = $instance['category'];
		$no_posts = $instance['no_posts'];

		// Custom Query
		$query = array('showposts' => 3, 'nopaging' => 0, 'post_status' => 'publish', 'ignore_sticky_posts' => 1);
		$loop = new WP_Query($query);

		// Before widget (defined by theme functions file)
		echo wp_kses_post( $before_widget );

		// Display the widget title if one was input
		if ( $title )
			echo wp_kses_post( $before_title . $title . $after_title );

		?>
	     
		<div class="widget-content">
		    <div class="widget-recent-post">
		        <ul>

		        	<?php if ($loop->have_posts()) : while ($loop->have_posts()) : $loop->the_post(); ?>

			            <li>
			                <div class="left">
			                    <div class="recent-post-img">
									<?php if (  has_post_thumbnail() ) { ?>
										<a href="<?php echo get_permalink() ?>" rel="bookmark">
											<?php the_post_thumbnail('misc-thumb', array('class' => 'side-item-thumb')); ?>
										</a>
									<?php } else { ?>
			                            <div class="recent-post-alt">
			                                <div class="recent-post-alt-body"><i class="fa fa-file-text-o"></i></div>
			                            </div>
									<?php } ?>
			                    </div>
			                </div>
			                <div class="right">
			                    <div class="recent-post-title">
			                        <a href="<?php echo get_permalink() ?>"><?php the_title(); ?></a>
			                    </div>
			                    <div class="recent-post-date"><?php the_time( get_option('date_format') ); ?></div>
			                </div>
			                <div class="clearfix"></div>
			            </li>

					<?php endwhile; ?>
					<?php wp_reset_query(); ?>
					<?php endif; ?>
						            
		        </ul>
		    </div>
		</div>

		<?php

		// After widget (defined by theme functions file)
		echo wp_kses_post( $after_widget );
		
	}

	#-------------------------------------------------------------------------------#
	#  Update Widget
	#-------------------------------------------------------------------------------#
	
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		// Strip tags to remove HTML (important for text inputs)
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$instance['no_posts'] = strip_tags( $new_instance['no_posts'] );

		// No need to strip tags

		return $instance;
	}

	#-------------------------------------------------------------------------------#
	#  Widget Settings
	#-------------------------------------------------------------------------------#
		 
	public function form( $instance ) {

		// Set up some default widget settings
		$defaults = array(
			'title' => 'Recent Posts',
			'category' => '',
			'no_posts' => '',
		);
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e('Title:', 'themeshash') ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

		<!-- Name: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e('Name:', 'themeshash') ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['category'] ); ?>" />
		</p>

		<!-- No. Of Posts: Text Input -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'no_posts' ) ); ?>"><?php esc_html_e('No. Of Posts:', 'themeshash') ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'no_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'no_posts' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['no_posts'] ); ?>" />
		</p>

		<?php
		}
	}