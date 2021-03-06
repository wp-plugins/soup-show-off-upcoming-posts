<?php
/*
Plugin Name: SOUP - Show Off Upcoming Posts
Plugin URI: https://github.com/theukedge/soup-show-off-upcoming-posts
Description: Displays your upcoming posts to tease your readers
Version: 1.10.0
Author: Dave Clements
Author URI: https://www.theukedge.com
License: GPLv2
*/

/*  Copyright 2015  Dave Clements  (email : https://www.theukedge.com/contact/)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/* ---------------------------------- *
 * constants
 * ---------------------------------- */

if ( !defined( 'SOUP_PLUGIN_DIR' ) ) {
	define( 'SOUP_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( !defined( 'SOUP_PLUGIN_URL' ) ) {
	define( 'SOUP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}


// Start class soup_widget //

class soup_widget extends WP_Widget {

	// Constructor //

    function soup_widget() {
    	load_plugin_textdomain('soup', false, dirname(plugin_basename(__FILE__)) . '/languages/');
        parent::__construct(false, $name = __('Upcoming Posts', 'soup'), array('description' => __('Displays your upcoming posts to entice your readers', 'soup')) );
    }

	// Extract Args //

	function widget($args, $instance) {
		extract( $args );
		$title			= apply_filters('widget_title', $instance['title']); // the widget title
		$soupnumber		= $instance['soup_number']; // the number of posts to show
		$showdate		= $instance['show_date']; // whether or not to show the scheduled post date
		$showrss		= $instance['show_rss']; // whether or not to show the RSS feed link
		$soup_cat		= $instance['soup_cat']; // exclude posts from these categories
		$poststatus		= $instance['post_status']; // the statuses of posts to show
		$posttypes		= $instance['post_types']; // the type of posts to show
		$posttypesarray	= explode(',', $posttypes); // array of post types
		$postorder		= $instance['post_order']; // Display newest first or random order
		$shownews		= isset($instance['show_newsletter']) ? $instance['show_newsletter'] : false ; // whether or not to show the newsletter link
		$newsletterurl	= $instance['newsletter_url']; // URL of newsletter signup
		$noresults		= $instance['no_results']; // Message for when there are no posts to display

	// Before widget //

		echo $before_widget;

	// Title of widget //

		if ( $title ) { echo $before_title . $title . $after_title; }

	// Widget output //

		?>
		<ul class="no-bullets">
		<?php
			global $post;
			$tmp_post = $post;
			$args = array( 'numberposts' => $soupnumber, 'no_paging' => '1', 'post_status' => $poststatus, 'order' => 'ASC', 'orderby' => $postorder, 'ignore_sticky_posts' => '1', 'category' => $soup_cat, 'post_type' => $posttypesarray );
			$myposts = get_posts( $args );
			foreach( $myposts as $post ) : setup_postdata($post); ?>
				<li>
					<?php the_title(); ?>
					<?php if($showdate) {
						echo '(' . get_the_time( get_option( 'date_format' ) ) . ')';
					} ?>
				</li>
			<?php endforeach; ?>
			<?php $post = $tmp_post; ?>
		</ul>

		<?php if (!$myposts) {
			echo $noresults;
		} ?>

		<?php if ($showrss) { ?>
		<p>
			<a href="<?php bloginfo('rss2_url') ?>" title="<?php _e('Subscribe to ', 'soup'); bloginfo('name'); ?>">
				<img style="vertical-align:middle; margin:0 10px 0 0;" src="<?php echo plugins_url( 'includes/images/rss.png' , __FILE__ ); ?>" width="16px" height="16px" alt="<?php _e('Subscribe to ', 'soup'); bloginfo('name'); ?>" />
			</a>
			<?php _e('Don\'t miss it', 'soup'); ?> - <strong><a href="<?php bloginfo('rss2_url') ?>" title="<?php _e('Subscribe to ', 'soup'); bloginfo('name'); ?>"><?php _e('Subscribe by RSS', 'soup'); ?>.</a></strong>
		</p>
		<?php } ?>

		<?php if ($shownews) { ?>
		<p>
			<?php _e('Or, just', 'soup'); ?> <strong><a href="<?php echo $newsletterurl; ?>" title="<?php _e('Subscribe to ', 'soup'); bloginfo ('name'); _e(' newsletter', 'soup'); ?>"><?php _e('subscribe to the newsletter', 'soup'); ?></a></strong>!
		</p>
		<?php }

	// After widget //

		echo $after_widget;
	}

	// Update Settings //

	function update($new_instance, $old_instance) {
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['soup_number'] = strip_tags($new_instance['soup_number']);
		$instance['show_date'] = strip_tags($new_instance['show_date']);
		$instance['show_rss'] = strip_tags($new_instance['show_rss']);
		$instance['soup_cat'] = strip_tags($new_instance['soup_cat']);
		$instance['post_status'] = strip_tags($new_instance['post_status']);
		$instance['post_types'] = strip_tags($new_instance['post_types']);
		$instance['post_order'] = strip_tags($new_instance['post_order']);
		$instance['show_newsletter'] = strip_tags($new_instance['show_newsletter']);
		$instance['newsletter_url'] = strip_tags($new_instance['newsletter_url'],'<a>');
		$instance['no_results'] = strip_tags($new_instance['no_results']);
		return $instance;
	}

	// Widget Control Panel //

	function form($instance) {

		$defaults = array(
			'title' => 'Upcoming Posts',
			'soup_number' => 3,
			'show_date' => 'off',
			'show_rss' => 'off',
			'soup_cat' => '',
			'post_status' => 'future',
			'post_types' => 'post',
			'post_order' => 'date',
			'show_newsletter' => 'off',
			'newsletter_url' => '',
			'no_results' => 'Sorry - nothing planned yet!',
		);

		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'soup'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('soup_number'); ?>"><?php _e('Number of upcoming posts to display', 'soup'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('soup_number'); ?>" name="<?php echo $this->get_field_name('soup_number'); ?>" type="text" value="<?php echo $instance['soup_number']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_date'); ?>"><?php _e('Show post date', 'soup'); ?>?</label>
			<input <?php checked( $instance['show_date'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" type="checkbox" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_rss'); ?>"><?php _e('Show RSS link', 'soup'); ?>?</label>
			<input <?php checked( $instance['show_rss'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_rss' ); ?>" name="<?php echo $this->get_field_name( 'show_rss' ); ?>" type="checkbox" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('soup_cat'); ?>"><?php _e('Categories to include (comma separated i.e. 2,19,12 - leave blank for all categories)', 'soup'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('soup_cat'); ?>" name="<?php echo $this->get_field_name('soup_cat'); ?>" type="text" value="<?php echo $instance['soup_cat']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_status'); ?>"><?php _e('Post status', 'soup'); ?>:</label>
			<select id="<?php echo $this->get_field_id('post_status'); ?>" name="<?php echo $this->get_field_name('post_status'); ?>" class="widefat" style="width:100%;">
				<option value="future,draft" <?php selected('future,draft', $instance['post_status']); ?>><?php _e('Both scheduled posts and drafts', 'soup'); ?></option>
				<option value="future" <?php selected('future', $instance['post_status']); ?>><?php _e('Scheduled posts only', 'soup'); ?></option>
				<option value="draft" <?php selected('draft', $instance['post_status']); ?>><?php _e('Drafts only', 'soup'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_types'); ?>"><?php _e('Post types to display (comma separated for multiple - e.g. post,page,event)', 'soup'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('post_types'); ?>" name="<?php echo $this->get_field_name('post_types'); ?>" type="text" value="<?php echo $instance['post_types']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('post_order'); ?>"><?php _e('Sort order', 'soup'); ?>:</label>
			<select id="<?php echo $this->get_field_id('post_order'); ?>" name="<?php echo $this->get_field_name('post_order'); ?>" class="widefat" style="width:100%;">
				<option value="date" <?php selected('date', $instance['post_order']); ?>><?php _e('Next post first', 'soup'); ?></option>
				<option value="rand" <?php selected('rand', $instance['post_order']); ?>><?php _e('Random order', 'soup'); ?></option>
			</select>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('no_results'); ?>"><?php _e('Message to display for no results', 'soup'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('no_results'); ?>" name="<?php echo $this->get_field_name('no_results'); ?>" type="text" value="<?php echo $instance['no_results']; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('show_newsletter'); ?>"><?php _e('Show Newsletter', 'soup'); ?>?</label>
			<input <?php checked( $instance['show_newsletter'], 'on' ); ?> id="<?php echo $this->get_field_id( 'show_newsletter' ); ?>" name="<?php echo $this->get_field_name( 'show_newsletter' ); ?>" type="checkbox" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('newsletter_url'); ?>"><?php _e('Newsletter URL', 'soup'); ?>:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('newsletter_url'); ?>" name="<?php echo $this->get_field_name('newsletter_url'); ?>" type="text" value="<?php echo $instance['newsletter_url']; ?>" />
		</p>
    <?php }

}

// End class soup_widget

add_action('widgets_init', create_function('', 'return register_widget("soup_widget");'));
