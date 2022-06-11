<?php
/**
 * Block Name: Project carousel
 */

$carousel = get_field('carousel');
if ($carousel) :
    $count_carousel = count($carousel); ?>
    <div class="project-carousel swiper-container fade-in__container fade-in__animated-start">
        <div class="swiper-wrapper">
            <?php foreach ($carousel as $image) : ?>
                <div class="swiper-slide">
                    <img src="<?php echo $image['url']; ?>" alt="<?php echo $image['alt']; ?>">
                </div>
            <?php endforeach; ?>
        </div>
        <?php if ($count_carousel > 1) : ?>
            <div class="project-carousel__nav swiper-button-prev"></div>
            <div class="project-carousel__nav swiper-button-next"></div>
            <div class="project-carousel__pagination swiper-pagination"></div>
        <?php endif; ?>
    </div>
<?php endif; ?>