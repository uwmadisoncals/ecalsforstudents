<?php //CUSTOM default image

/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'ecals_post_default_image');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'ecals_save_post_default_image');

/* Run scripts for box*/
add_action ('admin_print_footer_scripts', 'ecals_post_default_image_scripts');


/* Adds a custom section to the "advanced" Post and Page edit screens */
function ecals_post_default_image() {
    add_meta_box( 'ecals_pfi_sectionid', __( 'Post\'s defaul Image', 'ecals_pfi_textdomain' ), 
                'ecals_pfi_inner_custom_box', 'post', 'advanced' );
}
   
/* Prints the inner fields for the custom post/page section */
function ecals_pfi_inner_custom_box() {
  global $post;
  global $wpdb;
  $post_id = $post->ID;

  //get images associated to current post
	$post_images = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_parent = '$post_id' AND post_parent <> '0' AND post_status = 'inherit' AND post_type='attachment' AND (post_mime_type='image/gif' OR post_mime_type='image/jpeg' OR post_mime_type='image/png') ORDER BY post_date DESC", "ARRAY_A");

  // Use nonce for verification
  echo '<input type="hidden" name="ecals_pfi_noncename" id="ecals_pfi_noncename" value="' . 
  wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

  // The actual fields for data entry 
  ?>
  <p class="meta-options"></p>

<?php
	if (!empty($post_images)):
		
		foreach( $post_images as $image):
		//check whether an image has already been chosen by comparing $att_id
		
		echo get_attachment_icon($image["ID"]);
		//echo $att_id["guid"];
		
		endforeach;
	endif;
?>

 
  <?php

}


/* When the post is saved, saves our custom data */
function ecals_save_post_default_image( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['ecals_pfi_noncename'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  if ( !current_user_can( 'edit_post', $post_id )){
      return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data
  /*if ($_POST["ecals_post_has_deadline"]==1){
  	$mydata = $_POST['dl_mm']."/".$_POST['dl_jj']."/".$_POST['dl_yy'];

	// Save ecals_post_default_image as custom field
	 add_post_meta($post_id, 'ecals_post_default_image', date("Y-m-d H:i:s", strtotime($mydata)), true) or update_post_meta($post_id, 'ecals_post_default_image', date("Y-m-d H:i:s", strtotime($mydata)));
  
  } else {
  	delete_post_meta($post_id, 'ecals_post_default_image');
  }*/
}

function ecals_post_default_image_scripts(){ ?>
<!--	<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script> 
	<script type="text/javascript">
	//disable deadlines category
	$(document).ready(function(){
		
		$('#in-category-28').attr("disabled", "disabled");
	
	
		$('#save-post').click(function(){
	        $('#in-category-28').removeAttr('disabled');			
		});
		
		$('#publish').click(function(){
	        $('#in-category-28').removeAttr('disabled');			
		});		
	});
	

	
    function toggleStatus() {
    if ($('#ecals_post_has_deadline').is(':checked')) {
        $('#ecals_deadline_fields :input').removeAttr('disabled');
        $('#in-category-28').attr("checked", "checked");

	} else {
        $('#ecals_deadline_fields :input').attr("disabled", "disabled");
		$('#in-category-28').removeAttr('checked');
		
	}   
}
    </script>
--><?php }


//END OF default image 

?>
