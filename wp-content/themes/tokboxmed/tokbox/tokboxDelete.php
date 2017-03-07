<?php
	global $wpdb;
	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
	if(is_super_admin()){
		$wpdb->query( "UPDATE ".$wpdb->prefix."tokbox SET sessionTok=''");
		$wpdb->query( "UPDATE ".$wpdb->prefix."users SET status_tokbox_get='0',status_tokbox_post='0'");
		session_destroy();	
	}
?>

