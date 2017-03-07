<?php
	global $wpdb;
	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
	require get_template_directory() . '/tokbox/opentok.phar';
	require WP_PLUGIN_DIR . '/composer/vendor/autoload.php';
	use OpenTok\OpenTok;
	use OpenTok\MediaMode;
	use OpenTok\ArchiveMode;
	use OpenTok\Session;
	use OpenTok\Role;	
if(is_super_admin()){	  
	  //получаем данные о сессии
	$resultRow = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."tokbox");
	foreach( $resultRow as $row) {
		$aboutConferenceText = $row->aboutConferenceText;
		$apiKey = $row->apiKey;
		$apiSecret = $row->apiSecret;
	} 	
    $opentok = new OpenTok($apiKey, $apiSecret);	
	// Create a session that attempts to use peer-to-peer streaming:
	$sessionOptions = array(
    	'archiveMode' => ArchiveMode::ALWAYS,
    	'mediaMode' => MediaMode::ROUTED
	);
	$sessionVar = $opentok->createSession($sessionOptions);	
	// Generate a Token by calling the method on the Session (returned from createSession)	
	$sessionTok = strval($sessionVar);
	$token = $opentok->generateToken($sessionTok,array(
	    'role'       => Role::MODERATOR,  
	    'expireTime' => time()+(7 * 24 * 60 * 60), // in one week
	    'data'       => ''.$aboutConferenceText.',moderator'
	));	
	
	$wpdb->query( "UPDATE ".$wpdb->prefix."tokbox SET sessionTok='".$sessionTok."'");		
    //crete session with token
	$_SESSION['tokbox_admin'] = $token;		
}
?>