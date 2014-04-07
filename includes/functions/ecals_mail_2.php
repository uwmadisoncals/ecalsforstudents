<?php 
/*
The eCALS email sender v 2
Edited 4/20/2012 by Jason Pursian 
Customized for eCALS News for Students Site
*/


add_action( 'ecals_mail_2_hook', 'ecals_mail_2' ); 

/*---ADMIN SECTION FOR ECALS NEWSLETTER--*/
add_action('admin_menu', 'ecals_mail_2_admin_menu');

function ecals_mail_2_admin_menu() {
  add_options_page('Student eCALS Newsletter', 'Student eCALS Newsletter', 10, __FILE__, 'ecals_mail_2_options');
}

function ecals_mail_2_options(){
		$hidden_field_name = "hfn";
		
		//check if user has requested to resubmit email
		if (isset($_POST[$hidden_field_name]) && $_POST[$hidden_field_name]=="Y") {	
			if (ecals_mail_2()){?>
                <div id="message" class="updated fade">
                  <p><strong>
                    <?php _e('Email has been sent.', 'ecals_mail_2_options' ); ?>
                    </strong></p>
                </div>				
			<?php }
		}

		
		?>
    	<div class="wrap">
			<h2>eCALS Newsletter Options (v2)</h2>
    <!--        <div class="tool-box">
            	<h3 class="title">Scheduled Submissions</h3>
                <p>
                <?php if (!wp_next_scheduled('ecals_mail_2_hook')){
                		echo "No submissions are scheduled at this time.";
					  } else { ?>
                      
                      The next draft of the eCALS News for Students newsletter will be sent to sryan@cals.wisc.edu on <strong><?php echo date("m/d/Y", wp_next_scheduled('ecals_mail_2_hook')); ?></strong>.
                <?php }	?>
                </p>
            </div>-->
			<div class="tool-box">
			<?php
				
				$current_week = date("W", time());
				$num_day_0101 = date("N", strtotime("Jan 1 ".date("Y", time())));
				$monday_current_week = date("m/d/y", strtotime("Jan 1 ".date("Y", time())) + ((($current_week)*604800) - 86400*($num_day_0101- 1) ));        

				/*if(isset($_POST["start"]) && isset($_POST["end"])){
					$start_date = date("m/d/Y", strtotime($_POST["start"]));
					$end_date = date("m/d/Y", strtotime($_POST["end"]));
				} else {*/
					$start_date = date("m/d/Y", strtotime($monday_current_week." - 6 days")); //start on tuesday next week
					$end_date = date("m/d/Y", strtotime($monday_current_week)); //end monday of current week
				//} ?>              
            	<h3 class="title">Resubmit email </h3>
                <p>Click the button below to resubmit a draft of the most recent eCALS Newsletter <strong>(includes posts from <?php echo date("m/d/y", strtotime($start_date)).' to '.date("m/d/y", strtotime($end_date)); ?>)</strong>.
                <form name="ecals_mail_2_options" method = "post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">       
                <strong>Date Range:</strong> <input id="start" name="start" type="text" size="10" maxlength="12" value="<?php echo $start_date?>"/> to <input id="end" name="end" type="text" size="10" maxlength="12" value="<?php echo $end_date?>"/>
                <span class="submit">
                	
                    <input name="resubmit" value="Resubmit Student eCALS Newsletter draft to sryan@cals.wisc.edu" type="submit">
                </span>
                    <input type="hidden" name="ecals_noncename" id="ecals_noncename" value="
  <?php wp_create_nonce( plugin_basename(__FILE__) );?>" />
  					<input type="hidden" name=<?php echo $hidden_field_name; ?> value="Y" />
                  </form>
                </p>
            </div>            
    	</div>

<?php }

/*---END OF ADMIN SECTION FOR ECALS NEWSLETTER--*/

/**
 * Send eCALS newsletter in html and plain text formats
 *
 * @reference http://krijnhoetmer.nl/stuff/php/html-plain-text-mail/ on how to send multipart emails
 **/
function ecals_mail_2(){
	
	//get vars
	global $wpdb;
	global $post;
	
	$cats = array(7,9,11,15,16,18,3,17);
	$current_week = date("W", time());
	$num_day_0101 = date("N", strtotime("Jan 1 ".date("Y", time())));
	$monday_current_week = date("m/d/y", strtotime("Jan 1 ".date("Y", time())) + ((($current_week-1)*604800) - 86400*($num_day_0101- 1) ));
		
	if(isset($_POST["start"]) && isset($_POST["end"])){
		$start_date = date("Y-m-d", strtotime($_POST["start"]));
		$end_date = date("Y-m-d", strtotime($_POST["end"]));
	} else {
		$start_date = date("Y-m-d", strtotime($monday_current_week." - 7 days"));
		$end_date = date("Y-m-d", strtotime($monday_current_week." - 1 days"));
	}	
	
	$google_campaign_vars = '?utm_source=ecals_email_newsletter&utm_medium=email&utm_campaign=ecals_email_newsletter';

	
	//setup email
	
	
	$to ='sryan@cals.wisc.edu';
	//$to ='anemec@cals.wisc.edu';
	
	$subject ="eCALS News for Students Newsletter - ".date("F d Y", time());

	$boundary = uniqid('np');
 
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "From: eCALS News for Students <sryan@cals.wisc.edu>\r\n";
	$headers .= "Subject: eCALS News for Students <sryan@cals.wisc.edu>\r\n";
	$headers .= "Content-Type: multipart/alternative;boundary=" . $boundary . "\r\n";

 
	$message = "This is a MIME encoded message."; 
 
	$message .= "\r\n\r\n--" . $boundary . "\r\n";
	$message .= "Content-type: text/plain;charset=utf-8\r\n\r\n";
	$message .= ecals_mail_2_plain_text($cats, $start_date, $end_date);

	$message .= "\r\n\r\n--" . $boundary . "\r\n";
	$message .= "Content-type: text/html;charset=utf-8\r\n\r\n";
	$message .= ecals_mail_2_html($cats, $start_date, $end_date);

	$message .= "\r\n\r\n--" . $boundary . "--";
 
 	//send email
	return(mail($to, $subject, $message, $headers));


}


function ecals_mail_2_plain_text($cats, $start_date, $end_date){

global $wpdb;

$output ='
==============================================================
eCALS Newsletter | College of Agricultural and Life Sciences
==============================================================

'.date("F d, Y", time()).'

The following is a compilation of all the messages posted
on eCALS News for Students from '.date("m/d/y", strtotime($start_date)).' to '.date("m/d/y", strtotime($end_date)).'.

';

//GET POSTS
foreach ($cats as $cat_id){ 
	//get list of children categories, so their posts can be printed as well
	$categories=  get_categories('child_of='.$cat_id); 
	$cats_in = $cat_id;
	
	//make list of posts
	foreach ($categories as $cat){
		$cats_in.= ", '".$cat->cat_ID."'";
	}

	//get posts
	$query ="SELECT DISTINCT ID, post_title, post_date 
				FROM $wpdb->posts, $wpdb->term_taxonomy, $wpdb->term_relationships 
				WHERE $wpdb->term_taxonomy.taxonomy = 'category' 
				AND $wpdb->term_taxonomy.term_id IN (".$cats_in.")
				AND $wpdb->posts.post_type = 'post' 
				AND ($wpdb->posts.post_status = 'publish') 
				AND post_date >= '$start_date 00:00:00' 
				AND post_date <= '$end_date 23:59:59' 
				AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id 
				AND $wpdb->term_relationships.object_id = $wpdb->posts.ID 
				ORDER BY $wpdb->posts.post_date DESC";
				
	$posts = $wpdb->get_results($query);	
	
	//if there are posts in the category, print them
	if ($wpdb->num_rows > 0) {

$output.='
'.strtoupper(htmlspecialchars_decode(get_cat_name($cat_id))).'
------------------------------------
';
		foreach ($posts as $post){
			setup_postdata($post);
$output.='
** '.wordwrap(addslashes(htmlspecialchars_decode(get_the_title($post->ID))), 65).'
   <'.wp_get_shortlink($post->ID).'>
';
		} //endforeach
$output.='

';
	} // endif

} //endforeach

$output.='
CALS EVENTS
------------------------------------

** To see a list of CALS events, please visit
   <http://www.today.wisc.edu/events/feed/30>



==============================================================
==============================================================

For more news, to post an article, or to subscribe to
RSS Feeds, please visit http://ecals.cals.wisc.edu.
You may also send your inquiries to ecals@cals.wisc.edu.

CALS on Facebook: http://www.facebook.com/UWMadisonCALS
CALS on Twitter: http://twitter.com/#!/UWMadisonCALS
CALS RSS Feed: http://ecals.cals.wisc.edu/rss-feeds/


Regards,

The eCALS Team

© Copyright '.date('Y', time()).'. All rights reserved.
College of Agricultural and Life Sciences
University of Wisconsin-Madison.';


return $output;

}

function ecals_mail_2_html($cats, $start_date, $end_date) {
		
	global $wpdb;

$message_head = 
'<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" >
<title>eCALS Newsletter - '.date("F d Y", time()).'</title>
<style type="text/css">
     #outlook a{padding:0;} 
     body{width:100% !important; font-size: 16px} .ReadMsgBody{width:100%;} .ExternalClass{width:100%;} 
     body{-webkit-text-size-adjust:none;} 
     body{margin:0; padding:0;}
     img{border:0; height:auto; line-height:100%; outline:none; text-decoration:none;}
     table td{border-collapse:collapse; vertical-align:top}
     #backgroundTable{height:100% !important; margin:0; padding:0; width:100% !important;}
     body{ color: #333; font-family:Arial, Helvetica, sans-serif; }
     a { color:#333; text-decoration:none; }
     a:hover { color:#900; }
     a:visited {color: #888 }
     h1, h2, h3, h4 { font-weight: normal}
     #templatePreheader{     color: #777777; font-size: 11px; }
     #templateHeader{ background-color: #222 }
     #utilityNav{ background-color:#000;     font-size:11px; color:#FFF; }
     #utilityNav a{ color: #FFF; }
     #mainNav a { color:#FFF; display: block; font-size:10px; height:26px; padding:4px }
     #mainNav a:hover { background-color: #900; color:#FFF; }
     #submit-article a {background-color: #FC0;color: #454545; font-family:Georgia, "Times New Roman", Times, serif}
     #templateContainer{ background-color:#FBFBF8; border: 1px solid #F5F5F5;}
     #templateBody{ background-color:#F1F1E9; border:1px solid #DDDDDD}
     #highlights, #aroundCALS{ background-color:#FFFFFF; border:5px solid #E6E6DF;}
     #imageAroundCALS{ border:5px solid #F8F9F2; float:left; margin-right: 8px}
     .box-title{ font-size: 11px; font-weight:normal; margin-top: 0; margin-bottom:8px}
     .box-title a { color: #900; }
     .aroundCALS-title{ font-family: Georgia, "Times New Roman", Times, serif; font-size:16px; margin-top:0;}
     .aroundCALS-excerpt { font-size: 12px }
     .arounCALS-image { font-size: 19px;}
     .aroundCALS-image a { color: #fff; height: 302px; width: 72px}
     #highlights-list{ color: #C90; font-size: 16px; line-height: 140%; font-family: Georgia, "Times New Roman"; }
     .more-link { background-color: #EDEDED; font-size: 10px; padding: 4px 2px; text-align: right }
     .news-list{ color: #C90; font-size: 13px;}
     #copywrong{     font-size: 11px}
     #social-media {background-color: #F4F4F4}
</style>
<meta name="robots" content="noindex,nofollow"></meta>
<meta property="og:title" content="eCALS Newsletter - '.date("F d Y", time()).'"></meta>
</head>
';

$message_body='
<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width:100% !important;font-size:16px;-webkit-text-size-adjust:none;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;color:#333;font-family:Arial, Helvetica, sans-serif;" >
    <center>
        <table id="backgroundTable" border="0" cellpadding="0" cellspacing="0"  height="100%" width="100%" style="height:100% !important;margin-top:0;margin-bottom:0;margin-right:0;margin-left:0;padding-top:0;padding-bottom:0;padding-right:0;padding-left:0;width:100% !important;" >
               <tr align="left" valign="top">

                  <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                              <table id="templatePreheader" border="0" cellpadding="10" cellspacing="0" width="700" style="color:#777777;font-size:11px;" >
                          <tr align="left" valign="top">
                             <td align="center" valign="top" width="100%" style="border-collapse:collapse;vertical-align:top;" >
                                 The following is a compilation of all the messages posted 
on <a href="http://ecalsforstudents.cals.wisc.edu" style="color:#333;text-decoration:none;" >eCALS News for Students</a> from '.date("m/d/y", strtotime($start_date)).' to '.date("m/d/y", strtotime($end_date)).'.</td>';
/*$message_body.=                          '<td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                            Is this email not displaying correctly? <a href="http://ecals.cals.wisc.edu/enewsletter/" style="color:#333;text-decoration:none;" >View it in your browser</a>. 
                            </td>';*/
$message_body.='                        </tr>
                    </table>
                    <table id="templateContainer" border="0" cellpadding="0" cellspacing="0"  width="700" style="background-color:#FBFBF8;border-width:1px;border-style:solid;border-color:#F5F5F5;" >
                         <tr align="left" valign="top">
                             <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                 <table id="templateHeader" border="0" cellpadding="0" cellspacing="0"  width="700" style="background-color:#222;" >
                                     <tr align="left" valign="top">
                                         <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                             <table id="utilityNav" border="0" cellpadding="5" cellspacing="0" width="700" style="background-color:#000;font-size:11px;color:#FFF;" >

                                                 <tr align="left" valign="top">
                                                     <td align="right" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                                      <a href="http://ecalsforstudents.cals.wisc.edu/" style="text-decoration:none;color:#FFF;" >eCALS News for Students</a> | <a href="http://cals.wisc.edu/" style="text-decoration:none;color:#FFF;" >CALS</a> | <a href="http://www.wisc.edu/" style="text-decoration:none;color:#FFF;" >UW-Madison</a>                                                    &nbsp;</td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                    <tr align="left" valign="top">
                                         <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                             <table cellpadding ="8" id="arounCALS-image">
                                                 <tr align="left" valign="top">
                                                     <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" ></td>
                                                     <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                         <a href="http://ecalsforstudents.cals.wisc.edu/'.$google_campaign_vars.'" style="color:#333;text-decoration:none;" ><img src="http://ecalsforstudents.cals.wisc.edu/wp-content/themes/ecalsforstudents/images/ecals_logo.png" height="79" width="302" alt="eCALS News for Students -  College of Agricultural and Life Sciences" style="border-width:0;height:auto;line-height:100%;outline-style:none;text-decoration:none;" ></a>

                                                    </td>
                                                </tr>
                                             </table>
                                        </td>
                                    </tr>
                                    <tr align="left" valign="top">
                                         <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                      <table id="mainNav"  border="0" cellpadding="8" cellspacing="0" width="99%">
                                                 <tr align="left" valign="top">

                                                     <td width="2%" style="border-collapse:collapse;vertical-align:top;" ></td>
                                                    <td width="11%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        <a href="'.get_category_link(7).$google_campaign_vars.'" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >CAREER EXPLORATION</a></td>
                                                    <td width="13%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        <a href="'.get_category_link(3).$google_campaign_vars.'" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >ACADEMIC ANNOUNCEMENTS</a></td>
                                                    <td width="15%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        <a href="'.get_category_link(15).$google_campaign_vars.'" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >MESSAGE FROM THE DEAN</a></td>
                                                    <td width="14%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        <a href="'.get_category_link(10).$google_campaign_vars.'" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >EVENTS</a></td>
                                                    <td width="13%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        <a href="'.get_category_link(16).$google_campaign_vars.'" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >RESEARCH</a></td>
                                                    <td width="13%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        <a href="'.get_category_link(18).$google_campaign_vars.'" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >VOLUNTEER</a></td>
                                                    <td width="13%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        <a href="'.get_category_link(12).$google_campaign_vars.'" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >GET MONEY</a></td>
                                                    <td width="11%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" ><a href="http://www.today.wisc.edu/events/feed/30" style="text-decoration:none;color:#FFF;display:block;font-size:10px;height:26px;padding-top:4px;padding-bottom:4px;padding-right:4px;padding-left:4px;" >EVENTS</a>
                                                        </td>
                                                    <td align="center" valign="top" width="7%" style="border-collapse:collapse;vertical-align:top;" >
                                                        </td>
                                                </tr>
                                            </table>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                         <tr align="left" valign="top">
                             <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                 <table id="templateBody" border="0" cellpadding="8" cellspacing="0"  width="700" height="100%" style="background-color:#F1F1E9;border-width:1px;border-style:solid;border-color:#DDDDDD;" >
                                     <tr align="left" valign="top">

                                         <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                             <table id="featured" border="0" cellpadding="4" cellspacing="0"  width="100%">
                                                 <tr align="left" valign="top">
                                                     <td height ="100%" width="50%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                         <table id="aroundCALS" border="0" cellpadding="12" cellspacing="0" style="background-color:#FFFFFF;border-width:5px;border-style:solid;border-color:#E6E6DF;" >';
															 
															 //ACADEMIC ANNOUNCEMENT FEATURE
															 
															 query_posts(array('cat'=>'3', "showposts" => '1'));
															 while (have_posts()) : the_post();
																
																//img url
																if ( get_post_meta($post->ID, 'image', true)) { 
																	$img_src = site_url().get_post_meta($post->ID, "image", $single = true);
																} else {
																	$img_src = get_bloginfo('template_url').'/images/default200x200.jpg';
																}
																
																//excerpt
																$e = explode(' ', get_the_excerpt());
																if(count($e) > 20){
																	$excerpt = implode(' ', array_slice($e, 0, 19)).' [...]';
																}
	
	$message_body.='
                                                             <tr align="left" valign="top">
                                                                 <td valign="top" height="195" style="border-collapse:collapse;vertical-align:top;" >
                                                                    
                                                                     <h1 class="box-title" style="font-size:11px;font-weight:normal;margin-top:0;margin-bottom:8px;" ><a href="#" style="text-decoration:none;color:#900;" >ACADEMIC ANNOUNCEMENTS</a></h1>                                                      <table cellpadding="2" cellspacing="0" border="0"  width="100%">
                                                                         <tr align="left" valign="top">
                                                                             <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" ><a href="'.get_permalink().$google_campaign_vars.'"><img src="'.$img_src.'" alt="'.get_the_title().'" width="115" height="115" id="imageAroundCALS" style="height:auto;line-height:100%;outline-style:none;text-decoration:none;border-width:5px;border-style:solid;border-color:#F8F9F2;float:left;margin-right:8px;" ></a></td>
                                                                            <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" ><h2 class="aroundCALS-title" style="font-weight:normal;font-family:Georgia, \'Times New Roman\', Times, serif;font-size:16px;margin-top:0; " ><a href="'.get_permalink().$google_campaign_vars.'" title="Link to '.get_the_title().'" style="color:#333;text-decoration:none;">'.get_the_title().'</a></h2><p class="aroundCALS-excerpt" style="font-size:12px;" >'.$excerpt.'
                                                                    </p></td>
                                                                        </tr>
                                                                    </table>
																</td>
                                                            </tr>

                                                            <tr align="left" valign="top">
                                                                 <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >                                                                    <p class="more-link" style="background-color:#EDEDED;font-size:10px;padding-top:4px;padding-bottom:4px;padding-right:2px;padding-left:2px;text-align:right;" ><a href="'.get_permalink().'" style="color:#333;text-decoration:none;" >READ MORE</a></p>
</td>
                                                            </tr>';
                                                                    endwhile;     
                                                                         
$message_body.='
                                                        </table>
                                                    </td>
                                                    <td width = "100%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                         <table id="highlights" border="0" cellpadding="12" cellspacing="0" style="background-color:#FFFFFF;border-width:5px;border-style:solid;border-color:#E6E6DF;" >                                            				<tr align="left" valign="top">
                                                                 <td valign="top" height="195" style="border-collapse:collapse;vertical-align:top;" >
                                                                      <h1 class="box-title" style="font-size:11px;font-weight:normal;margin-top:0;margin-bottom:8px;" ><a href="#" style="text-decoration:none;color:#900;" >CAREERS AND OPPORTUNITIES</a></h1>
                                                                    <table cellpadding="2" cellspacing="0" border="0" id="highlights-list" width="100%" style="color:#C90;font-size:16px;line-height:140%;font-family:Georgia, \'Times New Roman\';" >';

															//GET CAREERS AND OPPORTUNITIES 
															query_posts(array('cat'=>'7,11', "showposts" => '2'));
															while (have_posts()) : the_post();

$message_body.=' 
                                                           <tr align="left" valign="top">
                                                             <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >&bull;</td>
                                                            <td align="left" style="border-collapse:collapse;vertical-align:top;" ><a href="'.get_permalink().'" title="Link to '.get_the_title().'" style="color:#333;text-decoration:none;" >'.get_the_title().'</a></td>
                                                        </tr>';
															endwhile;
$message_body.='										</table>
														
														</td>

                                                            </tr>
                                                            <tr align="left" valign="top">
                                                                 <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >                                                                    <p class="more-link" style="background-color:#EDEDED;font-size:10px;padding-top:4px;padding-bottom:4px;padding-right:2px;padding-left:2px;text-align:right;" ><a href="'.get_category_link(7).'" style="color:#333;text-decoration:none;" >READ MORE</a></p>
</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>

                                            </table>
                                        </td>
                                    </tr>
                                    <tr align="left" valign="top">

                                         <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                             <table id="featured" border="0" cellpadding="4" cellspacing="0"  width="100%">
                                                 <tr align="left" valign="top">
                                                     <td height ="100%" width="50%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                         <table id="highlights" border="0" cellpadding="12" cellspacing="0" style="background-color:#FFFFFF;border-width:5px;border-style:solid;border-color:#E6E6DF;" >                                            				<tr align="left" valign="top">
                                                                 <td valign="top" height="195" style="border-collapse:collapse;vertical-align:top;" >
                                                                      <h1 class="box-title" style="font-size:11px;font-weight:normal;margin-top:0;margin-bottom:8px;" ><a href="#" style="text-decoration:none;color:#900;" >STUDENT ORGANIZATIONS</a></h1>
                                                                    <table cellpadding="2" cellspacing="0" border="0" id="highlights-list" width="100%" style="color:#C90;font-size:16px;line-height:140%;font-family:Georgia, \'Times New Roman\';" >';

															//GET EVENTS 
															query_posts(array('cat'=>'17', "showposts" => '1'));
															while (have_posts()) : the_post();

$message_body.=' 
                                                           <tr align="left" valign="top">
                                                             <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >&bull;</td>
                                                            <td align="left" style="border-collapse:collapse;vertical-align:top;" ><a href="'.get_permalink().'" title="Link to '.get_the_title().'" style="color:#333;text-decoration:none;" >'.get_the_title().'</a></td>
                                                        </tr>';
															endwhile;
$message_body.='										</table>
														
														</td>

                                                            </tr>
                                                            <tr align="left" valign="top">
                                                                 <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >                                                                    <p class="more-link" style="background-color:#EDEDED;font-size:10px;padding-top:4px;padding-bottom:4px;padding-right:2px;padding-left:2px;text-align:right;" ><a href="'.get_category_link(17).'" style="color:#333;text-decoration:none;" >READ MORE</a></p>
</td>
                                                            </tr>
                                                        </table>                                                    </td>
                                                    <td width = "100%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                         <table id="highlights" border="0" cellpadding="12" cellspacing="0" style="background-color:#FFFFFF;border-width:5px;border-style:solid;border-color:#E6E6DF;" >                                            				<tr align="left" valign="top">
                                                                 <td valign="top" height="195" style="border-collapse:collapse;vertical-align:top;" >
                                                                      <h1 class="box-title" style="font-size:11px;font-weight:normal;margin-top:0;margin-bottom:8px;" ><a href="#" style="text-decoration:none;color:#900;" >EVENTS</a></h1>
                                                                    <table cellpadding="2" cellspacing="0" border="0" id="highlights-list" width="100%" style="color:#C90;font-size:16px;line-height:140%;font-family:Georgia, \'Times New Roman\';" >';

															//GET EVENTS 
															query_posts(array('cat'=>'17,10', "showposts" => '1'));
															while (have_posts()) : the_post();

$message_body.=' 
                                                           <tr align="left" valign="top">
                                                             <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >&bull;</td>
                                                            <td align="left" style="border-collapse:collapse;vertical-align:top;" ><a href="'.get_permalink().'" title="Link to '.get_the_title().'" style="color:#333;text-decoration:none;" >'.get_the_title().'</a></td>
                                                        </tr>';
															endwhile;
$message_body.='										</table>
														
														</td>

                                                            </tr>
                                                            <tr align="left" valign="top">
                                                                 <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >                                                                    <p class="more-link" style="background-color:#EDEDED;font-size:10px;padding-top:4px;padding-bottom:4px;padding-right:2px;padding-left:2px;text-align:right;" ><a href="'.get_category_link(10).'" style="color:#333;text-decoration:none;" >READ MORE</a></p>
</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                </tr>

                                                <tr align="left" valign="top">
                                                     <td height ="100%" width="50%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                          <table id="highlights" border="0" cellpadding="12" cellspacing="0" style="background-color:#FFFFFF;border-width:5px;border-style:solid;border-color:#E6E6DF;" >                                            				<tr align="left" valign="top">
                                                                 <td valign="top" height="195" style="border-collapse:collapse;vertical-align:top;" >
                                                                      <h1 class="box-title" style="font-size:11px;font-weight:normal;margin-top:0;margin-bottom:8px;" ><a href="#" style="text-decoration:none;color:#900;" >INTERNATIONAL</a></h1>
                                                                    <table cellpadding="2" cellspacing="0" border="0" id="highlights-list" width="100%" style="color:#C90;font-size:16px;line-height:140%;font-family:Georgia, \'Times New Roman\';" >';

															//GET EVENTS 
															query_posts(array('cat'=>'14', "showposts" => '2'));
															while (have_posts()) : the_post();

$message_body.=' 
                                                           <tr align="left" valign="top">
                                                             <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >&bull;</td>
                                                            <td align="left" style="border-collapse:collapse;vertical-align:top;" ><a href="'.get_permalink().'" title="Link to '.get_the_title().'" style="color:#333;text-decoration:none;" >'.get_the_title().'</a></td>
                                                        </tr>';
															endwhile;
$message_body.='										</table>
														
														</td>

                                                            </tr>
                                                            <tr align="left" valign="top">
                                                                 <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >                                                                    <p class="more-link" style="background-color:#EDEDED;font-size:10px;padding-top:4px;padding-bottom:4px;padding-right:2px;padding-left:2px;text-align:right;" ><a href="'.get_category_link(14).'" style="color:#333;text-decoration:none;" >READ MORE</a></p>
</td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td width = "100%" align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                        &nbsp;
                                                    </td>
                                                </tr>
                                                
                                            </table>
                                        </td>
                                    </tr>
                                    <tr align="left" valign="top">
                                         <td align="center" valign="top" bgcolor="#ffffff" style="border-collapse:collapse;vertical-align:top;" >';
                                         
                                         //GET NEWS									 	
											//$cats = array(array(3,4),array(5,6),array(7,'follow'));
											$cats = array(3,9,15,12,18,16,'follow');
											$num_printed_cells = 1;
											for ($i=0; $i<count($cats); $i++){ 
												
												//open table
												if($i==0 || $open_table==1){
													$open_table=0;
													$message_body.='<table id="news" width="98%" border="0" cellpadding="16" cellspacing="0">
													<tr align="left" valign="top">';
												}
												
                                                 									 
												if($cats[$i]!='follow'){
													
													//GET CATEGORY ARTICLES
													//get list of children categories for current cat
													//so their posts are also included in list
													$categories=  get_categories('child_of='.$cats[$i]); 
													$cats_in = $cats[$i];
													//print_r($categories);
													foreach ($categories as $cat){
														//make list of categories (parent + children cats)
														$cats_in.= ", '".$cat->cat_ID."'";
													}
													
													$query ="SELECT DISTINCT ID, post_title, post_date 
																FROM $wpdb->posts, $wpdb->term_taxonomy, $wpdb->term_relationships 
																WHERE $wpdb->term_taxonomy.taxonomy = 'category' 
																AND $wpdb->term_taxonomy.term_id IN (".$cats_in.")
																AND $wpdb->posts.post_type = 'post' 
																AND ($wpdb->posts.post_status = 'publish') 
																AND post_date >= '$start_date 00:00:00' 
																AND post_date <= '$end_date 23:59:59' 
																AND $wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id 
																AND $wpdb->term_relationships.object_id = $wpdb->posts.ID 
																ORDER BY $wpdb->posts.post_date DESC";
																
													$posts = $wpdb->get_results($query);	
													//echo '<p>'.count($posts).'</p>';
													//echo $query;
													
													//if there are posts in the category, print them
													if ($wpdb->num_rows > 0) {
                                                     
$message_body.='									<td width="50%" style="border-collapse:collapse;vertical-align:top;" >
                                                          <h1 class="box-title" style="font-size:11px;font-weight:normal;margin-top:0;margin-bottom:8px;" ><a href="'.get_category_link($cats[$i]).'" style="text-decoration:none;color:#900;" >'.strtoupper(get_cat_name($cats[$i])).'</a></h1>

                                                      <table cellpadding="2" cellspacing="0" border="0" class="news-list" width="100%" style="color:#C90;font-size:13px;" >';
																	foreach ($posts as $post) :
																		setup_postdata($post);
														
														
$message_body.='                                         <tr align="left" valign="top">
															<td valign="top" style="border-collapse:collapse;vertical-align:top;" >&bull;</td>
															<td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
															<a href="'.get_permalink($post->ID).$google_campaign_vars.'" style="color:#333;text-decoration:none;"  title="Link to '.get_the_title($post->ID).'">'.get_the_title($post->ID).'</a></td>
                                                        </tr>';
																	endforeach;
$message_body.=' 										</table>
													</td>';
													
													//increase count of printed cells
													$num_printed_cells++;
													}
                                                    
												} else {
															 
$message_body.='									<td width="334" align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
																  <h1 class="box-title" style="font-size:11px;font-weight:normal;margin-top:0;margin-bottom:8px;" >FOLLOW CALS ON<br >
																	 <br >
															</h1>
															  <table id="social-media"  border="0" cellspacing="0" cellpadding="8" style="background-color:#F4F4F4;" width="50%">
																<tr align="left" valign="top">
																  <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" ><a href="http://www.facebook.com/UWMadisonCALS" style="color:#333;text-decoration:none;" ><img src="http://ecals.cals.wisc.edu/wp-content/themes/ecals/images/icon-fb.jpg" title="Facebook" alt="Facebook" style="border-width:0;height:auto;line-height:100%;outline-style:none;text-decoration:none;" ></a></td>
		
																  <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" ><a href="http://twitter.com/uwmadisoncals" style="color:#333;text-decoration:none;" ><img src="http://ecals.cals.wisc.edu/wp-content/themes/ecals/images/icon-tw.jpg" title="Twitter" alt="Twitter" style="border-width:0;height:auto;line-height:100%;outline-style:none;text-decoration:none;" ></a></td>
																  <td align="center" valign="top" style="border-collapse:collapse;vertical-align:top;" ><a href="http://news.cals.wisc.edu/feed/" style="color:#333;text-decoration:none;" ><img src="http://ecals.cals.wisc.edu/wp-content/themes/ecals/images/icon-rss.jpg" title="RSS" alt="RSS" style="border-width:0;height:auto;line-height:100%;outline-style:none;text-decoration:none;" ></a></td>
																</tr>
															  </table>
													 </td>';
													$num_printed_cells++;
													}
											
												//close table
												if(($num_printed_cells % 2)){
													$open_table=1;
	
	$message_body.='                                  </tr>                             
												</table>';
												}
											}
                                            
$message_body.='                                        </td>
                                    </tr>

                                     <tr align="left" valign="top">
                                         <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                             <table id="copywrong" width="98%" border="0" cellpadding="8" cellspacing="0" style="font-size:11px;" >
                                                 <tr align="left" valign="top">
                                                     <td align="left" valign="top" style="border-collapse:collapse;vertical-align:top;" >
                                                         <p align="left" valign="top">&copy; Copyright 2012. All rights reserved. <a href="http://www.cals.wisc.edu/" >College of Agricultural and Life Sciences</a> - <a href="http://www.wisc.edu/" >University of Wisconsin-Madison</a>.<br>
                                                           <br>
                                                        </p>
                                                    </td>
                                                  </tr>
                                            </table>
                                        </td>
                                    </tr>                                    
                                </table>
                            </td>

                        </tr>
                        </table>
              </td>
            </tr>
        </table>
        &nbsp;
     </center>
</body>
</html>
';
	
 $output = $message_head.$message_body;

 return $output;
 
}

function ecals_mail_2_scripts(){ 
	 wp_enqueue_script('jquery');
	 
	 ?>
	 <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" />
	 <script src="http://code.jquery.com/jquery-1.8.3.js"></script>
  <script src="http://code.jquery.com/ui/1.10.0/jquery-ui.js"></script>
    <!--<script src="<?php echo get_bloginfo("template_directory");?>/includes/js/ui.datepicker.js" type="text/javascript"></script>-->
     
	<script type="text/javascript">
	//disable deadlines category
	
	jQuery(document).ready(function($){
		jQuery(function() {
			console.log(jQuery("#start").attr("value"));
			jQuery("#start").datepicker();
			jQuery("#end").datepicker();
		});
		
	
	
	});
    </script>
<?php }

add_action ('admin_print_footer_scripts', 'ecals_mail_2_scripts');
?>