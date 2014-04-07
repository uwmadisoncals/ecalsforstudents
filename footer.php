<hr />
<div id="footer">
<div id="utility_nav_bar_footer">
<div class="utility_links">
<a href="<?php echo get_option('home');?>" title="Home">Home</a> |
<a href="<?php echo get_page_link('10020');?>" title="eCALS RSS Feeds">Subscribe</a> |
<a href="<?php echo get_page_link('11804');?>" title="Resources for faculty and Staff">Fac & Staff Links</a> |
<a href="<?php echo get_page_link('9976');?>" title="About eCALS">About eCALS</a> |
<a href="http://www.cals.wisc.edu" title="College of Agricultural and Life Sciences">CALS</a> |
<a href="http://www.wisc.edu" title="University of Wisconsin-Madison">UW-Madison</a> |
<?php
if ( is_user_logged_in() ) {?>
 <a class="signin" href="<?php echo wp_logout_url(); ?>" title="Log out">Log out</a>
<?php } else { ?>
 <a class="signin" href="<?php echo wp_login_url(); ?>" title="Log In">Log In</a>
<?php }?>
</div>
</div>
<div id="copywrong">
&copy; Copyright <?php echo date('Y', time());?>. All rights reserved. <a href="http://www.cals.wisc.edu" target="_blank">College of Agricultural and Life Sciences</a> - <a href="http://www.wisc.edu" target="_blank">University of Wisconsin-Madison</a>.</div>
</div>
</div>
<?php wp_footer(); ?>
<script type="text/javascript" src="http://jqueryjs.googlecode.com/files/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?php bloginfo("stylesheet_directory");?>/includes/js/jquery.idTabs.min.js"></script>
</body>
</html>