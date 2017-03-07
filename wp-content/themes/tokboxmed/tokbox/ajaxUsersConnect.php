<?php
	global $wpdb;
	include_once($_SERVER['DOCUMENT_ROOT'].'/wp-load.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-config.php' );
	require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-includes/wp-db.php' );



/*db 
status_tokbox_post = 0 -> no conference crieted;
status_tokbox_post = 1 -> запрос на подключение к конференции;
status_tokbox_post = 2 -> запрос был отклонен админитсратором;
status_tokbox_get = 0 -> нет доступа;
status_tokbox_get = 1 -> доступ открыт;
*/
//get list of users who made pending for connect



if($_POST["getUsers"]){
    $resultRow = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."users");		   
	foreach( $resultRow as $row) {
		if($row->status_tokbox_post == 1){
			$user_info = get_userdata($row->ID);
			$tokbox_name_user[] = $row->ID.'='.$user_info->first_name.' '.$user_info->last_name;
		}
	} 
	echo json_encode($tokbox_name_user);
}

//solve connection
if($_POST["solve"]){
	$prefix = $wpdb->prefix.'users';
				$wpdb->update(''.$prefix.'',
				 				array(status_tokbox_get=>'1'),
								array(ID=>''.$_POST['id'].'')
							);
				$wpdb->update(''.$prefix.'',
				 				array(status_tokbox_post=>'0'),
								array(ID=>''.$_POST['id'].'')
							);
}
//reject connection
if($_POST["reject"]){
	$prefix = $wpdb->prefix.'users';
				$wpdb->update(''.$prefix.'',
				 				array(status_tokbox_post=>'2'),
								array(ID=>''.$_POST['id'].'')
							);
				$wpdb->update(''.$prefix.'',
				 				array(status_tokbox_get=>'0'),
								array(ID=>''.$_POST['id'].'')
							);
}

//reset connection from user post
if($_POST["resetSend"]){
	$prefix = $wpdb->prefix.'users';
				$wpdb->update(''.$prefix.'',
				 				array(status_tokbox_post=>'1'),
								array(ID=>''.$_POST['id'].'')
							);
}


?>