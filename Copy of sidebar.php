<?php
/**
 * @package WordPress
 * @subpackage Default_Theme
 */
?>
	<div id="sidebar">
		<ul>
			<?php 	/* Widgetized sidebar, if you have the plugin installed. */
					if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar() ) : ?>
			<?php endif; ?>
		</ul>
        
        <div id="events" class="box">
        	<div class="box_header ">
            	<div class="box_title shadow">
                <h2>EVENTS</h2></div>
                <div class="more_link"><a href="#">MORE</a></div>
            </div>
            <div class="box_content shadow">
            <div class="box_content_inner">
                <?php // Get Events
                include_once(ABSPATH . WPINC . '/rss.php');
                $events = simplexml_load_file("http://www.today.wisc.edu/events/feed/30.xml");
				$maxitems = 4;
	            ?>
                
                  <?php if (empty($events)) echo '<li>No items</li>';
                    else
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
				unset ($events);
				?>
                    </ul>       
                </div>
            </div>
        </div> <!--/#events-->
        
        <div id="deadlines" class="box">
        	<div class="box_header">
            	<div class="box_title shadow">
                <h2>DEADLINES</h2></div>
                <div class="more_link"><a href="#">MORE</a></div>
            </div>
            <div class="box_content shadow">
            <div class="box_content_inner">
                <?php // Get RSS Feed(s)
                include_once(ABSPATH . WPINC . '/rss.php');
                $deadlines = simplexml_load_file("http://www.today.wisc.edu/events/feed/30.xml");
				$maxitems = 4;
	            
				//search events with "deadline" tag

				
				?>
                
                  <?php if (empty($deadlines)) echo '<li>No items</li>';
                    else
                        $prev_date="";
						$i=0;
						
						foreach ( $deadlines as $deadline ) : 
                        $has_deadline=0;
						//check for "deadline" tag
						  	foreach($deadline->tags as $tags){
								foreach($tags->tag as $tag){
									if ($tag["id"]=="1970"){
										$has_deadline=1;
										break;
									}
								}	
							}
						  
	
						   if ($has_deadline==1){
						   
								   $date = date('M d', strtotime($deadline->start_date));
								   $time = date("g:s a", strtotime($deadline->start_date));
								   
								   if ($prev_date!="" && $prev_date!=$date) { echo "</ul>";  }
		
								   if ($prev_date=="" || $prev_date!=$date) { 
									  echo "<h3>".$date."</h3>";
									  echo "<ul class=\"events_list\">";
								   }
								   ?>
									<li>
										<a href='http://www.today.wisc.edu/events/view/<?php echo $deadline->id; ?>'>
										<?php 
										if ($time!="12:00 am"){
										echo "<strong>".$time."</strong> - ";
										} ?> 
										<?php echo $deadline->title; ?></a>
									</li>
								   <?php if ($prev_date!=$date) {
											$prev_date = $date;
									}?>
							<?php 

							$has_deadline=0;
						
							if ($i<$maxitems){ $i++; } else { break; }
						   
						   } // if ($has_deadline==1)
				endforeach; 
				unset ($deadlines);
				?>
                
                </div>
            </div>
        </div><!--/#dealines-->
        
<!--        <div id="tabs" class="box"> 
          <ul class="idTabs"> 
            <li class="box_title_tabs shadow"><a href="#recent" class="selected">RECENT</a></li>
            <li class="box_title_tabs shadow"><a href="#most_viewed" >MOST VIEWED</a></li> 
          </ul> 
          </div>
         <div class="box_content_tabs shadow">
               <div id="recent" class="box_content_inner">
                 <div class="more_link"><a href="#">MORE</a></div>here
				 <?php  wp_get_archives("type=postbypost&limit=5");?>
              	</ul>
              </div> 
              
         <div id="most_viewed" class="box_content_inner">
                <div class="more_link"><a href="#">MORE</a></div>
	             More content in tab 2.
              </div>
          </div>
        </div>-->
      
        <div id="tags" class="box">
        	<div class="box_header">
            	<div class="box_title shadow">
                <h2>POPULAR TAGS</h2></div>
            </div>
            <div class="box_content shadow">
            <div class="box_content_inner">
            	<?php wp_tag_cloud("number=14"); ?>
            </div>
            </div>
        </div><!--/#tags-->

        



	</div><!--/#sidebar-->

