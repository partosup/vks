<?php
/**
 * tokboxMed functions and definitions.
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package tokboxMed
 */
add_filter( 'wp_mail_from', function() {
    return 'wordpress@opentok.com';
} );
if ( ! function_exists( 'tokboxmed_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function tokboxmed_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on tokboxMed, use a find and replace
	 * to change 'tokboxmed' to the name of your theme in all the template files.
	 */
	load_theme_textdomain( 'tokboxmed', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
	 */
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary', 'tokboxmed' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'tokboxmed_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif;
add_action( 'after_setup_theme', 'tokboxmed_setup' );



function my_jq_method() {
    wp_deregister_script( 'jquery' );
    wp_register_script( 'jquery', 'https://code.jquery.com/jquery-2.2.4.min.js');
    wp_enqueue_script( 'jquery' );
}    
 
add_action( 'wp_enqueue_scripts', 'my_jq_method' );



/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function tokboxmed_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'tokboxmed_content_width', 640 );
}
add_action( 'after_setup_theme', 'tokboxmed_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function tokboxmed_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'tokboxmed' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'tokboxmed' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'tokboxmed_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function tokboxmed_scripts() {
	wp_enqueue_style( 'tokboxmed-style', get_stylesheet_uri() );
		
	wp_enqueue_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css' );
	wp_enqueue_style( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css' );
	wp_enqueue_style( 'jquery-nicelabel', get_template_directory_uri() .'/css/jquery-nicelabel.css' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'tokboxmed-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );
	wp_enqueue_script( 'tokboxmed-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );
		
	wp_enqueue_script( 'bootstrap', '//netdna.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js', array(), '20151215', true );
	wp_enqueue_script( 'nicelabel', get_template_directory_uri() . '/js/jquery.nicelabel.js', array(), '', true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'tokboxmed_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/inc/jetpack.php';




remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );








/**
* После его установки в Я.ВМ будет постепенно уходить загруженный мусор. **/


 
// Отключаем фильтры REST API
remove_action( 'xmlrpc_rsd_apis', 'rest_output_rsd' );
remove_action( 'wp_head', 'rest_output_link_wp_head', 10, 0 );
remove_action( 'template_redirect', 'rest_output_link_header', 11, 0 );
remove_action( 'auth_cookie_malformed', 'rest_cookie_collect_status' );
remove_action( 'auth_cookie_expired', 'rest_cookie_collect_status' );
remove_action( 'auth_cookie_bad_username', 'rest_cookie_collect_status' );
remove_action( 'auth_cookie_bad_hash', 'rest_cookie_collect_status' );
remove_action( 'auth_cookie_valid', 'rest_cookie_collect_status' );
remove_filter( 'rest_authentication_errors', 'rest_cookie_check_errors', 100 );
 

function disable_embeds_init() {

    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');

    // Turn off oEmbed auto discovery.
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);

    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');

    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
}

add_action('init', 'disable_embeds_init', 9999);






// фильтр передает переменную $template - путь до файла шаблона.
// Изменяя этот путь мы изменяем файл шаблона.
$template = get_template_directory();
add_filter('template_include', 'conference_template');
function conference_template( $template ) {
	if( is_page('conference') ){
		
		if ( $new_template = locate_template( array( 'page-conference.php' ) ) ){
			return $new_template ;
		}
	}
	return $template;
}

//открываем сессию для token opentok
add_action('init', 'opentokSession', 1);
add_action('wp_logout', 'opentokEndSession');
function opentokSession() {
    if(!session_id()) {
        session_start();
    }
}
function opentokEndSession() {
    session_destroy();
}


	  //opentok fun
function tokboxmed_init_admin(){	
	global $wpdb;
	global $current_user;
	$resultRow = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."tokbox");
	foreach( $resultRow as $row) {
		$sessionTok = $row->sessionTok;
		$apiKey = $row->apiKey;
	} 
	//get session of tokens	
	if(isset($_SESSION['tokbox_admin'])){
		$token = $_SESSION['tokbox_admin'];
	}
	get_currentuserinfo();    
    $user_name_a  = $current_user->user_firstname;
    $user_name_b  = $current_user->user_lastname; 
	$variable_array_to_js = array(
				'apiKey' =>  $apiKey,
				'sessionTok' => $sessionTok,
				'token' => $token,
				'modeConference' => 'Moderator',
				'name' => ''.$user_name_a.' '.$user_name_b.''
	);		
	wp_enqueue_style( 'tokbox', get_template_directory_uri() .'/tokbox/css/tokbox.css' );
	wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'opentok', 'https://static.opentok.com/v2/js/opentok.min.js');
    wp_enqueue_script( 'opentokModerate', get_template_directory_uri() .'/tokbox/js/opentokModerate.js');  
    wp_localize_script( 'opentokModerate', 'opentokVars', $variable_array_to_js );
    wp_enqueue_script( 'opentokModerate' );	

}


	  //opentok fun
function tokboxmed_init(){
	global $wpdb;
	global $current_user;
	global $status_tokbox_get;
	global $status_tokbox_post;
	get_currentuserinfo(); 

	$resultRow = $wpdb->get_results( "SELECT * FROM ".$wpdb->prefix."users");		   
	foreach( $resultRow as $row) {
		if($current_user->user_email == $row->user_email){
			$status_tokbox_get = $row->status_tokbox_get;
			$status_tokbox_post = $row->status_tokbox_post;
		}
	} 
  wp_reset_query();
   if($status_tokbox_get == 1){
			require get_template_directory() . '/tokbox/tokboxConnect.php';			
			$user_name_a  = $current_user->user_firstname;
			$user_name_b  = $current_user->user_lastname;  
		
			$variable_array_to_js = array(
						'apiKey' =>  $apiKey,
						'sessionTok' => $sessionTok,
						'token' => $token,
						'modeConference' => 'Publisher',
						'name' => ''.$user_name_a.' '.$user_name_b.''
			);	
			wp_enqueue_style( 'tokbox', get_template_directory_uri() .'/tokbox/css/tokbox.css' );	
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'opentok', 'https://static.opentok.com/v2/js/opentok.min.js');
			wp_register_script( 'opentokConnect', get_template_directory_uri() .'/tokbox/js/opentokConnect.js');
			wp_localize_script( 'opentokConnect', 'opentokVars', $variable_array_to_js );
			wp_enqueue_script( 'opentokConnect' );	
   }else{
	   		wp_enqueue_script( 'jquery' );
   }
}



show_admin_bar(false);
