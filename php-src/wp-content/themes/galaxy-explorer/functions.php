<?php
/**
 * galaxy explorer functions and definitions
 *
 * @package galaxy explorer
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 640; /* pixels */
}




if ( ! function_exists( 'galaxy_explorer_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function galaxy_explorer_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on galaxy explorer, use a find and replace
	 * to change 'galaxy-explorer' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'galaxy-explorer', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	//add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'galaxy-explorer' ),
	) );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link'
	) );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'galaxy_explorer_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );
}
endif; // galaxy_explorer_setup
add_action( 'after_setup_theme', 'galaxy_explorer_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function galaxy_explorer_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'galaxy-explorer' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h1 class="widget-title">',
		'after_title'   => '</h1>',
	) );
}
add_action( 'widgets_init', 'galaxy_explorer_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function galaxy_explorer_scripts() {
	
	
	wp_enqueue_style( 'galaxy-explorer-style', get_stylesheet_uri() );
	
	//tool css
	wp_enqueue_style( 'ge-jquery-confirm', get_template_directory_uri() . '/js/plugins/confirm/jquery.confirm.css', array('galaxy-explorer-style') );
	wp_enqueue_style( 'ge-jquery-colorbox', get_template_directory_uri() . '/js/plugins/colorbox/colorbox.css', array('ge-jquery-confirm') );
	
	
	//custom css
	wp_enqueue_style( 'ge-custom-css', get_template_directory_uri() . '/css/custom.css', array('ge-jquery-colorbox') );
	
	
	
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	
	wp_deregister_script('jquery');
	wp_enqueue_script( 'fonts', 'https://fast.fonts.net/jsapi/04432635-5d2d-42e2-bcbb-43e596634d2a.js');
	wp_enqueue_script( 'jquery', get_template_directory_uri() . '/js/jquery-1.11.1.min.js');
	
	

	wp_enqueue_script( 'ge-jquery-confirm-js', get_template_directory_uri() . '/js/plugins/confirm/jquery.confirm.js', array('jquery'), '1' );
	wp_enqueue_script( 'ge-custom-buttons-js', get_template_directory_uri() . '/js/plugins/screwDefaultButtons/screwdefaultbuttonsV2.min.js', array('jquery'), '1' );
	wp_enqueue_script( 'ge-custom-select-js', get_template_directory_uri() . '/js/plugins/customSelect/customSelect.min.js', array('jquery'), '1' );
	wp_enqueue_script( 'ge-same-height-js', get_template_directory_uri() . '/js/plugins/sameHeight/jquery.same-height.js', array('jquery'), '1' );
	wp_enqueue_script( 'ge-modernizr-js', get_template_directory_uri() . '/js/modernizr.js', array('jquery'), '1' );
	wp_enqueue_script( 'ge-jquery-color-js', get_template_directory_uri() . '/js/plugins/colorbox/jquery.colorbox-min.js', array('jquery'), '1' );
	
	
	//wp_enqueue_script( 'ge-transcriber', get_template_directory_uri() . '/tool/js/transcriber.js', array('ge-jquery-color-js'), '1' );
	
	//galaxy tool file
	wp_enqueue_script( 'ge-galaxy-tool', get_template_directory_uri() . '/tool/js/tool.js', array('ge-jquery-color-js'), '1' );
	
	
	//main js file
	wp_enqueue_script( 'ge-main-js', get_template_directory_uri() . '/js/main.js', array('ge-galaxy-tool'), '1' );
	
	//webtrend file
	//wp_enqueue_script( 'ge-webtrends', get_template_directory_uri() . '/js/webtrends.load.wd.js', array('ge-transcriber'), '1' );
	
	// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
	wp_localize_script( 'ge-main-js', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
	
	
	
}
add_action( 'wp_enqueue_scripts', 'galaxy_explorer_scripts' );

add_action('admin_head', 'custom_admin_css');

function custom_admin_css() {

	wp_enqueue_style( 'explore-admin-css', get_template_directory_uri(). '/css/main-admin.css' );
	add_editor_style( get_template_directory_uri(). '/css/main-admin.css' );

}


/*----------------------------------------------------------------------------------------------
 cleanup wordpress meta and others
------------------------------------------------------------------------------------------------*/
remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
remove_action( 'wp_head', 'rsd_link' ); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action( 'wp_head', 'wlwmanifest_link' ); // Display the link to the Windows Live Writer manifest file.
remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
remove_action( 'wp_head', 'wp_generator' ); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );

add_action('do_feed', 'disable_our_feeds', 1);
add_action('do_feed_rdf', 'disable_our_feeds', 1);
add_action('do_feed_rss', 'disable_our_feeds', 1);
add_action('do_feed_rss2', 'disable_our_feeds', 1);
add_action('do_feed_atom', 'disable_our_feeds', 1);

function disable_our_feeds() {
	wp_redirect( '/not-found/' ); exit;
}

add_theme_support( 'post-thumbnails' );

//add_image_size( 'science-page', 450, 265, true );
add_image_size( 'science-subpage', 830, 490, true );
add_image_size( 'science-subpage-list', 400, 235, true );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
remove_action( 'admin_print_styles', 'print_emoji_styles' );


/**
 * Implement the Custom Header feature.
 */
//require get_template_directory() . '/inc/custom-header.php';

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

/**
 * Load custom galaxy explorer functions - FUNCTIONS
 */
require get_template_directory() . '/inc/ge-functions.php';

/**
 * Load custom galaxy explorer functions - AJAX
 */
require get_template_directory() . '/inc/ge-ajax.php';

/**
 * Load CRON jobs
 */
require get_template_directory() . '/inc/ge-cron.php';


/**
 * Load custom galaxy explorer functions for images import - DELETE on live
 */
//require get_template_directory() . '/inc/ge-imports.php';

// Filter to fix the Post Author Dropdown


