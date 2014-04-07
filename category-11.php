<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
get_header();
?>

	<div id="content" class="widecolumn">


		<?php if (have_posts()) : ?>

 	  <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
 	  <?php /* If this is a category archive */ if (is_category()) { ?>
		<h2 class="pagetitle"><?php single_cat_title(); ?></h2>
			<ul id="funding_opp_links">
            	<li><a href="http://www.cals.wisc.edu/research/funding/non-federal.php?0?Cp6" title="Link to CALS Research">Click here to see a full list of upcoming Non-federal funding opportunities (CALS Research Division)</li>
                
                <?php $mypost = get_posts('numberposts=1&category=11');
					  foreach($mypost as $post){
						  setup_postdata($post);?>
                <li><a href="<?php the_permalink(); ?>">Click here to see the latest list of upcoming Federal Funding opportunities</a></li>
            	<?php } ?>
			</ul>
        
        
        <h2 id="funding_opp_arch">Funding Opportunities Archive</h2>
        
        
		<div class="archivefeed">
			<?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; 
				  echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '" class=\'rss_link\'> RSS feed for this section</a>'; 
			?>
        </div>
 	  <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
		<h2 class="pagetitle">Posts Tagged &#8216;<?php single_tag_title(); ?>&#8217;</h2>
 	  <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F jS, Y'); ?></h2>
 	  <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('F, Y'); ?></h2>
 	  <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
		<h2 class="pagetitle">Archive for <?php the_time('Y'); ?></h2>
	  <?php /* If this is an author archive */ } elseif (is_author()) { ?>
		<h2 class="pagetitle">Author Archive</h2>
 	  <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
		<h2 class="pagetitle">Blog Archives</h2>
 	  <?php } ?>



		<?php while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?>>
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?><?php if (in_category("28", $post->ID)){ echo " <span class=\"date_due\">(Due ".date("M d", strtotime(get_post_meta($post->ID, 'ecals_post_deadline', true))).")</span>";}?></a></h2>
				<small><?php the_time('l, F jS, Y') ?></small>
<!--				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?> </p>
-->
			</div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	<?php else :

		if ( is_category() ) { // If this is a category archive
			printf("<h2 class='center'>Sorry, but there aren't any posts in the %s category yet.</h2>", single_cat_title('',false));
		} else if ( is_date() ) { // If this is a date archive
			echo("<h2>Sorry, but there aren't any posts with this date.</h2>");
		} else if ( is_author() ) { // If this is a category archive
			$userdata = get_userdatabylogin(get_query_var('author_name'));
			printf("<h2 class='center'>Sorry, but there aren't any posts by %s yet.</h2>", $userdata->display_name);
		} else {
			echo("<h2 class='center'>No posts found.</h2>");
		}
		//get_search_form();

	endif;
?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
