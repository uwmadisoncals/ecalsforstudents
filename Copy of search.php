<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */

get_header(); ?>

	<div id="content" class="widecolumn">

	<?php if (have_posts()) : ?>

		<h2 class="pagetitle">Search Results for "<em><?php the_search_query() ?></em>"</h2>

		<?php while (have_posts()) : the_post(); ?>

			<div class="search_result_item">
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>">
				<?php
                    $title 	= get_the_title();
                    $keys= explode(" ",$s);
                    $title 	= preg_replace('/('.implode('|', $keys) .')/iu',
                        '<strong class="search-excerpt">\0</strong>',
                        $title);
                ?>				
				
				<?php echo $title; ?></a></h2>
                <div class="the_excerpt">
					<?php the_excerpt();?>
                </div>
                <p>                
                    <strong>Posted in:</strong> <?php the_category(', ') ?><br />
                    <?php the_tags( '<strong>Tags:</strong> ', ', ', ''); ?>
                </p>
	
            </div>

		<?php endwhile; ?>
<!--        <h3>Didn't find what you were looking for? Refine your search!</h3>
        <?php include (TEMPLATEPATH . '/searchform.php'); ?>
	<?php related_searches(); ?>
   	<ul>
			<li><?php next_post_link('&laquo; Older Entries') ?></li>
			<li><?php previous_post_link('Newer Entries &raquo;') ?></li>
		</ul>--> 

   	<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>

	<?php else : ?>

		<h2 class="pagetitle">No posts found. Please try a new search.</h2>
        
        <div class="post">
        	<?php spell_suggest(); ?>
		</div>
		<?php //include (TEMPLATEPATH . '/searchform.php'); ?>

<?php endif; ?>

	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
