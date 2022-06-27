<?php
/**
 * Remove p tags from images, scripts, links, and iframes.
 */
function remove_some_ptags( $content ) {
	$content = preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
	$content = preg_replace( '/<p>\s*(<script.*>*.<\/script>)\s*<\/p>/iU', '\1', $content );
	$content = preg_replace( '/<p>\s*(<iframe.*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content );
	$content = preg_replace( '/<p>\s*(<a.*>*.<\/a>)\s*<\/p>/iU', '\1', $content );

	return $content;
}

add_filter( 'the_content', 'remove_some_ptags' );

/**
 * Thumbnail column in the admin entry list
 */
add_filter( 'manage_posts_columns', 'posts_columns', 5 );
add_action( 'manage_posts_custom_column', 'posts_custom_columns', 5, 2 );
function posts_columns( $defaults ) {
	$defaults['riv_post_thumbs'] = __( 'Thumbnail' );

	return $defaults;
}

function posts_custom_columns( $column_name, $id ) {
	if ( $column_name === 'riv_post_thumbs' ) {
		the_post_thumbnail( array( 150, 150 ) );
	}
}

/**
 * Remove margin bottom in html if admin login
 */
function remove_admin_login_header() {
	remove_action( 'wp_head', '_admin_bar_bump_cb' );
}

//add_action('get_header', 'remove_admin_login_header');

/**
 * Customize admin bar css
 */
function override_admin_bar_css() {
	if ( is_admin_bar_showing() ) { ?>
        <style>
            #wpadminbar {
                z-index: 1000000;
                position: fixed!important;
            }

            header {
                top: 32px !important;
            }
            @media screen and (max-width: 1023px) {
                .header__nav-wrap{
                    top: 32px !important;
                }
            }
            @media screen and (max-width: 782px){
                header, .header__nav-wrap{
                    top: 46px !important;
                }
            }
        </style>
	<?php }
}


// on backend area
add_action( 'admin_head', 'override_admin_bar_css' );
// on frontend area
add_action( 'wp_head', 'override_admin_bar_css' );


/**
 * Disable big image size threshold
 */
add_filter( 'big_image_size_threshold', '__return_false' );


/**
 * @param $mimes
 *
 * @return mixed
 * Support svg upload
 * NOTE: need write in wp-config.php - define('ALLOW_UNFILTERED_UPLOADS', true);
 */
function cc_mime_types( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}

add_filter( 'upload_mimes', 'cc_mime_types' );


/**
 * Disable WordPress Admin Bar for all users but admins.
 */
//show_admin_bar(false);