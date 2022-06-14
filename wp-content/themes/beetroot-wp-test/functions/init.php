<?php
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
if ( ! function_exists( 'my_setup' ) ) :
	function my_setup() {
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

		## disable create thumbnail for this sizes
		add_filter( 'intermediate_image_sizes', 'delete_intermediate_image_sizes' );
		function delete_intermediate_image_sizes( $sizes ) {
			// remove this sizes
			return array_diff( $sizes, array(
				'medium_large',
				'large',
				'1536x1536',
				'2048x2048',
			) );
		}

		// This theme uses wp_nav_menu() in one location.
//		register_nav_menus( array(
//			'header-menu' => 'Primary',
//		) );

		//Register Sidebar
//        if (function_exists('register_sidebar')) {
//            register_sidebar(array(
//                    'id' => 'sidebar',
//                    'name' => 'Sidebar',
//                )
//            );
//        }

	}
endif;
add_action( 'after_setup_theme', 'my_setup' );


// remove_json_api
function remove_json_api() {

	// Remove the REST API lines from the HTML Header
	remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );

	// Remove the REST API endpoint.
	remove_action( 'rest_api_init', 'wp_oembed_register_route' );

	// Turn off oEmbed auto discovery.
	add_filter( 'embed_oembed_discover', '__return_false' );

	// Don't filter oEmbed results.
	remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );

	// Remove oEmbed discovery links.
	remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );

	// Remove oEmbed-specific JavaScript from the front-end and back-end.
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );

	// Remove all embeds rewrite rules.
	// add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );

}

add_action( 'after_setup_theme', 'remove_json_api' );
// end remove_json_api

// disable_json_api
function disable_json_api() {

	// Filters for WP-API version 1.x
	add_filter( 'json_enabled', '__return_false' );
	add_filter( 'json_jsonp_enabled', '__return_false' );

	// Filters for WP-API version 2.x
	add_filter( 'rest_enabled', '__return_false' );
	add_filter( 'rest_jsonp_enabled', '__return_false' );

}

add_action( 'after_setup_theme', 'disable_json_api' );
// end disable_json_api

function translations() {
	// Load the text domain and translations
	$lang_dir = get_stylesheet_directory() . '/languages';
	load_theme_textdomain( 'beetroot-wp-test', $lang_dir );
}

add_action( 'after_setup_theme', 'translations' );


/**
 * Css/Js includes
 */
function enqueue_css() {
//	$css_frontend_ver = filemtime( get_template_directory() . '/dist/css/frontend.css' );
//	$css_backend_ver = filemtime( get_template_directory() . '/dist/css/backend.css' );
	$app_css_ver = filemtime( get_template_directory() . '/dist/css/app.css' );

	wp_enqueue_style( 'css_style', get_stylesheet_uri(), array(), '' );
//	wp_enqueue_style( 'frontend', get_template_directory_uri() . '/dist/css/frontend.css', '', $css_frontend_ver );
//	wp_enqueue_style( 'backend', get_template_directory_uri() . '/dist/css/backend.css', '', $css_backend_ver );
	wp_enqueue_style( 'app_css', get_template_directory_uri() . '/dist/css/app.css', '', $app_css_ver );
}

add_action( 'wp_enqueue_scripts', 'enqueue_css' );


function load_custom_wp_admin_style() {
	$admin_css_ver = filemtime( get_template_directory() . '/admin/admin-style.css' );
	wp_register_style( 'custom_wp_admin_css', get_bloginfo( 'stylesheet_directory' ) . '/admin/admin-style.css', $admin_css_ver, '1.0.0' );
	wp_enqueue_style( 'custom_wp_admin_css' );
}

add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );

/**
 * app.ajax_url
 * app.post_type
 */
function enqueue_app_js() {
//	$js_backend_ver = filemtime( get_template_directory() . '/dist/js/backend.js' );
//	$js_frontend_ver = filemtime( get_template_directory() . '/dist/js/frontend.js' );
	$app_js_ver = filemtime( get_template_directory() . '/dist/js/app.js' );

//	wp_enqueue_script( 'backend', get_template_directory_uri() . '/dist/js/backend.js', '', $js_backend_ver, true );
//	wp_localize_script( 'backend', 'app', array(
//		'ajax_url'  => admin_url( 'admin-ajax.php' ),
//		'post_type' => get_post_type(),
//		'nonce'     => wp_create_nonce( 'app-nonce' ),
//		'user_id'   => get_current_user_id()
//	) );
//
//	wp_enqueue_script( 'frontend', get_template_directory_uri() . '/dist/js/frontend.js', '', $js_frontend_ver, true );
//	wp_localize_script( 'frontend', 'app', array(
//		'ajax_url'  => admin_url( 'admin-ajax.php' ),
//		'post_type' => get_post_type(),
//		'nonce'     => wp_create_nonce( 'app-nonce' ),
//		'user_id'   => get_current_user_id()
//	) );

	wp_enqueue_script( 'app_js', get_template_directory_uri() . '/dist/js/app.js', '', $app_js_ver, true );
	wp_localize_script( 'app_js', 'app', array(
		'ajax_url'  => admin_url( 'admin-ajax.php' ),
		'post_type' => get_post_type(),
		'nonce'     => wp_create_nonce( 'app-nonce' ),
		'user_id'   => get_current_user_id()
	) );
}

add_action( 'wp_enqueue_scripts', 'enqueue_app_js' );