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
			<small><?php the_time('l, F jS, Y') ?></small>
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

	<?php else : ?>

		<h2 class="pagetitle">No posts found. Please try a new search.</h2>
        
        <div class="post">
        	<?php spell_suggest(); ?>
		</div>
		<?php //include (TEMPLATEPATH . '/searchform.php'); ?>

<?php endif; ?>



<!--
  <?php // Get Events
        $s=  str_replace(" ", "+", urldecode($_GET["s"]));
		echo $s;
		$events = simplexml_load_file("http://www.today.wisc.edu/events/xml?url=http://www.today.wisc.edu/events/search/video+bootcamp.xml");
		$maxitems = 4;
        ?>
        
          <?php if (empty($events)){ echo '<li>No items</li>';}
            else{
                echo "<h3>Events</h3>";
				$prev_date="";
                $i=0;
                foreach ( $events as $event ) : 
                   if ($i>=2) {?>
                   <?php 
                   $date = date('M d', strtotime($event->start_date));
                   $time = date("g:s a", strtotime($event->start_date));
                   
                   if ($prev_date!="" && $prev_date!=$date) { echo "</ul>";  }

                   if ($prev_date=="" || $prev_date!=$date) { 
                      echo "<h3>".$date."</h3>";
                      echo "<ul class=\"events_list\">";
                   }
                   ?>
                    <li>
                        <a href='http://www.today.wisc.edu/events/view/<?php echo $event->id; ?>'>
                        <?php 
                        if ($time!="12:00 am"){
                        echo "<strong>".$time."</strong> - ";
                        } ?> 
                        <?php echo $event->title; ?></a>
                    </li>
                   <?php if ($prev_date!=$date) {
                            $prev_date = $date;
                    }?>
            <?php 
            }
            
            if ($i<$maxitems){ $i++; } else { break; }
        endforeach; 
			}
		unset ($events);
        ?>
-->    
    </div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
