<?php
/*
Template Name: Home Page
*/
?>


<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

		<div id="featured" class="shadow">
        	<div id="featured-content">
            <div id="the_lead">
                <?php 
                query_posts(array('cat'=>'46', "showposts" => '1'));
                while (have_posts()) : the_post();?>
						<?php if ( get_post_meta($post->ID, 'images', true) ) { ?>
                            <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
                            <img src="<?php bloginfo('template_url'); ?>/includes/thumb.php?src=<?php echo bloginfo('siteurl'); ?><?php echo get_post_meta($post->ID, "image", $single = true); ?>&amp;h=182&amp;w=182&amp;zc=1&amp;q=90" alt="<?php the_title(); ?>" />			
                            </a>
                            
                        <?php } else {?>
                            <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
                            <img src="<?php echo bloginfo('siteurl'); ?>/files/2009/07/default180x180.jpg" alt="<?php the_title(); ?>" />			
                            </a>
							
                            
						<?php } ?>		

		                <h3><a href="<?php echo get_category_link('46');?>">THE LEAD &raquo;</a></h3>

                        <h2>
                        	<a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h2>
                		<?php the_excerpt();?>                    
                <?php endwhile; ?>

			<br class="dirtyLittleTrick" />
            </div>

            <h3><a href="#">FEATURED &raquo;</a></h3>
            <div id="featured_columns">
				<?php
                //GET FEATURED STORIES
                query_posts(array('cat'=>'15', "showposts" => '3'));
                $i=1;
                while (have_posts()) : the_post();?>
		            <div class="featured_column c_<?php echo $i;?>">
                    	<a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                    </div>
                <?php 
				$i++;
				endwhile; ?>
            </div>            

            <br class="dirtyLittleTrick" />

            </div>

    	</div><!--/featured-->
     
        <div id="more_news">
            <?php 
			$cats = array(array(3,6),array(7,43),array(11));
			foreach ($cats as $cat_array){ ?>
        	
				<?php for ($i=0; $i<2; $i++){ ?>
					<?php 
					$cat_id = $cat_array[$i];
					if (!empty($cat_id)){ ?>
                    <div class="more_news_bin <?php if ($i==0) {echo "alignleft";} else {echo "alignright";}?>">
                        <h3><a href="<?php echo get_category_link($cat_id);?>" title="View all posts in <?php echo get_cat_name($cat_id);?> category"><?php echo get_cat_name($cat_id);?> &raquo;</a></h3>
                        <ul>
                            <?php 
                            query_posts(array('cat'=>$cat_id, "showposts" => '2'));
                            while (have_posts()) : the_post();?>
                            	<li>
                                <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                               	<?php //edit_post_link('Edit this entry', ' | ', '&raquo'); ?>
</li>
                            <?php endwhile; ?>                    
                        </ul>
                    </div>
                    <?php } 
				   } //end for?>
            <br class="dirtyLittleTrick"/>
			<?php } //end foreach?>
                                    
        </div><!--/more_news-->
        
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
