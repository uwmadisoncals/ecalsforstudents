<?php 

//CUSTOM DEADLINE FIELD 

/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'ecals_add_custom_box');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'ecals_save_postdata');

/* Adds a custom section to the "advanced" Post and Page edit screens */
function ecals_add_custom_box() {
    add_meta_box( 'ecals_sectionid', __( 'Post Deadline', 'ecals_textdomain' ), 
                'ecals_inner_custom_box', 'post', 'advanced' );
}
   
/* Prints the inner fields for the custom post/page section */
function ecals_inner_custom_box() {
  global $post;
  $post_id = $post->ID;
  $deadline = get_post_meta($post_id, 'ecals_post_deadline', true);
  if (!empty($deadline)){
	  $has_deadline = true;
	  $mm = date("m", strtotime($deadline));
	  $jj = date("d", strtotime($deadline));
	  $yy = date("Y", strtotime($deadline));
  }

  // Use nonce for verification
  echo '<input type="hidden" name="ecals_noncename" id="ecals_noncename" value="' . 
  wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

  // The actual fields for data entry 
  
  ?>
  <p class="meta-options">
    <label class="selectit" for="ecals_post_has_deadline">
    	<input id="ecals_post_has_deadline" name="ecals_post_has_deadline" type="checkbox" tabindex="1" value="1" onchange="toggleStatus()" <?php if ($has_deadline){ echo "checked=\"checked\"";}?>/> 
    	There is a deadline announced in this post:
    </label>
  <br />
	<div class="inside">
      <div id="ecals_deadline_fields">

    <label for="dl_mm" class="hidden">Deadline Date </label>
    <select name="dl_mm" id="dl_mm" tabindex="2" <?php if (!$has_deadline){ echo "disabled=\"disabled\"";}?>>
	  <?php
      for ($i=1; $i<=12; $i++){ 
	  $ts = strtotime("$i/1/1970");?>
      <option  value="<?php echo date('m', $ts);?>" <?php if ($mm == date('m', $ts)){ echo "selected=\"selected\"";}?>><?php echo date('M', $ts);?></option>
      <?php } ?>
    </select> / 
    
     <input  name="dl_jj" type="text" id="dl_jj" size="2" maxlength="2"  tabindex="3" <?php if (!$has_deadline){ echo "disabled=\"disabled\"";}?>  value="<?php echo $jj;?>"/> /
     <input  name="dl_yy" type="text" id="dl_yy" size="4" maxlength="4"  tabindex="4" <?php if (!$has_deadline){ echo "disabled=\"disabled\"";}?> value="<?php echo $yy;?>"/> 
     (i.e. Aug/08/2009)


</div></div>
 </p>
 
  <?php

}


/* When the post is saved, saves our custom data */
function ecals_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['ecals_noncename'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  if ( !current_user_can( 'edit_post', $post_id )){
      return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data
  if ($_POST["ecals_post_has_deadline"]==1){
  	$mydata = $_POST['dl_mm']."/".$_POST['dl_jj']."/".$_POST['dl_yy'];

	// Save ecals_post_deadline as custom field
	 add_post_meta($post_id, 'ecals_post_deadline', date("Y-m-d H:i:s", strtotime($mydata)), true) or update_post_meta($post_id, 'ecals_post_deadline', date("Y-m-d H:i:s", strtotime($mydata)));
  
  } else {
  	delete_post_meta($post_id, 'ecals_post_deadline');
  }
}

function ecals_post_deadline_scripts(){ 

 wp_enqueue_script('jquery');

?>
	<script type="text/javascript">
	//disable deadlines category
	
	jQuery(document).ready(function($){
		
		jQuery('#in-category-28').attr("disabled", "disabled");
	
	
		jQuery('#save-post').click(function(){
	        $('#in-category-28').removeAttr('disabled');			
		});
		
		jQuery('#publish').click(function(){
	        jQuery('#in-category-28').removeAttr('disabled');			
		});		
	});
	

	
    function toggleStatus() {
    if (jQuery('#ecals_post_has_deadline').is(':checked')) {
        jQuery('#ecals_deadline_fields :input').removeAttr('disabled');
        jQuery('#in-category-28').attr("checked", "checked");

	} else {
        jQuery('#ecals_deadline_fields :input').attr("disabled", "disabled");
		jQuery('#in-category-28').removeAttr('checked');
	}   
}
    </script>
<?php }

add_action ('admin_print_footer_scripts', 'ecals_post_deadline_scripts');

//END OF CUSTOM DEADLINE FIELD ?>