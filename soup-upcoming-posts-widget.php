<?php
/*
Plugin Name: SOUP - Show Off Upcoming Posts
Plugin URI: http://www.doitwithwordpress.com
Description: Displays your upcoming posts to tease your readers
Author: Dave Clements
Version: 1.1
Author URI: http://www.theukedge.com
*/
 
function soup()
{
   ?>

<p>
	<div class=".box.widget_upcoming">
		<?php

$options = get_option('widget_soup');
  if (!is_array( $options ))
{
$options = array(
      'soup_number' => '3'
      );
  }

$soupnumber = $options['soup_number'];

			$my_query = new WP_Query(array('posts_per_page' => $soupnumber, 'nopaging' => 0, 'post_status' => 'future', 'order' => 'ASC'));
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
			<img style="vertical-align:middle; margin:0 10px 0 0;" src="<?php bloginfo('url'); ?>/wp-content/plugins/soup-show-off-upcoming-posts/icons/rss.png" width="16px" alt="Subscribe to <?php bloginfo('name'); ?>" />
		</a>
		Don't miss it - <strong><a href="<?php bloginfo('rss2_url'); ?>" title="Subscribe to <?php bloginfo('name'); ?>">Subscribe by RSS.</a></strong>
	</div>
</p>

<?php }

function widget_soup($args) {
  extract($args);

$options = get_option("widget_soup");
  if (!is_array( $options ))
{
$options = array(
      'title' => 'Upcoming Posts'
      );
  }


  echo $before_widget;
  echo $before_title;
  echo $options['title'];
  echo $after_title;
  soup();
  echo $after_widget;
}

function soup_control() {
	$options = get_option('widget_soup');
 	 if (!is_array( $options ))
	{
	$options = array(
      'title' => 'Upcoming Posts',
      'soup_number' => 3
      );
  }

if ($_POST['soup-submit'])
  {
    $options['title'] = htmlspecialchars($_POST['soup_widget_title']);
    $options['soup_number'] = htmlspecialchars($_POST['soup_no_of_posts']);
    update_option("widget_soup", $options);
  }

?>
	<p>
    <label for="soup_widget_title">Widget title: </label>
    <input type="text" id="soup_widget_title" name="soup_widget_title" value="<?php echo $options['title'];?>" />
  </p>
	<p>
    <label for="soup_no_of_posts">No. of posts: </label>
    <input type="text" id="soup_no_of_posts" name="soup_no_of_posts" value="<?php echo $options['soup_number'];?>" />
	<input type="hidden" id="soup-submit" name="soup-submit" value="1" />
  </p>
<?php }

function soup_init() {
	register_sidebar_widget (__('Upcoming Posts'), 'widget_soup');
	register_widget_control( 'Upcoming Posts', 'soup_control' );
	}

add_action('plugins_loaded', 'soup_init');

?>