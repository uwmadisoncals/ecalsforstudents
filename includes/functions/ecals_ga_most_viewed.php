<?php 

//session_start for caching, if desired
session_start();

function ecals_ga_most_viewed(){

global $wpdb;

//get the class
require 'ga/analytics.class.php';
//sign in and grab profile
$analytics = new analytics('uwcals', 'CALS_comm2012!');
$analytics->setProfileById('ga:58595627');

// set it up to use caching (cache for a day)
$analytics->useCache(true, 3600*24);

//set the date range for which I want stats for (could also be $analytics->setDateRange('YYYY-MM-DD', 'YYYY-MM-DD'))
$date_ranges = array('week' => array(date('Y-m-d',time() - 86400*6), date('Y-m-d',time())),
					 'month'=>array(date('Y-m-d',time() - 86400*30), date('Y-m-d',time())));


try{
	
	$analytics->setDateRange(date('Y-m-d',time() - 86400*6), date('Y-m-d',time()));	
			  
	//get paths/views in current date range 
	$data = $analytics->getData(array('dimensions' => 'ga:pagePath',
									'metrics'    => 'ga:visits',
									'sort'       => 'ga:visits'));

	//sort array from most viewed to least viewed
	arsort($data);
			  
	//take out home and pages,so they don't appear in list
	$keys = array('/', '/category/ecals-blog/', '/category/highlights/', '/category/research/', '/category/awards-honors/');
	
	foreach($keys as $key){
		if (array_key_exists($key, $data)){
			unset($data[$key]);
		}
	}
			  
	//reduce array to top-five entries only
	$data = array_slice($data, 0, 5);
			  
	//get post_paths and generate array with post_names 
	$post_names = array();
	$post_paths = array_keys($data);
	
	foreach($post_paths as $post_path){
		$pp = explode('/', $post_path);
		$post_names[]= $pp[count($pp)-2];
	}
			  
//get posts by post_name
					
	//build "or" condition
	for ($i=0; $i<count($post_names); $i++){
		$or.= 'post_name = "'.$post_names[$i].'" ';
		if ($i<(count($post_names)-1))
			$or.=' OR ';
	}
					
	$query = 'SELECT * FROM '.$wpdb->posts.' 
			    WHERE '.$or.'';
				
	$most_viewed = $wpdb->get_results($query);
					
	if ($most_viewed){
		foreach($most_viewed as $mv){
	    	echo '<li><a title="Permanent Link to '.$mv->post_title.'" href="'.get_permalink($mv->ID).'" rel="bookmark">'.$mv->post_title.'</a></li>';
		}
	} else {
		echo "<h2>Not Found</h2";
	}
} //end try 

catch (Exception $e) { 
      echo 'Caught exception: ' . $e->getMessage(); 
}

} //EOF

?>