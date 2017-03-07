<?php

	global $wpdb;
	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );
	require get_template_directory() . '/tokbox/opentok.phar';
	require WP_PLUGIN_DIR . '/composer/vendor/autoload.php';
	
	
	use OpenTok\OpenTok;
	use OpenTok\MediaMode;
	use OpenTok\Session;
	use OpenTok\Role;
	use OpenTok\Archive;
	use OpenTok\OutputMode;
	
	
if(is_super_admin()){

  //получаем данные о сессии
$resultRow = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."tokbox");
foreach( $resultRow as $row) {
	$sessionTok = $row->sessionTok;
	$aboutConferenceText = $row->aboutConferenceText;
	$apiKey = $row->apiKey;
	$apiSecret = $row->apiSecret;
	$archiveId = $row->archiveId;
}


	// start video conference
	if($_POST["start"]){
		
		//описание коференции дата
		$wpdb->query( "UPDATE ".$wpdb->prefix."tokbox SET aboutConferenceText='".date('l jS \of F Y h:i:s A')."'");
		 	
	    $opentok = new OpenTok($apiKey, $apiSecret);	
		// Create a session that attempts to use peer-to-peer streaming:
		$sessionOptions = array(
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
		$sessionId = $sessionVar->getSessionId();
	
		
	   }
	   
	   
	   // delete video conference
	   if($_POST["stop"]){
	   		$wpdb->query( "UPDATE ".$wpdb->prefix."tokbox SET sessionTok=''");
			$wpdb->query( "UPDATE ".$wpdb->prefix."users SET status_tokbox_get='0',status_tokbox_post='0'");
			session_destroy();
	   	}
	   	
	   	
	   	// record video conference
		 if($_POST["archive_on"]){
		    $opentok = new OpenTok($apiKey, $apiSecret);
				$archiveOptions = array(
				    'name' => $aboutConferenceText,     // default: null
				    'hasAudio' => true,                     // default: true
				    'hasVideo' => false,                     // default: true
				    'outputMode' => OutputMode::INDIVIDUAL  // default: OutputMode::COMPOSED
				);
			$archive = $opentok->startArchive($sessionTok, $archiveOptions);
			$wpdb->query( "UPDATE ".$wpdb->prefix."tokbox SET archiveId='".$archive->id."'");
		}	
		
		
		if($_POST["archive_off"]){
		    $opentok = new OpenTok($apiKey, $apiSecret);
	   		$opentok->stopArchive($archiveId);
		}
		
		
	   
}  

?>