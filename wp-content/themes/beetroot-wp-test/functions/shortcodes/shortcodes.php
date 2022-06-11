<?php
/**
 * Create custom button for admin editor
 */
function true_add_mce_button()
{
    if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
        return;
    }
    if ('true' == get_user_option('rich_editing')) {
        add_filter('mce_external_plugins', 'true_add_tinymce_script');
        add_filter('mce_buttons', 'true_register_mce_button');
    }
}

add_action('acf/init', 'true_add_mce_button');

/**
 * Admin js
 */
function true_add_tinymce_script($plugin_array)
{
    $plugin_array['true_mce_button'] = get_stylesheet_directory_uri() . '/functions/shortcodes/admin/js/admin.js';
    return $plugin_array;
}

function true_add_tinymce_style()
{
    wp_enqueue_style('true_mce_button', get_stylesheet_directory_uri() . '/functions/shortcodes/admin/css/admin.css');
}

add_action('admin_enqueue_scripts', 'true_add_tinymce_style');

/**
 * Register button to editor
 */
function true_register_mce_button($buttons)
{
    array_push($buttons, 'true_mce_button');
    return $buttons;
}


/**
 * Shortcodes
 */
function line_break_shortcode() {
    return '<br />';
}
add_shortcode( 'br', 'line_break_shortcode' );


function buttons_shortcode( $atts ) {
    $params = shortcode_atts( array(
        'title' => '',
        'link' => '',
        'style' => '',
    ), $atts );

    $title = $params['title'];
    $link = $params['link'];
    $style = $params['style'];

    ob_start();
    ?>
    <?php
    switch ($style) {
        case 1:
            $style = 'solid';
            break;
    }
    ?>

    <a class="button <?php echo $style;?>" href=<?php echo $link;?>><?php echo $title;?></a>

    <?php
    return ob_get_clean();
}
add_shortcode( 'buttons', 'buttons_shortcode' );



function read_more_shortcode( $atts, $content = null ) {
    extract(shortcode_atts(array(), $atts));
    $output = '<div class="read_more_content" style="display: none;">' . apply_filters('the_content', $content) . '</div><span data-read-more="' . __('Read More', 'minthis') . '" data-close-more="' . __('Close', 'minthis') . '" class="show-more button">' . __('Read More', 'minthis') .'</span>';
    return $output;
}
add_shortcode( 'read_more', 'read_more_shortcode');