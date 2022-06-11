<?php
/**
 * Block Name: Image description layout
 */

$image_right = get_field('image_right');
$image = get_field('image');
$editor = get_field('editor');
?>
<div class="images-layout">
    <div class="container container--full-width">
        <div class="images-layout__container">
            <?php if (!$image_right) : ?>
                <?php if (!empty($image['url'])) : ?>
                    <img class="images-layout__img images-layout__img--full-height fade-in__container fade-in__animated-start"
                         src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                <?php endif; ?>
            <?php endif; ?>

            <?php if (!empty($editor)) : ?>
                <div class="images-layout__text images-layout__text--align-top fade-in__container fade-in__animated-start">
                    <?php echo $editor; ?>
                </div>
            <?php endif; ?>

            <?php if ($image_right) : ?>
                <?php if (!empty($image['url'])) : ?>
                    <img class="images-layout__img images-layout__img--full-height fade-in__container fade-in__animated-start"
                         src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>