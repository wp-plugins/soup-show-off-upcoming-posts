<?php
/*
Plugin Name: SOUP - Show Off Upcoming Posts
Plugin URI: http://www.doitwithwp.com/soup-plugin-show-off-your-upcoming-posts/
Description: Displays your upcoming posts to tease your readers. Gives your readers the option to subscribe via RSS and via your newsletter, if you have one.
Author: Dave Clements
Version: 1.1a
Author URI: http://www.theukedge.com
*/

/**
 * Show Off Upcoming Posts Widget Class
 */
class soup_widget extends WP_Widget {
 
 
    /** constructor */
    function soup_widget() {
        parent::WP_Widget(false, $name = 'Upcoming Posts');
    }
 
	/** @see WP_Widget::widget -- do not rename this */
	function widget($args, $instance) {
		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']); // the widget title
		$soupnumber 	= $instance['soup_number']; // the number of posts to show
		$shownews 	= ($instance['show_newsletter'] == 'on') ? 'yes' : 'no'; // whether or not to show the newsletter link
		$newsletterurl 	= $instance['newsletter_url']; // URL of newsletter signuip
 
		$args = array(
			'soup_number' 	=> $soupnumber,
			'show_newsletter'	=> $shownews,
			'newsletter_url'	=> $newsletterurl
		);
 
		// retrieves upcoming posts from database
		echo $before_widget;
		if ( $title ) { echo $before_title . $title . $after_title; }
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
			} ?>
				  </p>
				  <p>
					<a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?>">
						<img style="vertical-align:middle; margin:0 10px 0 0;" src="<?php bloginfo('wpurl'); ?>/wp-content/plugins/soup-upcoming-post/icons/rss.png" width="16px" height="16px" alt="Subscribe to <?php bloginfo('name'); ?>" />
					</a>
					Don't miss it - <strong><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?>">Subscribe by RSS.</a></strong>
				  </p>
			<?php if ($shownews)
				{ ?>
					  <p>
						Or, just <strong><a href="<?php echo $newsletterurl; ?>" title="Subscribe to <?php bloginfo ('name'); ?>">subscribe to the newsletter!</a></strong>
					  </p>
				<?php } ?>
				<?php echo $after_widget; ?>
		<?php }
 
	/** @see WP_Widget::update -- do not rename this */
	function update($new_instance, $old_instance) {
		return $new_instance;
	}
 
    /** @see WP_Widget::form -- do not rename this */
    function form($instance) {
 
        $title 		= esc_attr($instance['title']);
        $soupnumber	= esc_attr($instance['soup_number']);
        $shownews	= esc_attr($instance['show_newsletter']);
        $newsletterurl	= esc_attr($instance['newsletter_url']);
        ?>
         <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget title:'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
	<p>
          <label for="<?php echo $this->get_field_id('soup_number'); ?>"><?php _e('Number of upcoming posts to display'); ?></label>
          <input class="widefat" id="<?php echo $this->get_field_id('soup_number'); ?>" name="<?php echo $this->get_field_name('soup_number'); ?>" type="text" value="<?php echo $soupnumber; ?>" />
        </p>
	<p>
	  <label for="<?php echo $this->get_field_id('show_newsletter'); ?>"><?php _e('Show Newsletter?'); ?></label>
	  <input type="checkbox" class="checkbox" <?php checked( (bool) $instance['show_newsletter'], true ); ?> id="<?php echo $this->get_field_id('show_newsletter'); ?>" name="<?php echo $this->get_field_id('show_newsletter'); ?>" />
	</p>
	<p>
	  <label for="<?php echo $this->get_field_id('newsletter_url'); ?>"><?php _e('Newsletter URL:'); ?></label>
	  <input class="widefat" id="<?php echo $this->get_field_id('newsletter_url'); ?>" name="<?php echo $this->get_field_name('newsletter_url'); ?>" type="text" value="<?php echo $newsletterurl; ?>" />
	</p>
        <?php
    }
 
} // end class soup_widget
add_action('widgets_init', create_function('', 'return register_widget("soup_widget");'));
?>