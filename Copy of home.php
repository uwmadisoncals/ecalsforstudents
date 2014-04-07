<?php
/*
Template Name: Home Page
*/
?>


<?php get_header(); ?>

	<div id="content" class="narrowcolumn">

		<div id="featured" class="shadow">
        	<div id="featured-content">
            <ul>
				<?php
                //GET FEATURED STORIES
                query_posts(array('cat'=>'15', "showposts" => '3'));
                
                while (have_posts()) : the_post();?>
                    <li>
                        <h2>
                        	<a title="Permanent Link to <?php the_title(); ?>" href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a>
                    	</h2>
                    </li>
                <?php endwhile; ?>
    		</ul>
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
