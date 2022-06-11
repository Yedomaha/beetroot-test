<?php
/**
 * Contact Form 7 remove auto added p tags
 */
add_filter('wpcf7_autop_or_not', '__return_false');


/*Contact form 7 remove span*/
add_filter('wpcf7_form_elements', function($content) {
    $content = preg_replace('/<(span).*?class="\s*(?:.*\s)?wpcf7-form-control-wrap(?:\s[^"]+)?\s*"[^\>]*>(.*)<\/\1>/i', '\2', $content);

    $content = str_replace('<br />', '', $content);

    return $content;
});