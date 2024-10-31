<?php
/*
Plugin Name: Round Top Post
Plugin URI: 
Description: It help your to view your recent popular post anywhere nicely. Where you want view your recent popular please past this code here <?php if(function_exists('round_top_posts')){round_top_posts();} ?>.
Version: 0.01
Author URI:http://www.projapotibd.com/author/osmansorkar
*/

if ( is_admin() ) : // Load only if we are viewing an admin page
function round_top_option_initt() {
	// Register settings and call sanitation functions
	register_setting( 'round_top', 'round_top_op' );
}
add_action( 'admin_init', 'round_top_option_initt' );
function round_top_add_plugin_page() {
	// Add theme options page to the addmin menu
	add_options_page( 'Round Top Post', 'Round Top Post', 'edit_plugins', 'round_top_post', 'round_top_post' );
}

add_action( 'admin_menu', 'round_top_add_plugin_page' );

// Function to generate options page
function round_top_post() {?>
<div class="wrap" style="margin-left:50px;">
<?php screen_icon(); ?>
<h2>Round Top Post Management page</h2>

<div style="width:600px">

<form method="post" action="options.php"> 
<?php settings_fields( 'round_top' ); ?>
<?php 	$defaults = array(
    'days' => '7',
  'title' => 'Top Post'

);
	 $round_top_op =wp_parse_args(get_option('round_top_op'), $defaults); ?>
<table style="text-align:left">

<tr><th><label for="Title">Title</label></th><th>&nbsp;</th>
<td><input id="bgc" type="text" name="round_top_op[title]" value="<?php echo $round_top_op['title']; ?>" /></td></tr>

<tr><th><label for="Days">Top Post between </label></th><th>&nbsp;</th>
<td><input id="lbg-color" type="text" name="round_top_op[days]" value="<?php echo $round_top_op['days']; ?>" /> days</td></tr>


</table>
</div>
<?php submit_button(); ?>
</form>
</div>
<?php
}
endif;  // EndIf is_admin()

/************* Create style for Round Top Post***************/
function round_top_style(){?>
<style type="text/css">
#round_top_post{
	width:1000px;
	border:1px solid #ccc;
	height:150px;
	overflow:hidden;
	}
#round_top_post ul{
	position:relative;
	list-style:none;
	padding:0;
	margin: 0 0 0 30px;
	
}
#round_top_post li{
	float:left;
}

#round_top_post li .avatar {
    border: 1px solid #CCCCCC;
    height: 0px;
    margin-left: 70px;
	margin-top: -28px;
    padding: 2px;
    position: absolute;
    width: 56px;
    z-index: 10;
	visibility:hidden;
	border-radius:50px;
	transition:height .5s;
	-webkit-transition:height .5s;
	-moz-transition:height .5s;
	-o-transition:height .5s;
	-ms-transition:height .5s;
	
}

#round_top_post li:hover .avatar{
	visibility:visible;
	height:56px;
}

#round_top_post li .img{
		border-radius:50px;
	-moz-border-radius:50px;
	padding:5px;
	border:1px solid #ccc;
	margin:5px;
	width:70px;
	height:70px;
}

#round_top_post #top_post {
    margin: 0 auto;
    padding: 0;
    text-align: center;
}

#round_top_post li h2 {
    margin: -40px 0 0;
    padding: 0;
    position: absolute;
    text-align: center;
    width: 1000px;
	left:0;
	visibility:hidden;
	transition:margin .3s;
	-webkit-transition:margin .3s;
	-moz-transition:margin .3s;
	-o-transition:margin .3s;
	-ms-transition:margin .3s;
}

#round_top_post li:hover h2{
	visibility:visible;
	 margin: -10px 0 0;
}
</style>
<?php
	}
add_action('wp_head','round_top_style');
/*********** count post view************************/
function round_top_post_count($postID) {
$defaults = array(
  'days' => '7',
  'title' => 'Top Post'

);
	 $round_top_op =wp_parse_args(get_option('round_top_op'), $defaults);
	 $days=$round_top_op['days']*24*60*60;
	$time=time()-$days;
	$post_time=get_post_time('U', true);
    $count_key = 'round_top_post_count';
    $count = get_post_meta($postID, $count_key, true);
	if($post_time >= $time){	
	 if($count==''){
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }else{
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
	
	}
	else{
		delete_post_meta($postID, $count_key);
	}
   
}

function round_top_post_views ($post_id) {
    if ( !is_single() ) return;
    if ( empty ( $post_id) ) {
        global $post;
        $post_id = $post->ID;    
    }
    round_top_post_count($post_id);
}
add_action( 'wp_head', 'round_top_post_views');


function round_top_posts(){?>
<div id="round_top_post">
<?php
$defaults = array(
  'days' => '7',
  'title' => 'Top Post'

);
	 $round_top_op =wp_parse_args(get_option('round_top_op'), $defaults);
	 $round_top_op['title'];
?>
<h2 id="top_post"><?php echo $round_top_op['title']; ?></h2>
<ul>

   <?php $popularpost = new WP_Query( array( 'posts_per_page' => 10, 'meta_key' => 'round_top_post_count', 'orderby' => 'meta_value_num', 'order' => 'DESC'  ) );
while ( $popularpost->have_posts() ) : $popularpost->the_post(); ?>  
               
 <li><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?>

<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'twentyten' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_post_thumbnail('thumbnail',array('style'=>'','class'=>'img'));?></a>

<h2 class="post-title"> <?php the_title(); ?></h2>
</li>              

               <?php endwhile; ?>   
                <?php wp_reset_query(); ?> 
                   
</ul>
</div>
<?php
		}
?>