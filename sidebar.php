<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar">
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
			<?php endif; ?>
        
        <div id="events" class="box">
        	<div class="box_header ">
            	<div class="box_title shadow">
                <h2>EVENTS</h2></div>
                <div class="more_link"><a href="http://www.today.wisc.edu/events/feed/30">MORE &raquo;</a></div>
            </div>
            <div class="box_content shadow">
            <div class="box_content_inner">
                <?php // Get Events
                
				//turn on xml error handling
				libxml_use_internal_errors(true);
				
				$events = @simplexml_load_file("http://www.today.wisc.edu/events/feed/30.xml");
				
				if ($events){
				$maxitems = 4;
				
				
	            ?>
                
                  <?php 
				  if (empty($events)) {
				  	echo '<li>No items</li>';
				  } else {
                        $prev_date="";
						$i=0;
						foreach ( $events as $event ) : 
                           if ($i>=2) {?>
						   <?php 
						   //temporary fix: set timezone to chicago to offset server's timezone
						   //which is off by 5hrs (04/06/10)
						   date_default_timezone_set('America/Chicago');
						   
						   
						   $date = date('M d', strtotime($event->start_date));
						   $time = date("g:i a", strtotime($event->start_date));
						  
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
				unset ($events);
				
				} }else {
					//if there was an xml error, temporarily display link to full list of events
					echo '<h3><a href="http://www.today.wisc.edu/events/feed/30" title="Get events">Upcoming CALS Events &raquo;</a></h3><p>&nbsp;</p>';
					
					//display xml errors
				    echo "<!--";
    					foreach(libxml_get_errors() as $error) {
        					echo "\t", $error->message;
						}
					echo "-->";				
				
				}
	
				?>
                    </ul>       
                </div>
            </div>
        </div> <!--/#events-->


	<?php
	
	//GET DEADLINE POSTS
		 /* -disabled on 06/27/11
		 $querystr = "
			SELECT wposts . * , wpostmeta . *
			FROM wp_3c4ls_posts wposts, wp_3c4ls_postmeta wpostmeta
			WHERE wposts.ID = wpostmeta.post_id
			AND wpostmeta.meta_key = 'ecals_post_deadline'
			AND wposts.post_status = 'publish'
			AND wposts.post_type = 'post'
			AND wpostmeta.meta_value >= '".date("Y-m-d", time())."'
			ORDER BY wpostmeta.meta_value ASC LIMIT 3";
		
  		    $post_deadlines = $wpdb->get_results($querystr, OBJECT);?>
    
     <?php if ($post_deadlines): ?>
        <div id="deadlines" class="box">
        	<div class="box_header">
            	<div class="box_title shadow">
                <h2>DEADLINES</h2></div>
                <div class="more_link"><a href="<?php echo get_page_link("10712");?>">MORE &raquo;</a></div>
            </div>
            <div class="box_content shadow">
            <div class="box_content_inner">
			<?php 				
				$prev_date="";
				$i=0; 
			?>
             <?php foreach ($post_deadlines as $post): ?>
             <?php setup_postdata($post); 
				
      		   	$date = date('M d', strtotime($post->meta_value));
				if ($prev_date!="" && $prev_date!=$date) { echo "</ul>";  }
	
				if ($prev_date=="" || $prev_date!=$date) { 
				     echo "<h3>".$date."</h3>";
				     echo "<ul class=\"events_list\">";
				
			  }?>
                
                <li><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?></a>
                </li>
            	                           <?php if ($prev_date!=$date) {
									$prev_date = $date;
							}?>
		<?php $i++; 
		endforeach; ?>
            </div>
            </div>
        </div><!--/#dealines-->
<?php endif; */ ?>
               
        <div id="tabs" class="box"> 
          <div class="box_header_tabs">
          <ul class="idTabs"> 
            <li class="box_title_tabs shadow"><a href="#recent" class="selected">RECENT</a></li>
            <li class="box_title_tabs shadow"><a href="#most_viewed" >MOST VIEWED</a></li> 
          </ul>
          </div> 

         <div class="box_content_tabs shadow">
               <div id="recent" class="box_content_inner">
                 <div class="more_link"><a href="<?php echo get_page_link(10024);?>">MORE &raquo;</a></div>
				 <ul>
				 <?php  wp_get_archives("type=postbypost&limit=5");?>
              	</ul>
              </div> 
              
         <div id="most_viewed" class="box_content_inner">
<!--                <div class="more_link"><a href="#">MORE &raquo;</a></div>
-->	

<?php if (function_exists('ecals_ga_most_viewed')){ ?>
   <ul>
      <?php ecals_ga_most_viewed(); ?>
   </ul>
<?php } ?>

                           </div>

          </div>
         </div>
      

        <div id="campus_news" class="box">
        	<div class="box_header">
            	<div class="box_title shadow">
                <h2>CAMPUS NEWS</h2></div>
                <div class="more_link"><a href="http://www.news.wisc.edu/archives/">MORE &raquo;</a></div>
            </div>
            <div class="box_content shadow">
            <div class="box_content_inner">
                <?php // Get RSS Feed(s)
                include_once(ABSPATH . WPINC . '/rss.php');
                $rss = fetch_rss('http://www.news.wisc.edu/feeds/list/4');
                $maxitems = 5;
                $items = array_slice($rss->items, 0, $maxitems);
                ?>
                
                <ul>
                <?php if (empty($items)) echo '<li>No items</li>';
                else
                foreach ( $items as $item ) : ?>
                <li><a href='<?php echo $item['link']; ?>' 
                title='<?php echo $item['title']; ?>'>
                <?php echo $item['title']; ?>
                </a></li>
                <?php endforeach; ?>
                </ul>
            </div>
            </div>
        </div><!--/#Campus News-->
        
        
       <!-- <div id="tags" class="box">
        	<div class="box_header">
            	<div class="box_title shadow">
                <h2>POPULAR TAGS</h2></div>
            </div>
            <div class="box_content shadow">
            <div class="box_content_inner">
            	<?php wp_tag_cloud("smallest=11&number=14"); ?>
            </div>
            </div>
        </div>/#tags-->
</div><!--/#sidebar-->

