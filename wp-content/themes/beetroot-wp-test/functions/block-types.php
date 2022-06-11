<?php
add_filter('allowed_block_types', 'custom_allowed_block_types', 10, 2);
function custom_allowed_block_types($allowed_blocks, $post)
{
    $allowed_blocks = array(
        'acf/text',
        'acf/video',
        'acf/image-description-layout',
        'acf/image-full-width',
        'acf/images-layout',
        'acf/project-carousel',
        'acf/project-extra',

        //'core/image',
        //'core/paragraph',
        //'core/heading',
        //'core/list'
    );
    return $allowed_blocks;
}