<?php
/*
Template Name: conference
*/
if( !is_user_logged_in()){
	wp_redirect(home_url()); 
	exit;
}else{
	 if ( !is_super_admin() ) {	 	
	 	get_header();
	 	tokboxmed_init();
		if($status_tokbox_get == 1){
		   include( locate_template( 'template-parts/content-conference.php', false, false ));
		}else{	
		  include( locate_template( 'template-parts/content-conference_wait.php',  false, false ));    
		}
	 }elseif(is_super_admin()){
	 	get_header();
	 	tokboxmed_init_admin();
		get_template_part( 'template-parts/content', 'conference' );
	 }
}	
get_sidebar();
get_footer();
