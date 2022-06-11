<?php
/* Marc Dingena Utilities
 * Test image resolution before image crunch
 */
add_filter('wp_handle_upload_prefilter','mdu_validate_image_size');
function mdu_validate_image_size( $file ) {
    $image = getimagesize($file['tmp_name']);
    $minimum = array(
        'width' => '0',
        'height' => '0'
    );
    $maximum = array(
        'width' => '4000',
        'height' => '4000'
    );
    $image_width = $image[0];
    $image_height = $image[1];

    $too_small = "Image dimensions are too small. Minimum size is {$minimum['width']} by {$minimum['height']} pixels. Uploaded image is $image_width by $image_height pixels.";
    $too_large = "Image dimensions are too large. Maximum size is {$maximum['width']} by {$maximum['height']} pixels. Uploaded image is $image_width by $image_height pixels.";

    if ( $image_width < $minimum['width'] || $image_height < $minimum['height'] ) {
        // add in the field 'error' of the $file array the message
        $file['error'] = $too_small;
        return $file;
    }
    elseif ( $image_width > $maximum['width'] || $image_height > $maximum['height'] ) {
        //add in the field 'error' of the $file array the message
        $file['error'] = $too_large;
        return $file;
    }
    else
        return $file;
}