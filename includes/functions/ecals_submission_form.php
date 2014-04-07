<?php 

function ecals_submission_form(){


 ?>

<form id="myForm" action="/wp-content/themes/ecals/functions/ecals_submission_form_p.php" method="post"> 
	<input type="text" id="title" name+"title" />
    <input type="text" id="post-body" name="post-body" />
    <input type="submit" value="Submit Comment" /> 
</form>

<div id="output"></div>

<?php }

function ecals_submission_form_scripts(){ ?>
<script language="javascript" type="text/javascript">
	jQuery(function($){
        // wait for the DOM to be loaded 
        $(document).ready(function() { 
            
			var options = {
				target: '#output',
				success: showResponse
				}

            $('#myForm').ajaxForm(options); 
        }); 	
	});
	
	function showResponse(responseText){
		//alert(responseText);
	
	}

</script>

<?php }





//display submission form
	//ecals_submissions_form();
	
//validate form
	//notify of errors
	


	
	


//load jquery and jquery form plugin 
wp_enqueue_script( 'jquery', $src, $deps, $ver, true);
wp_enqueue_script( 'jquery-form', $src, $deps, $ver, true);

//load ecals_submission_form_scripts
add_action ('print_footer_scripts', 'ecals_submission_form_scripts');

?>