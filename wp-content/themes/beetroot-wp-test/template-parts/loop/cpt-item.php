<?php
$client_logo     = get_field( 'client_logo' );
$title_modifier  = ! empty( $client_logo ) ? 'cpt-item__title--with-logo' : false;
$job_type        = get_field( 'job_type' );
$job_description = get_field( 'job_description' );

$job_locations = get_the_terms( get_the_ID(), 'job_locations' );
$job_locations = get_terms_string( $job_locations, ', ' );

$job_departments      = get_the_terms( get_the_ID(), 'job_departments' );
$job_departments_list = get_terms_string( $job_departments, ', ' );
?>

<article class="cpt-item">
    <div class="cpt-item__inner">
        <div class="cpt-item__header">
            <h2 class="cpt-item__title <?php echo $title_modifier ?>">
                <a href="<?php the_permalink() ?>"><?php the_title() ?></a>
            </h2>

			<?php if ( ! empty( $client_logo ) ): ?>

                <div class="cpt-item__logo-wrap">
                    <img <?php echo getImageAttributesById( $client_logo['id'], 500 ); ?>>
                </div>

			<?php endif; ?>

        </div>

		<?php if ( ! empty( $job_description ) ): ?>

            <div class="cpt-item__text"><?php echo $job_description ?></div>

		<?php endif; ?>

		<?php if ( ! empty( $job_departments_list ) ): ?>

            <div class="cpt-item__department"><?php echo $job_departments_list ?></div>

		<?php endif; ?>

        <div class="cpt-item__footer">
            <a class="cpt-item__cta"
               href="<?php the_permalink() ?>"><?php _e( 'Details', 'beetroot-wp-test' ) ?></a>

			<?php if ( ! empty( $job_locations ) ): ?>

                <div class="cpt-item__locations">
					<?php get_template_part( 'template-parts/inline-svg/location-svg' ) ?>
                    <div class="cpt-item__locations-list"><?php echo $job_locations ?></div>
                </div>

			<?php endif; ?>

            <ul class="cpt-item__tags-list">
                <li class="cpt-item__tags-item">
                    <div class="cpt-item__tag-info"
                         style="background-color: rgb(145, 214, 190);">
                        <span class="cpt-item__tag-info-text">Magento</span>
                        <div class="cpt-item__tag-info-arrow"
                             style="border-top-color: rgb(145, 214, 190);"></div>
                    </div>
                    <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg"
                         alt="" class="cpt-item__tags-img">
                </li>
                <li class="cpt-item__tags-item">
                    <div class="cpt-item__tag-info">
                        <span class="cpt-item__tag-info-text">PHP</span>
                        <div class="cpt-item__tag-info-arrow"></div>
                    </div>
                    <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg"
                         alt="" class="cpt-item__tags-img">
                </li>
                <li class="cpt-item__tags-item">
                    <div class="cpt-item__tag-info">
                        <span class="cpt-item__tag-info-text">Magento</span>
                        <div class="cpt-item__tag-info-arrow"></div>
                    </div>
                    <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg"
                         alt="" class="cpt-item__tags-img">
                </li>
            </ul>

        </div>

        <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">
            <li class="cpt-item__tags-item">
                <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                    <span class="cpt-item__tag-info-text">Magento</span>
                    <div class="cpt-item__tag-info-arrow"
                         style="border-top-color: rgb(145, 214, 190);"></div>
                </div>
                <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg"
                     alt="" class="cpt-item__tags-img">
            </li>
            <li class="cpt-item__tags-item">
                <div class="cpt-item__tag-info">
                    <span class="cpt-item__tag-info-text">PHP</span>
                    <div class="cpt-item__tag-info-arrow"></div>
                </div>
                <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/php-icon.svg"
                     alt="" class="cpt-item__tags-img">
            </li>
            <li class="cpt-item__tags-item">
                <div class="cpt-item__tag-info">
                    <span class="cpt-item__tag-info-text">Magento</span>
                    <div class="cpt-item__tag-info-arrow"></div>
                </div>
                <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg"
                     alt="" class="cpt-item__tags-img">
            </li>
        </ul>

        <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">

			<?php if ( ! empty( $client_logo ) ): ?>

                <img <?php echo getImageAttributesById( $client_logo['id'], 500 ); ?>>

			<?php endif; ?>

        </div>
    </div>
</article>