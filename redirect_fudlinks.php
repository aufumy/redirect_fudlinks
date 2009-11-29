<?php
/*
Plugin Name: Redirect FUDForum links
Plugin URI: http://github.com/aufumy/redirect_fudlinks
Description: Redirect FUDForum links to BBPress
Author: Audrey Foo
Version: 0.01
Author URI: http://www.coderamblings.com
*/

//HOOK
add_action( 'bb_index.php_pre_db', 'redirect_fudlinks' );
$site = apply_filters( 'bb_uri', bb_get_uri( $resource, $query, $context ), $resource, $query, $context );
	
function redirect_fudlinks() {
  global $bbdb;
  if ( $_GET['t'] == 'msg' || $_GET['t'] == 'rview' ) {

    // If msg id specified show that post
    if ( is_numeric($_GET['goto']) && !empty($_GET['goto']) ){
      $msg_id = $_GET['goto'];
      $sql = "SELECT bb_posts_post_id AS post_id, bb_posts_topic_id AS topic_id FROM map_posts WHERE fud_msg_id = $msg_id";
      $obj = $bbdb->get_row( $sql );
      wp_redirect( $site .'topic.php?id='. $obj->topic_id .'#post-'. $obj->post_id, 301 );

    // If no msg id specified, show the topic
    } elseif ( is_numeric($_GET['th']) && !empty($_GET['th']) ){
      // Get the corresponding topic id
      $sql = "SELECT bb_posts_topic_id AS topic_id FROM map_posts WHERE fud_msg_id = $msg_id";
      $obj = $bbdb->get_row( $sql );
      wp_redirect( $site .'topic.php?id='. $obj->topic_id, 301 );
    }

  // Redirect to appropriate forum
  } elseif ( $_GET['t'] == 'thread' ) {
    $forums = array(7 => 1, 8 => 2, 9 => 3, 10 => 4, 15 => 5, 17 => 6, 18 => 7, 25 => 8, 29 => 10);
    wp_redirect( $site .'forum.php?id='. $forums[$_GET['frm_id']], 301 );
  }
}
?>
