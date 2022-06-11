<?php
/**
 * Block Name: Images layout
 */

$images_layout = get_field('images_layout');
if ($images_layout) : ?>
    <div class="images-layout">
        <div class="container container--full-width">
            <div class="images-layout__container">
                <?php foreach ($images_layout as $image) : ?>
                    <img class="images-layout__img images-layout__img--half-height fade-in__container fade-in__animated-start"
                         src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php endif; ?>