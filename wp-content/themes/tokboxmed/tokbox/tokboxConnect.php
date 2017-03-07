<?php
	global $wpdb;
	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
	require get_template_directory() . '/tokbox/opentok.phar';
	require WP_PLUGIN_DIR . '/composer/vendor/autoload.php';
	use OpenTok\OpenTok;
	use OpenTok\Session;
	use OpenTok\Role;	
if( is_user_logged_in()) {	

	  //получаем данные о сессии
	$resultRow = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."tokbox");
	foreach( $resultRow as $row) {
		$sessionTok = $row->sessionTok;
		$aboutConferenceText = $row->aboutConferenceText;
		$apiKey = $row->apiKey;
		$apiSecret = $row->apiSecret;
	}
	
	if(!empty($sessionTok)){
			$opentok = new OpenTok($apiKey, $apiSecret);	
			$token = $opentok->generateToken($sessionTok,array(
					    'role'       => Role::PUBLISHER,  
					    'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
					    'data'       => ''.$aboutConferenceText.',user,'.get_current_user_id().''
					)
			);	
	} 
}
?>