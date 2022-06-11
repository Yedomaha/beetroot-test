<?php
/**
 * Block Name: Image full width
 */

$image_full_width = get_field('image_full_width');
?>
<?php if (!empty($image_full_width)) : ?>
    <img class="full-width__image fade-in__container fade-in__animated-start"
         src="<?php echo $image_full_width['url']; ?>" alt="<?php echo $image_full_width['alt']; ?>">
<?php endif; ?>
