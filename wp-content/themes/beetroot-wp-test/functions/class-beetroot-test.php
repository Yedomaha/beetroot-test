<?php

class beetrootTestClass {

	public static function get_terms_string( $terms, $separator ) {
		if ( ! isset( $terms ) || empty( $terms ) || ! is_array( $terms ) ) {
			return;
		}

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

	public static function get_img_id_from_arr( $img_arr ) {
		$image_placeholder_arr = get_field( 'image_placeholder', 'options' );
		if ( is_array( $img_arr ) && $img_arr['id'] ) {
			return $img_arr['id'];
		} elseif ( is_array( $image_placeholder_arr ) && $image_placeholder_arr['id'] ) {
			return $image_placeholder_arr['id'];
		} else {
			return false;
		}
	}

	public static function load_template_part( $template_name, $part_name = null ) {
		ob_start();
		get_template_part( $template_name, $part_name );
		$var = ob_get_contents();
		ob_end_clean();

		return $var;
	}

	public static function get_image_attributes_by_id( $image_id, $width, $height = 0, $crop = false, $aq_single = true, $aq_upscale = false ) {
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

}