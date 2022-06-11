<?php
add_filter( 'wpseo_locale', '__return_false' ); // for some reason does not remove, it sets to "en_US"
add_filter( 'wpseo_opengraph_url' , '__return_false' );
add_filter( 'wpseo_opengraph_desc', '__return_false' );
add_filter( 'wpseo_opengraph_title', '__return_false' );
add_filter( 'wpseo_opengraph_type', '__return_false' );
add_filter( 'wpseo_opengraph_site_name', '__return_false' );
add_filter( 'wpseo_opengraph_image' , '__return_false' );
add_action( 'wpseo_add_opengraph_images', '__return_false' );

add_filter( 'wpseo_twitter_title' , '__return_false' );
add_filter( 'wpseo_twitter_description' , '__return_false' );
add_filter( 'wpseo_twitter_image' , '__return_false' );