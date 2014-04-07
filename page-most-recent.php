<?php
/*
Template Name: Page Most Recent
*/

get_header();
?>

	<div id="content" class="widecolumn">


		<h2 class="pagetitle">Most Recent Posts</h2>
        <div class="archivefeed">
                    <a href="/feed/" class="rss_link"> RSS feed for this section</a>        </div>
		
		
		<?php 
		$page = (get_query_var('paged')) ? get_query_var('paged') : 0;
		query_posts('orderby=date&order=desc&posts_per_page=10&paged='.($page));
	    ?>
		<?php while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?>>
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?><?php if (in_category("28", $post->ID)){ echo " <span class=\"date_due\">(Due ".date("M d", strtotime(get_post_meta($post->ID, 'ecals_post_deadline', true))).")</span>";}?></a></h2>
				<small><?php the_time('l, F jS, Y') ?></small>
				<p class="postedin"><?php //the_tags('Tags: ', ', ', '<br />'); ?> 
				  <strong>Posted in:</strong>
<?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?> </p>

	  </div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
