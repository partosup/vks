<?php
/*
Template Name: conference
*/
get_header();
if( !is_user_logged_in()){
	wp_redirect(home_url()); 
	exit;
}else{
	 if ( !is_super_admin() ) {
	 	tokboxmed_init();
		if($status_tokbox_get == 1){
		   //include( locate_template( 'template-parts/content-conference.php', false, false ));
		   get_template_part( 'template-parts/content', 'conference' );
		}else{
		  // include( locate_template( 'template-parts/content-conference_wait.php',  false, false ));	
		  get_template_part( 'template-parts/content', 'conference_wait' );
		}

	 }elseif(is_super_admin()){
	 	tokboxmed_init_admin();
		get_template_part( 'template-parts/content', 'conference' );
	 }
}	
get_sidebar();
get_footer();