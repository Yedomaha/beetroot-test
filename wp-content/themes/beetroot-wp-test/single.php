<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package beetroot-wp-test
 */

get_header();
?>
<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <?php echo get_the_title(); ?>
    <?php endwhile; ?>
<?php endif; ?>
<?php get_footer(); ?>








