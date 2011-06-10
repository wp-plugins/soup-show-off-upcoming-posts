<?php
/*
Plugin Name: SOUP - Show Off Upcoming Posts
Plugin URI: http://www.doitwithwp.com/soup-plugin-show-off-your-upcoming-posts/
Description: Displays your upcoming posts to tease your readers
Author: Dave Clements
Version: 1.2a
Author URI: http://www.theukedge.com
*/

	// Start class soup_widget //
 
	class soup_widget extends WP_Widget {
 
	// Constructor //
    
		function soup_widget() {
			$widget_ops = array( 'classname' => 'soup_widget', 'description' => 'Displays your upcoming posts to tease your readers' ); // Widget Settings
			$control_ops = array( 'id_base' => 'soup_widget' ); // Widget Control Settings
			$this->WP_Widget( 'soup_widget', 'Upcoming', $widget_ops, $control_ops ); // Create the widget
		}

	// Extract Args //

		function widget($args, $instance) {
			extract( $args );
			$title 		= apply_filters('widget_title', $instance['title']); // the widget title
			$soupnumber 	= $instance['soup_number']; // the number of posts to show
			$shownews 	= isset($instance['show_newsletter']) ? $instance['show_newsletter'] : false ; // whether or not to show the newsletter link
			$newsletterurl 	= $instance['newsletter_url']; // URL of newsletter signup

	// Before widget //
		
			echo $before_widget;
		
	// Title of widget //
		
			if ( $title ) { echo $before_title . $title . $after_title; }
		
	// Widget output //
		
			$soupquery = new WP_Query(array('posts_per_page' => $soupnumber, 'nopaging' => 0, 'post_status' => 'future', 'order' => 'ASC'));
			if ($soupquery->have_posts()) {
			while ($soupquery->have_posts()) : $soupquery->the_post();
			$do_not_duplicate = $post->ID;
			?>
				<ul>
        				<li>
        					<?php the_title(); ?>
        				</li>
				</ul>
			<?php endwhile;
			}
			echo '</p>';
			echo '<p>';
			echo '<a href="'; bloginfo('rss2_url'); echo '" title="Subscribe to '; bloginfo('name'); echo '">';
			echo '<img style="vertical-align:middle; margin:0 10px 0 0;" src="'; bloginfo('wpurl'); echo '/wp-content/plugins/soup-upcoming-post/icons/rss.png" width="16px" height="16px" alt="Subscribe to '; bloginfo('name'); echo '" />';
			echo '</a>';
			echo 'Don\'t miss it - <strong><a href="'; bloginfo('rss2_url'); echo '" title="Subscribe to '; bloginfo('name'); echo '">Subscribe by RSS.</a></strong>';
			echo '</p>';
		
			if ($shownews) {
			echo '<p>';
			echo 'Or, just <strong><a href="'; echo $newsletterurl; echo '" title="Subscribe to '; bloginfo ('name'); echo '">subscribe to the newsletter</a></strong>!';
			echo '</p>';
			}
				
	// After widget //
		
			echo $after_widget;
		}
		
	// Update Settings //
 
		function update($new_instance, $old_instance) {
			$instance['title'] = strip_tags($new_instance['title']);
			$instance['soup_number'] = strip_tags($new_instance['soup_number']);
			$instance['show_newsletter'] = ($new_instance['show_newsletter']);
			$instance['newsletter_url'] = ($new_instance['newsletter_url']);
			return $instance;
		}
 
	// Widget Control Panel //
	
		function form($instance) {

		$defaults = array( 'title' => 'Upcoming Posts', 'soup_number' => 3, 'show_newsletter' => false, newsletter_url => '' );
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
		
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('soup_number'); ?>"><?php _e('Number of upcoming posts to display'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('soup_number'); ?>" name="<?php echo $this->get_field_name('soup_number'); ?>" type="text" value="<?php echo $instance['soup_number']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_newsletter'); ?>"><?php _e('Show Newsletter?'); ?></label>
			<input type="checkbox" class="checkbox" <?php checked( $instance['show_newsletter'], 'on' ); ?> id="<?php echo $this->get_field_id('show_newsletter'); ?>" name="<?php echo $this->get_field_name('show_newsletter'); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('newsletter_url'); ?>"><?php _e('Newsletter URL:'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('newsletter_url'); ?>" name="<?php echo $this->get_field_name('newsletter_url'); ?>" type="text" value="<?php echo $instance['newsletter_url']; ?>" />
		</p>
        <?php }
 
}

// End class soup_widget

add_action('widgets_init', create_function('', 'return register_widget("soup_widget");'));
?>