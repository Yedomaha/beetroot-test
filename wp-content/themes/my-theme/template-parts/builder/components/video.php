<?php
/**
 * Block Name: Video
 */
$video = get_field('video');
$video_url = $video['hero']['video']['url'];
if (empty($video_url)){
    echo 'Upload video: format mp 4';
}
?>
<?php get_template_part('template-parts/components/video'); ?>