<?php

/**
 * Custom modify date format
 */
//add_filter('the_time', 'modify_date_format');
//function modify_date_format(){
//    $month_names = array(1=>'Janvier','Fèvrier','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre');
//    return  get_the_time('j').' '.$month_names[get_the_time('n')].' '.get_the_time('Y');
//}


// TODO: change umg url string to variable
function get_image( $filename ) {
	echo get_template_directory_uri() . '/dist/img/' . $filename;
}

function inline_svg( $file, $is_fromlibrary = null ) {
	if ( $is_fromlibrary ) {
		echo file_get_contents( $file );
	} else {
		echo file_get_contents( get_template_directory() . '/dist/img/' . $file );
	}
}


/**
 * Get current url
 */
function get_current_url() {
	if ( isset( $_SERVER['HTTPS'] ) &&
	     ( $_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1 ) ||
	     isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) &&
	     $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' ) {
		$protocol = 'https://';
	} else {
		$protocol = 'http://';
	}

	return $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

//echo get_current_url();


/**
 * Get page ID
 */
function get_page_id() {
	return get_the_ID();
}

/**
 * Gets information about all registered image sizes.
 */
function get_image_sizes( $unset_disabled = true ) {
	$wais  = &$GLOBALS['_wp_additional_image_sizes'];
	$sizes = array();
	foreach ( get_intermediate_image_sizes() as $_size ) {
		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
			$sizes[ $_size ] = array(
				'width'  => get_option( "{$_size}_size_w" ),
				'height' => get_option( "{$_size}_size_h" ),
				'crop'   => (bool) get_option( "{$_size}_crop" ),
			);
		} elseif ( isset( $wais[ $_size ] ) ) {
			$sizes[ $_size ] = array(
				'width'  => $wais[ $_size ]['width'],
				'height' => $wais[ $_size ]['height'],
				'crop'   => $wais[ $_size ]['crop'],
			);
		}

		// size registered, but has 0 width and height
		if ( $unset_disabled && ( $sizes[ $_size ]['width'] == 0 ) && ( $sizes[ $_size ]['height'] == 0 ) ) {
			unset( $sizes[ $_size ] );
		}
	}

	return $sizes;
}

;

/**
 * Custom function for showing var_dump() result decorated on browser
 */
function pvd( $var ) {
	echo "<pre style='font-size:16px;'>";
	var_dump( $var );
	echo "</pre>";
}

function is_blog() {
	return ( is_archive() || is_author() || is_category() || is_home() || is_single() || is_tag() ) && 'post' == get_post_type();
}

/**
 * Get youtube video ID from url
 */
function getYoutubeId( $ytURL ) {
	$urlData = parse_url( $ytURL );
	//echo '<br>'.$urlData["host"].'<br>';
	if ( isset( $urlData["host"] ) && $urlData["host"] == 'www.youtube.com' ) // Check for valid youtube url
	{
		$ytvIDlen = 11; // This is the length of YouTube's video IDs

		// The ID string starts after "v=", which is usually right after
		// "youtube.com/watch?" in the URL
		$idStarts = strpos( $ytURL, "?v=" );

		// In case the "v=" is NOT right after the "?" (not likely, but I like to keep my
		// bases covered), it will be after an "&":
		if ( $idStarts === false ) {
			$idStarts = strpos( $ytURL, "&v=" );
		}
		// If still FALSE, URL doesn't have a vid ID
		if ( $idStarts === false ) {
			die( "YouTube video ID not found. Please double-check your URL." );
		}

		// Offset the start location to match the beginning of the ID string
		$idStarts += 3;

		// Get the ID string and return it
		$ytvID = substr( $ytURL, $idStarts, $ytvIDlen );

		return $ytvID;
	} else {
		//echo 'This is not a valid youtube video url. Please, give a valid url...';
		return 0;
	}

}

/**
 * Todo new youtube / vimeo functions
 */


/**
 * Youtube regex
 */
//Regex =   (?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+

//Examples
//http://www.youtube.com/watch?v=l1sETPnsiDo&feature=g-all-f&context=G27f364eFAAAAAAAAAAA
//http://www.youtube.com/watch?feature=g-all-f&v=l1sETPnsiDo&context=G27f364eFAAAAAAAAAAA
//<iframe width="560" height="315" src="http://www.youtube.com/embed/l1sETPnsiDo" frameborder="0" allowfullscreen></iframe>
//youtube.com/v/l1sETPnsiDo
//youtube.com/vi/l1sETPnsiDo
//youtube.com/?v=l1sETPnsiDo
//youtube.com/?vi=l1sETPnsiDo
//youtube.com/watch?v=l1sETPnsiDo
//youtube.com/watch?vi=l1sETPnsiDo
//youtu.be/l1sETPnsiDo

//Regex online = https://rubular.com/r/M9PJYcQxRW


/**
 * Get vimeo video ID from url
 */
function getVimeoId( $url = '' ) {

	$regs = array();

	$id = '';

	if ( preg_match( '%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $url, $regs ) ) {
		$id = $regs[3];
	}

	return $id;

}

/**
 * @param $link
 *
 * @return string|void
 */
function getLinkAttributes( $link ) {
	if ( empty( $link ) && ! is_array( $link ) ) {
		return;
	}

	if ( str_replace( $_SERVER['HTTP_HOST'], '', $link['url'] ) ) {
		return 'href="' . esc_url( $link['url'] ) . '" target="' . esc_attr( $link['target'] ) . '" title="' . esc_attr( $link['title'] ) . '"';
	} else {
		return 'href="' . esc_url( $link['url'] ) . '" rel="nofollow" target="' . esc_attr( $link['target'] ) . '" title="' . esc_attr( $link['title'] ) . '"';
	}

}

/*Example - <a <?php echo getLinkAttributes($link); ?> class=""></a> */


/**
 * @param $image_id
 * @param $width
 * @param int $height
 * @param false $crop
 * @param bool $aq_single
 * @param false $aq_upscale
 *
 * @return string
 */
function getImageAttributesById( $image_id, $width, $height = 0, $crop = false, $aq_single = true, $aq_upscale = false ) {
	if ( intval( $height ) == 0 ) {
		$height = intval( $width );
	}

	$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$image_alt = ( ! empty( $image_alt ) ) ? $image_alt : get_the_title( $image_id );

	$image_title = wp_get_attachment_metadata( $image_id );
	$image_title = ( ! empty( $image_title['image_meta']['title'] ) ) ? $image_title['image_meta']['title'] : $image_alt;

	$image_url = wp_get_attachment_image_url( $image_id, 'full' );

	return 'title="' . esc_attr( $image_title ) . '" alt="' . esc_attr( $image_alt ) . '" src="' . aq_resize( $image_url, $width, $height, $crop, $aq_single, $aq_upscale ) . '"';
}

/*Example - <img <?php getImageAttributesById($image_id, 600); ?>> */


/**
 * @param string $item
 */
function get_clean_phone( $item ) {
	if ( empty( $item ) ) {
		return false;
	}

	return preg_replace( "/[^0-9]/", "", $item );
}

function get_current_year() {

	echo date( 'Y' );

}

function get_terms_string( $terms, $separator ) {
	if(!isset($terms) || empty($terms) || !is_array($terms))
		return;

	$terms        = array_values( $terms );
	$terms_string = '';
	for ( $i = 0; $i < count( $terms ); $i ++ ) {
		// Each array item is an object. Display its 'name' value.
		$terms_string .= $terms[ $i ]->name;
		// If there is more than one term, comma separate them.
		if ( $i < count( $terms ) - 1 ) {
			$terms_string .= strval( $separator );
		}
	}

	return $terms_string;
}

/**
 * Check image function
 */
function checkImg( $img_arr ) {
	$image_placeholder_arr = get_field( 'image_placeholder', 'options' );
	if ( is_array( $img_arr ) && $img_arr['id'] ) {
		return $img_arr['id'];
	} elseif ( is_array( $image_placeholder_arr ) && $image_placeholder_arr['id'] ) {
		return $image_placeholder_arr['id'];
	} else {
		return false;
	}
}

/**
 * Load template part function
 */
function load_template_part($template_name, $part_name = null)
{
	ob_start();
	get_template_part($template_name, $part_name);
	$var = ob_get_contents();
	ob_end_clean();
	return $var;
}