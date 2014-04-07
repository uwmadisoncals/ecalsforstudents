<?php
	
	//display message if user has been redirected from old bookmark 
	session_start();
	if ($_GET['r']==1){
		//register session var, reload withour r variable
		$_SESSION['r']=1;
		//reload -> message will be displayed in single.php 
		header("Location: ".$_SERVER['REDIRECT_SCRIPT_URI']);
	}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="google-site-verification" content="qkm3cnTOfvuQuGdSbkMKjcEnzuXvtrv35EAdLI8o_RM" />
<title>eCALS News for Students</title>
<link type="text/css" href="http://ecalsforstudents.cals.wisc.edu/wp-content/themes/ecalsforstudents/style.css" rel="stylesheet" media="screen" />
<!--[if lt IE 8]><link rel="stylesheet" href="http://ecals.cals.wisc.edu/wp-content/themes/ecals/styles/ie.css" type="text/css" media="screen" /><![endif]-->
<link rel="stylesheet" href="http://ecalsforstudents.cals.wisc.edu/wp-content/themes/ecalsforstudents/style.css" type="text/css" media="print" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="alternate" type="application/atom+xml" title="<?php bloginfo('name'); ?> Atom Feed" href="<?php bloginfo('atom_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php if ( is_singular() ) wp_enqueue_script( 'comment-reply' ); ?>
<?php wp_head(); ?>
</head>
<body>
<div id="page">
 <!--[if lt IE 7]><div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>
<div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>
<div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'><div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div><div style='width: 275px; float: left; font-family: Arial, sans-serif;'>
<div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>You are using an outdated browser</div>
<div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>For a better experience using this site, please upgrade to a modern web browser.</div></div><div style='width: 75px; float: left;'><a href='http://www.firefox.com' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div><div style='width: 75px; float: left;'><a href='http://www.browserforthebetter.com/download.html' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-ie8.jpg' style='border: none;' alt='Get Internet Explorer 8'/></a></div><div style='width: 73px; float: left;'><a href='http://www.apple.com/safari/download/' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-safari.jpg' style='border: none;' alt='Get Safari 4'/></a></div><div style='float: left;'><a href='http://www.google.com/chrome' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>
</div></div><![endif]-->
<div id="header">
<div id="utility_nav_bar">
<div class="utility_links">
<a href="<?php echo get_option('home');?>" title="Home">Home</a> |
<a href="http://sc.cals.wisc.edu" title="CALS Student Council">CALS Student Council</a> |
<a href="http://www.cals.wisc.edu" title="College of Agricultural and Life Sciences">CALS</a> |
<a href="http://grow.cals.wisc.edu" title="Grow Magazine">Grow Magazine</a> |
<a href="http://news.cals.wisc.edu" title="CALS News">CALS News</a> |
<a href="http://www.wisc.edu" title="University of Wisconsin-Madison">UW-Madison</a> |
<?php
if ( is_user_logged_in() ) {?>
 <a class="signin" href="<?php echo wp_logout_url(); ?>" title="Log out">Log out</a>
<?php } else { ?>
 <a class="signin" href="<?php echo wp_login_url(); ?>" title="Log In">Log In</a>
<?php }?>
</div>
</div>
<div id="headerimg">
<div id="logo">
<a href="<?php echo get_option('home'); ?>/">
<img height="113px" width="430px" src="<?php bloginfo('template_url'); ?>/images/ecals_logo.png" alt="<?php bloginfo('name'); ?>" />
</a>
</div>
<div id="search"><?php get_search_form(); ?></div>
<div id="nav_bar">
<ul id="nav2">
<?php echo wp_list_categories("title_li=&exclude=1,17,28&hide_empty=0&depth=0");?>
</ul>
</div>
</div>
</div><hr />