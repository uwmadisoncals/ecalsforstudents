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
                query_posts(array('cat'=>'17', "showposts" => '1'));
                while (have_posts()) : the_post();?>
						<?php if ( get_post_meta($post->ID, 'image', true) ) { ?>
                            <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
	
            <img src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" height="200" width="200" alt="<?php the_title(); ?>" />		
                            </a>
                            
                        <?php } else {?>
                            <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">
                            <!-- default image is: /images/default200x200.jpg -->
                            <img src="<?php echo bloginfo('template_url'); ?>/images/mortarboard.jpg" alt="<?php the_title(); ?>" />			
                            </a>
							
                            
						<?php } ?>		

                        <h2>
                        	<a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                        </h2>
                		<div class="lead_excerpt">
							<?php the_excerpt();?>
                        </div>                    
                <?php endwhile; ?>
				<div id="blog_links">
                	<a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">READ ARTICLE</a> - <a href="<?php echo get_category_link(17);?>">VISIT BLOG</a>
                </div>
            </div>

            </div>

    	</div><!--/featured-->
     
        <div id="more_news">
                <div id="highlights">
                    <h2 class="section_title">Events</h2>
                    <ul>
                        <?php
                        //GET FEATURED STORIES
                        query_posts(array('cat'=>'10', "showposts" => '3'));
                        
						$i=0;
                        while (have_posts()) : the_post();
                            $i++; ?>

                            <li <?php if ($i==3){ echo "class=\"no_border\"";}?>>
                               <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?><?php if (in_category("28", $post->ID)){ echo " <span class=\"date_due\">(Due ".date("M d", strtotime(get_post_meta($post->ID, 'ecals_post_deadline', true))).")</span>";}?></a>
                              <?php 
                              if ($post->post_excerpt!=""){?>
                                <p>
                                  <?php echo ecals_short_excerpt($post->post_excerpt, 25);?> <a title=\"Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark">[...more]</a>
                                </p>
                             <?php  }?>
                            </li>
						<?php endwhile; ?>
                    </ul>
                
                <a href="<?php echo get_category_link(10);?>" class="more_highlights">See More &raquo;</a>
                </div>
            <br class="dirtyLittleTrick"/>
 
 			<div id="more_entries">
                    <h2 class="section_title">More Entries</h2>
            
			<?php 
			#$cats = array(array(3,4),array(5,7));
			$cats = array(array(3,7));
			foreach ($cats as $cat_array){ ?>
        	
				<?php for ($i=0; $i<2; $i++){ ?>
					<?php 
					$cat_id = $cat_array[$i];
					if (!empty($cat_id)){ ?>
                    <div class="more_news_bin <?php if ($i==0) {echo "alignleft";} else {echo "alignright";}?>">
                        <h3><a href="<?php echo get_category_link($cat_id);?>" title="View all posts in <?php echo get_cat_name($cat_id);?> category"><?php echo get_cat_name($cat_id);?> &raquo;</a></h3>
                        <ul>
                            <?php 
                            query_posts(array('cat'=>$cat_id, "showposts" => '5'));
                            while (have_posts()) : the_post();?>
                            	<li>
                                <a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?><?php if (in_category("28", $post->ID)){ echo " <span class=\"date_due\">(Due ".date("M d", strtotime(get_post_meta($post->ID, 'ecals_post_deadline', true))).")</span>";}?></a>
                               	<?php //edit_post_link('Edit this entry', ' | ', '&raquo'); ?>
</li>
                            <?php endwhile; ?>                    
                        </ul>
                    </div>
                    <?php } 
				   } //end for?>
            <br class="dirtyLittleTrick"/>
			<?php } //end foreach?>
          </div><!--more entries-->                          
        </div><!--/more_news-->
        
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
