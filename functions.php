<?php

//good reference
//http://malarvizhik.wordpress.com/2010/05/17/wordpress-functions-php-template-with-15-essential-custom-functions/

//require wp-load.php when making ajax requests, so all configuration is loaded again without refreshing pages

if (!function_exists('add_action')){
    require_once("/Library/WebServer/Documents/ecalsnew/wp-config.php");
}



//CUSTOM WIDGETS

//Wisconsin Calendar Widget

if (function_exists('register_sidebar_widget')){
	register_sidebar_widget('Wisconsin Calendar RSS', 'wisc_cal_rss');
}

function wisc_cal_rss(){
	include('widgets/wisc_cal_rss.php');
}


//CUSTOM FUNCTIONS
function ecals_short_excerpt($excerpt_text, $limit=25){
	if (str_word_count($excerpt_text, 0)<$limit){
		$output = $excerpt_text;
	} else {
		$words = explode(" ", $excerpt_text);
		for ($i=0; $i<$limit; $i++){
			$output.=$words[$i];
			if ($i!=$limit-1){
				$output.=" ";
			} else {
				$output.="...";
			}
		}
	}
	return $output;

}

//CUSTOM LOGIN PAGE
function ecals_custom_login(){?>
	<link rel="stylesheet" type="text/css" href="<?php bloginfo("template_url");?>/styles/custom-login.css" />
	<link rel="stylesheet" type="text/css" href="<?php bloginfo("template_url");?>/styles/custom-colors-fresh.css" />

<?php }

function ecals_login_form(){?>
        <div id="sup_form">
    	<h1 class="or">Don't have an account?</h1>

        <h2>
        <a href="<?php echo get_page_link(10042);?>">
        	Send your posts using our public submission form &raquo;
        </a>
        </h2>
        </div>

    
    
<?php }

function change_wp_login_url() {
    echo bloginfo('url');
}


add_action('login_head', 'ecals_custom_login');
add_action('login_form', 'ecals_login_form');
add_filter('login_headerurl', 'change_wp_login_url');

//END OF CUSTOM LOGIN PAGE CODE


//Include cals custom deadline file
include ("includes/functions/cals_custom_deadline.php");

//Google Analytics Most Viewed
include("includes/functions/ecals_ga_most_viewed.php");


if(is_admin()){
//include cals newsletter file
//file missing -- commented out Feb 9, 2014 by Jason Pursian
//include ("includes/functions/ecals_mail.php");

//include cals newsletter file v2
include ("includes/functions/ecals_mail_2.php");

}

?>