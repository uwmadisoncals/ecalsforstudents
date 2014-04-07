<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header();
?>

	<div id="content" class="widecolumn">

	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

	

		<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
			<h2><?php the_title(); ?> <?php if (in_category("28", $post->ID)){ echo "<span class=\"date_due\">(Due ".date("M d", strtotime(get_post_meta($post->ID, 'ecals_post_deadline', true))).")</span>";}?></h2>
					<?php if ($post->post_excerpt!=""){?>
					<h3 class="single_subhead">
						<?php echo get_the_excerpt();?>
                    </h3>
                    <?php } ?>
			<small><?php the_time('l, F jS, Y') ?></small>
	
    		<div class="entry">
            					<?php
	//if user has been redirected from old cals site, display message ($_SESSION['r'] should have been declared in header.php
	if($_SESSION['r']!=""){
		$_SESSION['r']="";
		echo '<div class="update_bookmark">
				<strong>NOTE:</strong> It seems like you have been redirected to this page from an old address (possibly a bookmarked page). If so, please make this page your new bookmark for this article. Thank you!</div>';
	
		//unset session so message is not displayed again
	} 
						
	
	?>
					

						<?php if ( get_post_meta($post->ID, 'image', true) ) { ?>
                            <a  href="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" rel="bookmark">

            <img src="<?php echo get_post_meta($post->ID, "image", $single = true); ?>" height="115" width="115" alt="<?php the_title(); ?>" class="single_th"/>		

                         </a>
                    <?php } ?>        
			<div id="post_utility_menu">
               <ul>
                <?php 
                    if(function_exists('wp_print')) { 
                        echo "<li>";
                        echo print_link();
                        echo "</li>";   
                    }?>

                    <li><a rel="nofollow" href="mailto:?subject=eCALS Article: &ldquo;<?php echo htmlentities($post->post_title);?>&rdquo;&body=<?php echo htmlentities(the_permalink());?>" title="E-mail this post" class="icon_email ">EMAIL</a>
                    </li>                
                </ul>
            </div>
                        
                      <?php the_content('<p class="serif">Read the rest of this entry &raquo;</p>'); ?>
                      <p><?php echo edit_post_link('Edit this entry &raquo;','','');?></p>

				<?php wp_link_pages(array('before' => '<p><strong>Pages:</strong> ', 'after' => '</p>', 'next_or_number' => 'number')); ?>
				<p>
                <strong>Posted in:</strong> <?php the_category(', ') ?><br />
				<?php the_tags( '<strong>Tags:</strong> ', ', ', ''); ?>
                </p>
                <?php if (wp_related_posts()!="") {?>
				<div id="related_posts">
                    <h3>RELATED POSTS</h3>
                    <?php echo wp_related_posts(); ?>				
                </div>
                <?php }?>
			</div>
		</div>

	<?php endwhile; else: ?>

		<p>Sorry, no posts matched your criteria.</p>

<?php endif; ?>

	</div>

<?php get_sidebar();?>
<?php get_footer(); ?>
