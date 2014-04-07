<?php
/*
Template Name: Page RSS Feeds
*/

get_header(); ?>

	<div id="content" class="widecolumn">

		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<h2 class="pagetitle"><?php the_title(); ?></h2>
	
    	<div class="post" id="post-<?php the_ID(); ?>">
			<div class="entry">
				<?php the_content('<p class="serif">Read the rest of this page &raquo;</p>'); ?>
	
                    <h2>General RSS Feeds</h2>
	                    <ul class="rss_list">
                        	<li><a href="/feed/" title="">Most recent</a></li>
                            <li><a href="<?php echo get_category_link(10);?>feed/" title="">Highlights</a></li>
                            <li><a href="<?php echo get_category_link(17);?>feed/" title="">eCALS Blog</a></li>
                        </ul>		                
                <h2>RSS Feeds by Category</h2>
			 
                    <ul class="rss_list">
                     <?php $categories = get_categories("hide_empty=1&type=post&exclude=1,10,17,28&orderby=name&order=ASC"); 
                            //print_r($categories);
                            foreach($categories as $cat){ ?>
                                <li><a href="<?php echo get_category_link($cat->cat_ID);?>/feed/" title="" ><?php echo $cat->cat_name;?></a></li>
                    <?php }?> 
                     </ul>
    		</div>
		</div>
		<?php endwhile; endif; ?>
	<?php edit_post_link('Edit this entry.', '<p>', '</p>'); ?>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
