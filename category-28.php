<?php //redirect to deadlines page
	  if (is_category(28)){ 
	  extract($_GET);
	  header ("Location:".get_page_link(10712)."?utm_source=$utm_source&utm_medium=$utm_medium&utm_campaign=$utm_campaign");}
?>