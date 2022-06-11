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

//Hide ACF specific class
function hide_acf_class()
{
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery(".hide_acf_field").css("display", "none");
            ;
        });
    </script>
    <?php
}

add_action('acf/input/admin_footer', 'hide_acf_class');

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

/**
 * Generate locations JSON file
 */
function locations_json($post_id)
{
    $post_type = get_post_type($post_id);
    if ($post_type == 'location') {

        //Getting locations
        $posts_europe = get_posts([
            'post_type' => 'location',
            'post_status' => 'publish',
            'numberposts' => -1,
            // 'order'    => 'ASC'
            'meta_query' => array(
                'relation' => 'AND',
                'сontinents' => array(
                    'key' => 'сontinents',
                    'value' => '0',
                ),
            ),
            'orderby' => array(
                'сontinents' => 'ASC',
                'overview_location_type' => 'DESC'
            ),
        ]);
        $posts_america = get_posts([
            'post_type' => 'location',
            'post_status' => 'publish',
            'numberposts' => -1,
            // 'order'    => 'ASC'
            'meta_query' => array(
                'relation' => 'AND',
                'сontinents' => array(
                    'key' => 'сontinents',
                    'value' => '1',
                ),
            ),
            'orderby' => array(
                'сontinents' => 'ASC',
                'overview_location_type' => 'DESC'
            ),
        ]);
        $posts_asia = get_posts([
            'post_type' => 'location',
            'post_status' => 'publish',
            'numberposts' => -1,
            'meta_query' => array(
                'relation' => 'AND',
                'сontinents' => array(
                    'key' => 'сontinents',
                    'value' => '2',
                ),
            ),
            'orderby' => array(
                'сontinents' => 'ASC',
                'overview_location_type' => 'DESC'
            ),
        ]);

        //Saving results into array
        $result = [];

        if (!empty($posts_europe)) {
            $europe = [
                'name' => 'Europe',
            ];
            array_push($result, $europe);
        }
        if (!empty($posts_america)) {
            $america = [
                'name' => 'America',
            ];
            array_push($result, $america);
        }
        if (!empty($posts_asia)) {
            $asia = [
                'name' => 'Asia',
            ];
            array_push($result, $asia);
        }

        //Filtering posts
        function save_filter_location($posts, $array)
        {

            $result = $array;

            foreach ($posts as $key => $post) {
                $id = $post->ID;
                $continentField = get_field('сontinents', $id);
                //Write to continents data array
                $result[$continentField['value']]['data'][$key]['id'] = $post->ID;

                //Saving locations
                if ($overview = get_field("overview", $id)) {

                    $location_type = ($overview['location_type']) ? $overview['location_type'] : '';
                    $location_image = ($overview['location_image']) ? $overview['location_image'] : '';
                    $location_retailer_name = ($overview['location_retailer_name']) ? $overview['location_retailer_name'] : '';
                    $location_opening_store_time = ($overview['location_opening_store_time']) ? $overview['location_opening_store_time'] : '';
                    $location_email = ($overview['location_email']) ? $overview['location_email'] : '';
                    $location_contact = ($overview['location_сontact']) ? $overview['location_сontact'] : '';
                    $location_fax = ($overview['location_fax']) ? $overview['location_fax'] : '';
                    $location_phone = ($overview['location_phone']) ? $overview['location_phone'] : '';
                    $location_website = ($overview['location_website']) ? $overview['location_website'] : '';
                    $location_address = ($overview['location_address']) ? $overview['location_address'] : '';
                    $address = ($location_address['address']) ? $location_address['address'] : '';
                    $lat = ($location_address['lat']) ? $location_address['lat'] : '';
                    $lng = ($location_address['lng']) ? $location_address['lng'] : '';

                    $location_address1 = ($overview['location_address1']) ? $overview['location_address1'] : '';
                    $location_address2 = ($overview['location_address2']) ? $overview['location_address2'] : '';
                    $location_town = ($overview['location_town']) ? $overview['location_town'] : '';
                    $location_post_code = ($overview['location_post_code']) ? $overview['location_post_code'] : '';
                    $location_country = ($overview['location_country']) ? $overview['location_country'] : '';

                    $result[$continentField['value']]['data'][$key]['imageSrc'] = $location_image;
                    $result[$continentField['value']]['data'][$key]['type'] = $location_type;
                    $result[$continentField['value']]['data'][$key]['company'] = $location_retailer_name;
                    $result[$continentField['value']]['data'][$key]['time'] = $location_opening_store_time;
                    $result[$continentField['value']]['data'][$key]['email'] = $location_email;
                    $result[$continentField['value']]['data'][$key]['contact'] = $location_contact;
                    $result[$continentField['value']]['data'][$key]['fax'] = $location_fax;
                    $result[$continentField['value']]['data'][$key]['phone'] = $location_phone;
                    $result[$continentField['value']]['data'][$key]['website'] = $location_website;
                    $result[$continentField['value']]['data'][$key]['address'] = $address;
                    $result[$continentField['value']]['data'][$key]['lat'] = $lat;
                    $result[$continentField['value']]['data'][$key]['long'] = $lng;

                    $result[$continentField['value']]['data'][$key]['location_address1'] = $location_address1;
                    $result[$continentField['value']]['data'][$key]['location_address2'] = $location_address2;
                    $result[$continentField['value']]['data'][$key]['city'] = $location_town;
                    $result[$continentField['value']]['data'][$key]['location_post_code'] = $location_post_code;
                    $result[$continentField['value']]['data'][$key]['country'] = $location_country;

                    if (!empty($location_address1)) {
                        $location_address1 = $location_address1 . '<br>';
                    }
                    if (!empty($location_address2)) {
                        $location_address2 = $location_address2 . '<br>';
                    }
                    if (!empty($location_town)) {
                        $location_town = $location_town . ', ';
                    }
                    if (!empty($location_post_code)) {
                        $location_post_code = $location_post_code . '<br>';
                    }
                    if (!empty($location_country)) {
                        $location_country = $location_country;
                    }

                    $result[$continentField['value']]['data'][$key]['address_full'] = $location_address1 . $location_address2 . $location_town . $location_post_code . $location_country;
                }

            }

            return $result;
        }

        $post_array_europe = save_filter_location($posts_europe, $result);
        $post_array_america = save_filter_location($posts_america, $post_array_europe);
        $post_array_asia = save_filter_location($posts_asia, $post_array_america);
        $result = $post_array_asia;

        //Saving actual file
        $file = wp_get_upload_dir()['basedir'] . '/locations.json';

        $fp = fopen($file, 'w');
        fwrite($fp, json_encode($result));
        fclose($fp);

        return;
    }
}

//add_action('acf/save_post', 'locations_json', 10, 3);

function get_oEmbed_url($field, $sub = false){

	$oEmbed_object = ($sub) ? get_sub_field_object(strval($field), false) : get_field_object(strval($field), false);

	if(!empty($oEmbed_object) && is_array($oEmbed_object)){
		return $oEmbed_object['value'];
	}

	return false;

}