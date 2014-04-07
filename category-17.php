<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
?>

	<div id="content" class="widecolumn ecals_blog">

 	  <?php /* If this is a category archive */ if (is_category()) { ?>
			<h2 class="pagetitle"><?php single_cat_title(); ?></h2>
 	  <?php }?>
		<div class="archivefeed">
			<?php $cat_obj = $wp_query->get_queried_object(); $cat_id = $cat_obj->cat_ID; 
				  echo '<a href="'; get_category_rss_link(true, $cat, ''); echo '" class=\'rss_link\'> RSS feed for this section</a>'; 
			?>
        </div>
		<?php $i=0;?>
        <?php        while (have_posts()) : the_post();?>
						<?php if ($i==0) {?>
							<div <?php post_class() ?>>
						<div id="the_lead">
							
							<?php if ( get_post_meta($post->ID, 'image', true) ) { ?>
                            <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
<!--                            <img src="<?php bloginfo('template_url'); ?>/includes/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=182&amp;w=182&amp;zc=1&amp;q=90" alt="<?php the_title(); ?>" />	-->	

            <img src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" height="200" width="200" alt="<?php the_title(); ?>" />		
                            </a>

                        <?php } else {?>
                            <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
                            <img src="<?php echo bloginfo('template_url'); ?>/images/default200x200.jpg" alt="<?php the_title(); ?>" />			
                            </a>
						<?php } ?>		
	

                        <h2>
                        	<a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h2>
                		<?php the_excerpt();?> 
                        <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">Continue reading...</a>
                        </div>
                       </div>
                       
                        <?php } else { ?>
							<div <?php post_class() ?>>

                            <?php if ( get_post_meta($post->ID, 'image', true) ) { ?>
                                <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
                               <!-- <img src="<?php bloginfo('template_url'); ?>/includes/thumb.php?src=<?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=80&amp;w=80&amp;zc=1&amp;q=90" alt="<?php the_title(); ?>" class="th" />		-->	
                                           <img src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" height="80" width="80" alt="<?php the_title(); ?>" class="th"/>		

                               
                                </a>
                                
								<?php } else {?>
                                    <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
                                    <img src="<?php echo bloginfo('template_url'); ?>/images/default80x80.jpg" alt="<?php the_title(); ?>" class="th"  />			
                                    </a>
                                <?php } ?>		
    
                            <h2>
                                <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                            </h2>
              				<small>
							<?php the_time('l, F jS, Y') ?></small>
                            <?php the_excerpt();?> 
                            </div>
                            <?php 
						} 
						
						$i++;
						?>
                <?php endwhile; ?>
                
                
                
		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
