<?php
/*
Template Name: Page Awards and Recognitions
*/

get_header();
?>

	<div id="content" class="widecolumn">


		<h2 class="pagetitle">List of Awards and Recognitions</h2>
<!--        <div class="archivefeed">
                    <a href="/feed/" class="rss_link"> RSS feed for this section</a>        </div>
-->		
		
		<?php 
		if ($_GET["start"]!="" && $_GET["end"]!=""){
			/*$sm = date("m", strtotime($_GET["start"]));
			$sd = date("d", strtotime($_GET["start"]));
			$sy = date("Y", strtotime($_GET["start"]));
			$em = date("m", strtotime($_GET["end"]));
			$ed= date("d", strtotime($_GET["end"]));
			$ey = date("Y", strtotime($_GET["end"]));
			
			if (checkdate($sm, $sd, $sy)==true && checkdate($em, $ed, $ey)==true) {*/

				function filter_where($where = '') {
					//global $_GET;
					$start = implode("/", explode("-", $_GET["start"]));
					$end = implode("/", explode("-", $_GET["end"]));
					$where .= " AND post_date >= '".date("Y-m-d", strtotime($start))."' AND post_date <= '".date("Y-m-d", strtotime($end))."'";
					//echo $where;
					return $where;
	
				}
				
				add_filter('posts_where', 'filter_where');
			
			/*} else {
				echo "End date is not valid. Please use mm-dd-yyyy format</ br>";
			}*/
		}

		
		$page = (get_query_var('paged')) ? get_query_var('paged') : 0;
		//query_posts('orderby=date&order=desc&posts_per_page=10&paged='.($page));
		query_posts('cat=16,47&orderby=date&order=desc&posts_per_page=100&paged='.($page));
	    ?>
		<?php while (have_posts()) : the_post(); ?>
		<div <?php post_class() ?>>
				<h2 id="post-<?php the_ID(); ?>"><a href="<?php the_permalink() ?>" rel="bookmark" title="Permanent Link to <?php the_title_attribute(); ?>"><?php the_title(); ?><?php if (in_category("28", $post->ID)){ echo " <span class=\"date_due\">(Due ".date("M d", strtotime(get_post_meta($post->ID, 'ecals_post_deadline', true))).")</span>";}?></a></h2>
				<small><?php the_time('l, F jS, Y') ?></small>
				<p class="postedin"><?php //the_tags('Tags: ', ', ', '<br />'); ?> 
				  <strong>Posted in:</strong>
<?php the_category(', ') ?> | <?php edit_post_link('Edit', '', ' | '); ?> </p>
<div class="entry"><?php the_content();?></div>

	  </div>

		<?php endwhile; ?>

		<div class="navigation">
			<div class="alignleft"><?php next_posts_link('&laquo; Older Entries') ?></div>
			<div class="alignright"><?php previous_posts_link('Newer Entries &raquo;') ?></div>
		</div>
	</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
