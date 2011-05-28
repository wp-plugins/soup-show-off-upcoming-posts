<?php
/*
Plugin Name: Upcoming Posts Widget
Plugin URI: http://www.doitwithwordpress.com
Description: Displays your upcoming posts to tease your readers
Author: Dave Clements
Version: 1.01
Author URI: http://www.theukedge.com
*/
 
 
class UpcomingPostWidget extends WP_Widget
{
  function UpcomingPostWidget()
  {
    $widget_ops = array('classname' => 'UpcomingPostWidget', 'description' => 'Displays your upcoming posts' );
    $this->WP_Widget('UpcomingPostWidget', 'Upcoming Post', $widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
    $title = $instance['title'];
?>
  <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    return $instance;
  }
  
  function widget($args, $instance)
  {
    extract($args, EXTR_SKIP);
 
    echo $before_widget;
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
 
    if (!empty($title))
      echo $before_title . $title . $after_title;;
 
    // WIDGET CODE GOES HERE

   ?>
<p>
	<div class=".box.widget_upcoming">
		<?php
			$my_query = new WP_Query('post_status=future&order=ASC&showposts=1');
			if ($my_query->have_posts()) {
			while ($my_query->have_posts()) : $my_query->the_post();
			$do_not_duplicate = $post->ID;
		?>
			<ul>
        			<li>
        				<?php the_title(); ?>
        			</li>
			</ul>
		<?php endwhile;
		} ?>
	</div>
</p>
<p>
	<div class=".box.widget_upcoming">
		<a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?>">
			<img style="vertical-align:middle; margin:0 10px 0 0;" src="http://www.doitwithwordpress.com/wp-content/themes/Gadget/images/icon-rss.png" width="16px" alt="Subscribe to <?php bloginfo('name'); ?>" />
		</a>
		Don't miss it - <strong><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?>">Subscribe by RSS.</a></strong>
	</div>
</p>
<p>
	<div class=".box.widget_upcoming">
		Or, just <strong><a href="http://eepurl.com/bz-Mz" title="Subscribe to Do More With WordPress">subscribe to the newsletter!</a></strong>
	</div>
</p>

<?php

    echo $after_widget;
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("UpcomingPostWidget");') );?>