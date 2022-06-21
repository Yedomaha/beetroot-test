<?php get_header();

$logo            = get_field( 'logo' );
$job_type        = get_field( 'job_type' );
$job_description = get_field( 'job_description' );

$job_cities      = get_the_terms( $post->ID, 'job_locations' );
$job_cities_list = get_terms_string( $job_cities, ' & ' );

$job_technologies = get_the_terms( $post->ID, 'job_technologies' );

?>

<section class="job-hero">
    <div class="container container--sm">
        <div class="job-hero__inner">
            <h1 class="job-hero__title"><?php the_title(); ?></h1>

			<?php if ( ! empty( $job_description ) ): ?>

                <div class="job-hero__description"><?php echo $job_description ?></div>


			<?php endif; ?>

            <div class="job-hero__footer">
                <div class="job-hero__cta-wrap">
                    <a href="#apply"
                       class="job-hero__cta default-cta default-cta--red"><?php _e( 'Apply now', 'beetroot-wp-test' ) ?></a>
                </div>

				<?php if ( ! empty( $job_cities_list ) ): ?>

                    <div class="job-hero__cities"><?php echo $job_cities_list ?></div>

				<?php endif; ?>


				<?php if ( $job_technologies ): ?>

                    <ul class="job-hero__technologies-list tags">

						<?php foreach ( $job_technologies as $item ): ?>

                            <li class="tags__item">
                                <div class="tags__info">
                                    <span class="tags__info-text">PHP</span>
                                    <div class="tags__info-arrow"></div>
                                </div>
                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg"
                                     alt="" class="tags__img">
                            </li>

                            <!--		                --><?php //echo 'name - ' . $item->name . '<br>' . 'id - ' . $item->term_id; ?>


						<?php endforeach; ?>

                    </ul>

				<?php endif; ?>

				<?php if ( ! empty( $logo ) ): ?>

                    <div class="job-hero__logo-wrap">
                        <img class="job-hero__logo" <?php echo getImageAttributesById( $logo['id'], 500 ); ?>>
                    </div>

				<?php endif; ?>

            </div>

        </div>
    </div>
</section>

<?php the_content(); ?>

<section id="apply" class="apply">
    <div class="container container--sm">
        <div class="apply__inner">

            <h3 class="apply__title"><?php _e( 'Apply for this position', 'beetroot-wp-test' ) ?></h3>

            <div class="apply-form apply__form-wrap">

				<?php echo do_shortcode( '[contact-form-7 id="485" title="Apply for a job"]' ); ?>

				<?php $show = false;
				if ( $show ): ?>

                    <div class="apply-form__row">
                        <div class="apply-form__col apply-form__col--3">
                            <label><span class="apply-form__label-inner">Name<span
                                            class="apply-form__required"> *</span></span>
                                [text* your-name]
                            </label>
                        </div>
                        <div class="apply-form__col apply-form__col--3">
                            <label><span class="apply-form__label-inner">Email<span
                                            class="apply-form__required"> *</span></span>
                                [email* your-email]
                            </label>
                        </div>
                        <div class="apply-form__col apply-form__col--3">
                            <label><span class="apply-form__label-inner">Phone<span
                                            class="apply-form__required"> *</span></span>
                                [tel* your-tel]
                            </label>
                        </div>
                    </div>

                    <div class="apply-form__row">
                        <div class="apply-form__col">
                            <label><span class="apply-form__label-inner">Message</span>
                                [text your-message]
                            </label>
                        </div>
                    </div>

                    <div class="apply-form__row apply-form__row--files">
                        <div class="apply-form__col apply-form__col--2">
                            <div class="apply-form__file-input-wrap">
                                <div class="apply-form__file-title">CV<span class="apply-form__required"> *</span></div>
                                <label class="apply-form__file-label">Attach
                                    [file* file-cv filetypes:pdf|doc|docx]
                                </label>
                                <div class="apply-form__file-input-result"></div>
                            </div>
                        </div>
                        <div class="apply-form__col apply-form__col--2">
                            <div class="apply-form__file-input-wrap">
                                <div class="apply-form__file-title">Cover Letter<span
                                            class="apply-form__required"> *</span>
                                </div>
                                <label class="apply-form__file-label">Attach
                                    [file* cover-letter filetypes:pdf|doc|docx]
                                </label>
                                <div class="apply-form__file-input-result"></div>
                            </div>
                        </div>
                    </div>

                    <div class="apply-form__row apply-form__row--checkboxes">
                        <div class="apply-form__col">
                            <div class="apply-form__checkbox">
                                [checkbox* privacy-policy use_label_element "I agree with Beetroot’s Privacy Policy and
                                saving
                                data in compliance with Swedish law."]
                            </div>
                        </div>

                        <div class="apply-form__col">
                            <div class="apply-form__checkbox">
                                [checkbox* contact-me use_label_element "Contact me with job offers in the future."]
                            </div>
                        </div>
                    </div>

                    <div class="apply-form__hidden">
                        [text job-page-url]
                    </div>

                    <div class="apply-form__footer">
                        [submit class:default-cta class:default-cta--red "Submit"]
                    </div>

				<?php endif; ?>

            </div>


        </div>
    </div>
</section>

<section class="pre-footer pre-footer--gray">
    <div class="container container--sm">
        <div class="pre-footer__inner">
            <h4 class="pre-footer__title">Know someone who might be interested?</h4>

        </div>
    </div>
</section>


<?php get_footer(); ?>
