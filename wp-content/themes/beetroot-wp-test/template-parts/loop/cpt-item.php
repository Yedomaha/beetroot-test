<?php
$client_logo     = get_field( 'client_logo' );
$title_modifier  = ! empty( $client_logo ) ? 'cpt-item__title--with-logo' : false;
$job_type        = get_field( 'job_type' );
$job_description = get_field( 'job_description' );

$job_locations = get_the_terms( get_the_ID(), 'job_locations' );
$job_locations = get_terms_string( $job_locations, ', ' );

$job_departments      = get_the_terms( get_the_ID(), 'job_departments' );
$job_departments_list = get_terms_string( $job_departments, ', ' );

$job_technologies = get_the_terms( get_the_ID(), 'job_technologies' );
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

			<?php if ( $job_technologies && count( $job_technologies ) > 0 ): ?>

                <ul class="cpt-item__tags-list">

					<?php $i = 0;
					foreach ( $job_technologies as $item ): ?>
						<?php
						$item_icon    = get_field( 'icon', $item );
						$item_icon_id = checkImg( $item_icon );
						?>
						<?php if ( $item_icon_id && $i < 3 ): $i ++ ?>

                            <li class="cpt-item__tags-item">
                                <div class="cpt-item__tag-info"
                                     style="border-top-color: #bc204b;">
                                    <span class="cpt-item__tag-info-text"><?php echo $item->name ?></span>
                                    <div class="cpt-item__tag-info-arrow"
                                         style="border-top-color: #bc204b;"></div>
                                </div>
                                <img <?php echo getImageAttributesById( $item_icon_id, 300 ); ?>
                                        class="cpt-item__tags-img">
                            </li>

						<?php endif; ?>
					<?php endforeach; ?>

                </ul>

			<?php endif; ?>

        </div>

		<?php if ( $job_technologies && count( $job_technologies ) > 0 ): ?>

            <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">

				<?php $i = 0;
				foreach ( $job_technologies as $item ): ?>
					<?php
					$item_icon    = get_field( 'icon', $item );
					$item_icon_id = checkImg( $item_icon );
					?>
					<?php if ( $item_icon_id && $i < 3 ): $i ++ ?>

                        <li class="cpt-item__tags-item">
                            <div class="cpt-item__tag-info"
                                 style="border-top-color: #bc204b;">
                                <span class="cpt-item__tag-info-text"><?php echo $item->name ?></span>
                                <div class="cpt-item__tag-info-arrow"
                                     style="border-top-color: #bc204b;"></div>
                            </div>
                            <img <?php echo getImageAttributesById( $item_icon_id, 300 ); ?>
                                    class="cpt-item__tags-img">
                        </li>

					<?php endif; ?>
				<?php endforeach; ?>

            </ul>

		<?php endif; ?>

        <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">

			<?php if ( ! empty( $client_logo ) ): ?>

                <img <?php echo getImageAttributesById( $client_logo['id'], 500 ); ?>>

			<?php endif; ?>

        </div>

    </div>
</article>