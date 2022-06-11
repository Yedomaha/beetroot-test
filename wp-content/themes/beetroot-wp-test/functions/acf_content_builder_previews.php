<?php
/**
 * Styles for ACF content builder previews.
 */
if (class_exists('ACF')) {
    add_action('admin_head', 'my_custom_styles');
    function my_custom_styles()
    {
        echo '<style>
            body .acf-fc-popup ul li a .acf-fc-popup-image{
            background-repeat: no-repeat!important;
            background-size: contain!important;
            }
        </style>';
    }

    /**
     * Path to ACF content builder previews.
     */
    add_filter('acf-flexible-content-preview.images_path', 'change_previews_path', 10, 2);
    function change_previews_path()
    {
        $path = 'lib/acf-flexible-content-preview';
        return $path;
    }
}