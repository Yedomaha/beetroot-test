<?php get_header();

$logo            = get_field( 'client_logo' );
$job_type        = get_field( 'job_type' );
$job_hot         = get_field( 'hot' );
$job_description = get_field( 'job_description' );

$job_cities      = get_the_terms( $post->ID, 'job_locations' );
$job_cities_list = beetrootTestClass::get_terms_string( $job_cities, ' & ' );

$job_technologies = get_the_terms( get_the_ID(), 'job_technologies' );

if($job_type === '1'){
	$job_locations = get_the_terms( get_the_ID(), 'job_locations' );
	$locations_to_show = beetrootTestClass::get_terms_string( $job_cities, ' & ' );
} else{
	$job_locations_academy = get_the_terms( get_the_ID(), 'job_locations_academy' );
	$locations_to_show = beetrootTestClass::get_terms_string( $job_locations_academy, ' & ' );
}

?>

<section class="job-hero">
    <div class="container container--sm">
        <div class="job-hero__inner">
            <div class="job-hero__title-wrap">
                <h1 class="job-hero__title"><?php the_title(); ?></h1>
				<?php if ( $job_hot ): ?>
                    <span class="hot-label hot-label--2"><?php _e( 'Hot', 'beetroot-wp-test' ) ?></span>
				<?php endif; ?>
            </div>

			<?php if ( ! empty( $job_description ) ): ?>

                <div class="job-hero__description"><?php echo $job_description ?></div>


			<?php endif; ?>

            <div class="job-hero__footer">
                <div class="job-hero__cta-wrap">
                    <a data-anchor href="#apply"
                       class="job-hero__cta default-cta default-cta--red"><?php _e( 'Apply now', 'beetroot-wp-test' ) ?></a>
                </div>

				<?php if ( ! empty( $locations_to_show ) ): ?>

                    <div class="job-hero__cities"><?php echo $locations_to_show ?></div>

				<?php endif; ?>


	            <?php if ( $job_technologies && count( $job_technologies ) > 0 ): ?>

                    <ul class="job-hero__technologies-list tags">

						<?php foreach ( $job_technologies as $item ): ?>
							<?php
							$item_icon    = get_field( 'icon', $item );
							$icon_svg_code = get_field( 'icon_svg_code', $item );
							$item_icon_id = beetrootTestClass::get_img_id_from_arr( $item_icon );
							?>

							<?php if ( $i < 3 ): ?>
								<?php if ( ! empty( $icon_svg_code ) ): $i ++ ?>

                                    <li class="tags__item">
                                        <div class="tags__info">
                                            <span class="tags__info-text"><?php echo $item->name ?></span>
                                            <div class="tags__info-arrow"></div>
                                        </div>
										<?php echo $icon_svg_code ?>
                                    </li>


								<?php elseif ( $item_icon_id ): $i ++ ?>


                                    <li class="tags__item">
                                        <div class="tags__info">
                                            <span class="tags__info-text"><?php echo $item->name ?></span>
                                            <div class="tags__info-arrow"></div>
                                        </div>
                                        <img <?php echo beetrootTestClass::get_image_attributes_by_id( $item_icon_id, 300 ); ?> class="tags__img">
                                    </li>

								<?php endif; ?>
							<?php endif; ?>

						<?php endforeach; ?>

                    </ul>

				<?php endif; ?>

				<?php if ( ! empty( $logo ) ): ?>

                    <div class="job-hero__logo-wrap">
                        <img class="job-hero__logo" <?php echo beetrootTestClass::get_image_attributes_by_id( $logo['id'], 500 ); ?>>
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

                <div class="apply-form__data apply-form__data--job-info"
                     data-url="<?php the_permalink() ?>"
                     data-name="<?php the_title() ?>"></div>
                <div class="apply-form__data" data-privacy-text>I agree with Beetroot’s <a href="#privacy">Privacy
                        Policy</a> and saving data in compliance with Swedish law.
                </div>
                <div class="apply-form__data" data-contact-text>Contact me with job offers in the future.</div>

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
                                [checkbox* privacy-policy class:privacyCheckbox use_label_element "I agree with
                                Beetroot’s Privacy Policy and
                                saving
                                data in compliance with Swedish law."]
                            </div>
                        </div>

                        <div class="apply-form__col">
                            <div class="apply-form__checkbox">
                                [checkbox* contact-me class:contactCheckbox use_label_element "Contact me with job
                                offers in the future."]
                            </div>
                        </div>
                    </div>

                    <div class="apply-form__hidden">
                        [text job-page-title class:applyForAJobTitle]
                    </div>
                    <div class="apply-form__hidden">
                        [text job-page-url class:applyForAJobUrl]
                    </div>

                    <div class="apply-form__footer">
                        [submit class:default-cta class:default-cta--red "Submit"]
                    </div>

				<?php endif; ?>

            </div>


        </div>
    </div>
</section>

<section class="pre-footer pre-footer--gray pre-footer--share">
    <div class="container container--sm">
        <div class="pre-footer__inner">
            <h4 class="pre-footer__title">Know someone who might be interested?</h4>
            <ul class="pre-footer__share-list">
                <li class="pre-footer__share-item">
                    <img src="<?php echo get_template_directory_uri() ?>/dist/img/fb-color.svg" alt=""
                         class="pre-footer__share-icon">
                    <a href="javascript:void(0)"
                       onclick="window.open( 'http://www.facebook.com/sharer.php?u=' + window.location.href, 'sharer', 'toolbar=0, status=0');return false;"
                       title="Facebook">
                    </a>
                </li>
                <li class="pre-footer__share-item">
                    <img src="<?php echo get_template_directory_uri() ?>/dist/img/vk-color.svg" alt=""
                         class="pre-footer__share-icon">
                    <a class="btn btn-default icon"
                       href="javascript:void(0)"
                       onclick="window.open( 'https://vk.com/share.php?url=' + window.location.href, 'sharer', 'toolbar=0, status=0');return false;"
                       title="Twitter">
                    </a>
                </li>
                <li class="pre-footer__share-item">
                    <img src="<?php echo get_template_directory_uri() ?>/dist/img/tw-color.svg" alt=""
                         class="pre-footer__share-icon">
                    <a href="https://www.addtoany.com/add_to/twitter?linkurl=<?php the_permalink() ?>&linkname=<?php the_title() ?>&linknote="
                       target="_blank"
                       title="Twitter">
                    </a>
                </li>
                <li class="pre-footer__share-item">
                    <img src="<?php echo get_template_directory_uri() ?>/dist/img/in-color.svg" alt=""
                         class="pre-footer__share-icon">
                    <a href="javascript:void(0)"
                       onclick="window.open( 'https://www.linkedin.com/sharing/share-offsite/?url=' + window.location.href, 'sharer', 'toolbar=0, status=0');return false;"
                       title="Linkedin">
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>


<?php get_footer(); ?>
