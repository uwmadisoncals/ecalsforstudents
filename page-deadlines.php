<?php
/*
Template Name: Page Deadlines
*/

get_header();
?>

	<div id="content" class="widecolumn">


		<h2 class="pagetitle">Deadlines</h2>
<!--        <div class="archivefeed">
                    <a href="/feed/" class="rss_link"> RSS feed for this section</a>        </div>
-->		
		
		<?php 
		$page = (get_query_var('paged')) ? get_query_var('paged') : 0;
		
	//GET DEADLINE POSTS
		 $querystr = "
			SELECT wposts . * , wpostmeta . *
			FROM wp_3c4ls_posts wposts, wp_3c4ls_postmeta wpostmeta
			WHERE wposts.ID = wpostmeta.post_id
			AND wpostmeta.meta_key = 'ecals_post_deadline'
			AND wposts.post_status = 'publish'
			AND wposts.post_type = 'post'
			AND wpostmeta.meta_value >= '".date("Y-m-d", time())."'
			ORDER BY wpostmeta.meta_value ASC";
		
  		    $post_deadlines = $wpdb->get_results($querystr, OBJECT);?>
    
	
             <?php foreach ($post_deadlines as $post): ?>
             <?php setup_postdata($post);?> 
				
		<div <?php post_class() ?>>
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?><?php echo " <span class=\"date_due\">(Due ".date("M d", strtotime(get_post_meta($post->ID, 'ecals_post_deadline', true))).")</span>";?></a></h2>
				<small><?php the_time('l, F jS, Y') ?></small>
<!--				<p class="postmetadata"><?php the_tags('Tags: ', ', ', '<br />'); ?> Posted in <?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?> </p>
-->
			</div>

		<?php endforeach; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
