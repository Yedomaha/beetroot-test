<?php
/**
 * ACF PRO Options page
 */
if (function_exists('acf_add_options_page')) {

    acf_add_options_page(array(
        'page_title' => 'Theme General Settings',
        'menu_title' => 'Theme Settings',
        'menu_slug' => 'theme-general-settings',
        'capability' => 'edit_posts',
        'redirect' => false
    ));
};

// Setting Google API Key for Admin
function my_acf_google_map_api( $api ){
    $api['key'] = get_field('google_maps_api_key', 'option');
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api');

/**
 * Remove p tags from images, scripts, links, and iframes. (ACF)
 */
function remove_some_ptags_acf( $content ) {
    $content = preg_replace('/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content);
    $content = preg_replace('/<p>\s*(<script.*>*.<\/script>)\s*<\/p>/iU', '\1', $content);
    $content = preg_replace('/<p>\s*(<iframe.*>*.<\/iframe>)\s*<\/p>/iU', '\1', $content);
    $content = preg_replace('/<p>\s*(<a.*>*.<\/a>)\s*<\/p>/iU', '\1', $content);
    return $content;
}
add_filter( 'acf_the_content', 'remove_some_ptags_acf', 30 );

function get_oEmbed_url($field, $sub = false){

	$oEmbed_object = ($sub) ? get_sub_field_object(strval($field), false) : get_field_object(strval($field), false);

	if(!empty($oEmbed_object) && is_array($oEmbed_object)){
		return $oEmbed_object['value'];
	}

	return false;

}