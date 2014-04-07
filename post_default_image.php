<?php //CUSTOM default image

/* Use the admin_menu action to define the custom boxes */
add_action('admin_menu', 'ecals_post_default_image');

/* Use the save_post action to do something with the data entered */
add_action('save_post', 'ecals_save_post_default_image');

/* Run scripts for box*/
add_action ('admin_print_footer_scripts', 'ecals_post_default_image_scripts');


/* Adds a custom section to the "advanced" Post and Page edit screens */
function ecals_post_default_image() {
    add_meta_box( 'ecals_pdi_sectionid', __( 'Post\'s Default Image', 'ecals_pdi_textdomain' ), 
                'ecals_pdi_inner_custom_box', 'post', 'advanced' );
}
   
/* Prints the inner fields for the custom post/page section */
function ecals_pdi_inner_custom_box() {
  global $post;
  global $wpdb;
  $post_id = $post->ID;

  //get images associated to current post
	$post_images = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE post_parent = '$post_id' AND post_parent <> '0' AND post_status = 'inherit' AND post_type='attachment' AND (post_mime_type='image/gif' OR post_mime_type='image/jpeg' OR post_mime_type='image/png') ORDER BY post_date ASC", "ARRAY_A");

  // Use nonce for verification
  echo '<input type="hidden" name="ecals_pdi_noncename" id="ecals_pdi_noncename" value="' . 
  wp_create_nonce( plugin_basename(__FILE__) ) . '" />';

  // The actual fields for data entry 
  ?>

  <p class="meta-options">
    <label class="selectit" for="post_has_custom_image">
    	<input id="post_has_custom_image" name="post_has_custom_image" type="checkbox" tabindex="1" value="1" onchange="toggleStatus_()" <?php if ($has_deadline){ echo "checked=\"checked\"";}?>/> 
    	Set default image for this post:
    </label>
  <br />
	<div class="inside">

<?php
	if (!empty($post_images)): ?>
        <div style="position: relative;" id="s2" class="pics">

<?php
		foreach( $post_images as $image):
		//check whether an image has already been chosen by comparing $att_id
		?>
		<div>
        
        <?php echo get_attachment_icon($image["ID"]); 
		//echo $att_id["guid"];?>
        
		</div>
		<?php
		endforeach;?>
       
        </div>
        <div class="nav"><a id="prev2" href="#">Prev</a> <a id="next2" href="#">Next</a></div>
        
<?php	else:
	
		echo "No images have been added to this post. "; post_image_media_button();
	
	endif; ?>
	</div>
    </p>
 
  <?php

}


/* When the post is saved, saves our custom data */
function ecals_save_post_default_image( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['ecals_pdi_noncename'], plugin_basename(__FILE__) )) {
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

function ecals_post_default_image_scripts(){ 

	wp_enqueue_script('jquery.cycle.all.min', '/wp-content/themes/ecals/includes/js/jquery.cycle.all.min.js');

?>
	<script type="text/javascript">
	jQuery(document).ready(function($){
		
		jQuery('#s2').cycle({ 
			fx:     'fade', 
			speed:  'fast', 
			timeout: 0, 
			next:   '#next2', 
			prev:   '#prev2' 
		});
	});
    </script>

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

/* Function post_image_media_button is based on media_buttons() function on includes/media.php*/
function post_image_media_button() {
	global $post_ID, $temp_ID;
	$uploading_iframe_ID = (int) (0 == $post_ID ? $temp_ID : $post_ID);
	$context = apply_filters('media_buttons_context', __('%s'));
	$media_upload_iframe_src = "media-upload.php?post_id=$uploading_iframe_ID";
	$media_title = __('Add Media');
	$image_upload_iframe_src = apply_filters('image_upload_iframe_src', "$media_upload_iframe_src&amp;type=image");
	$image_title = __('Add an Image');
	
	$out = <<<EOF

	<a href="{$image_upload_iframe_src}&amp;TB_iframe=true" id="add_image" class="thickbox" title='$image_title' onclick="return false;">Upload or insert an image <img src='images/media-button-image.gif' alt='$image_title' /></a>

EOF;
	printf($context, $out);
}

?>