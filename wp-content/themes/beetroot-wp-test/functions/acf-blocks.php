<?php
//Registered acf blocks
//Icons - https://www.kevinleary.net/wordpress-list-custom-post-type-icons/
function register_acf_blocks_types(){

    //Text
    acf_register_block_type(array(
        'name' => 'text',
        'title' => __('Text'),
        'render_template' => "/template-parts/builder/components/text.php",
        'category' => 'common',
        'icon' => 'editor-paste-word',
        'post_type' => array('page', 'post', 'projects'),
        'keywords' => array('text'),
    ));
    //Video
    acf_register_block_type(array(
        'name' => 'video',
        'title' => __('Video'),
        'description' => __('Upload video: support mp4'),
        'render_template' => "/template-parts/builder/components/video.php",
        'category' => 'common',
        'icon' => 'format-video',
        'post_type' => array('page', 'post', 'projects'),
        'keywords' => array('video'),
    ));
    //Image description layout
    acf_register_block_type(array(
        'name' => 'image-description-layout',
        'title' => __('Image description layout'),
        //'description' => __('Image'),
        'render_template' => "/template-parts/builder/components/image-description-layout.php",
        'category' => 'common',
        'icon' => 'format-image',
        'post_type' => array('page', 'post', 'projects'),
        'keywords' => array('image'),
    ));
    //Image full width
    acf_register_block_type(array(
        'name' => 'image-full-width',
        'title' => __('Image full width'),
        //'description' => __('Image'),
        'render_template' => "/template-parts/builder/components/image-full-width.php",
        'category' => 'common',
        'icon' => 'format-image',
        'post_type' => array('page', 'post', 'projects'),
        'keywords' => array('image'),
    ));
    //Images layout
    acf_register_block_type(array(
        'name' => 'images-layout',
        'title' => __('Images layout'),
        //'description' => __('Image'),
        'render_template' => "/template-parts/builder/components/images-layout.php",
        'category' => 'common',
        'icon' => 'images-alt',
        'post_type' => array('page', 'post', 'projects'),
        'keywords' => array('image'),
    ));
    //Project carousel
    acf_register_block_type(array(
        'name' => 'project-carousel',
        'title' => __('Project carousel'),
        //'description' => __('Image'),
        'render_template' => "/template-parts/builder/components/project-carousel.php",
        'category' => 'common',
        'icon' => 'slides',
        'post_type' => array('page', 'post', 'projects'),
        'keywords' => array('project', 'projects', 'carousel', 'slider'),
    ));
    //Project extra
    acf_register_block_type(array(
        'name' => 'project-extra',
        'title' => __('Project extra'),
        //'description' => __('Image'),
        'render_template' => "/template-parts/builder/components/project-extra.php",
        'category' => 'common',
        'icon' => 'text',
        'post_type' => array('page', 'post', 'projects'),
        'keywords' => array('project', 'projects', 'extra'),
    ));
}
if (function_exists('acf_register_block_type')) {
    add_action('after_setup_theme', 'register_acf_blocks_types');
}