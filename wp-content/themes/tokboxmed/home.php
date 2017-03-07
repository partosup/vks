<?php
get_header();?>
<div class="row">	
	<div id="primary" class="content-area col-md-12">
		<main id="main" class="site-main" role="main">
		  <div class="col-sm-6 col-xs-12">
		  	<?php 
			    echo do_shortcode("[wpcrl_login_form]");
			?>
		  </div>
		   <div class="col-sm-6 col-xs-12">
		  	<?php if( !is_user_logged_in()) {
			    echo do_shortcode("[wpcrl_register_form]");
			}?>
		  </div>   		
		</main><!-- #main -->
	</div><!-- #primary -->
</div>
<?php
get_sidebar();
get_footer();