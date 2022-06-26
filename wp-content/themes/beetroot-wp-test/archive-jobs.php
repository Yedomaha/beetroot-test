<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package beetroot-wp-test
 */

get_header();

/*Page content fields*/
$jobs_cpt_group  = get_field( 'jobs_cpt', 'options' );
$title           = $jobs_cpt_group['title'];
$text            = $jobs_cpt_group['text'];
$pre_footer_text = $jobs_cpt_group['pre_footer_text'];
$pre_footer_cta  = $jobs_cpt_group['pre_footer_cta'];


/*Job posts taxonomies*/
$job_departments = get_categories( [
	'taxonomy'   => 'job_departments',
	'hide_empty' => true,
] );

$job_locations = get_categories( [
	'taxonomy'   => 'job_locations',
	'hide_empty' => true,
] );

$job_locations_academy = get_categories( [
	'taxonomy'   => 'job_locations_academy',
	'hide_empty' => true,
] );

$job_technologies = get_categories( [
	'taxonomy'   => 'job_technologies',
	'hide_empty' => true,
] );

$locations_drop_down_modifier = ( $job_locations && count( $job_locations ) > 0 && $job_locations_academy && count( $job_locations_academy ) > 0 ) ? false : 'search-bar__drop-down--one-col';


/*Job posts query*/
$posts_per_page = get_option( 'posts_per_page' );

if ( get_query_var( 'paged' ) ) {
	$paged = get_query_var( 'paged' );
} else if ( get_query_var( 'page' ) ) {
	$paged = get_query_var( 'page' );
} else {
	$paged = 1;
}

$args = array(
	'paged'          => $paged,
	'posts_per_page' => $posts_per_page,
	'post_type'      => 'jobs',
	'orderby'        => 'date',
	'post_status'    => 'publish',
);

$jobs_query           = new WP_Query( $args );
$posts_count          = $jobs_query->found_posts;
$load_more_btn_status = ( $paged >= $jobs_query->max_num_pages ) ? 'hide' : '';

$posts_count_string = $posts_count === 1 ? $posts_count.' opening' : $posts_count.' openings';

?>

<section class="job-openings">
    <div class="container">
        <div class="job-openings__inner">

            <div class="job-openings__title-area">

				<?php if ( ! empty( $title ) ): ?>

                    <h1 class="job-openings__title"><?php echo $title ?></h1>

				<?php endif; ?>

				<?php if ( ! empty( $text ) ): ?>

                    <div class="job-openings__text"><?php echo $text ?></div>


				<?php endif; ?>

            </div>

            <div data-filter-wrap class="job-filter job-openings__filter">
                <div class="search-bar job-filter__search-bar">

                    <div class="search-bar__input-wrap">
                        <input class="search-bar__input"
                               placeholder="<?php _e( 'Search job openings', 'beetroot-wp-test' ) ?>"
                               data-filter="search"
                               type="text">
                    </div>

                    <div class="search-bar__drop-down-wrap">
                        <button data-placeholder="<?php _e( 'All departments', 'beetroot-wp-test' ) ?>" class="search-bar__drop-down-btn"><?php _e( 'All departments', 'beetroot-wp-test' ) ?></button>
                        <div class="search-bar__drop-down search-bar__drop-down--one-col">
                            <div class="search-bar__drop-down-col">
                                <h3 class="search-bar__drop-down-title"><?php _e( 'Departments', 'beetroot-wp-test' ) ?></h3>

								<?php if ( $job_departments && count( $job_departments ) > 0 ): ?>

                                    <ul class="search-bar__drop-down-list">

										<?php foreach ( $job_departments as $item ): ?>

                                            <li class="search-bar__drop-down-item">
                                                <label class="checkbox search-bar__checkbox"><?php echo $item->name ?>
                                                    <input data-filter="department"
                                                           data-value="<?php echo $item->term_id ?>"
                                                           data-name="<?php echo $item->name ?>"
                                                           type="checkbox"
                                                           class="checkbox__input">
                                                    <span class="checkbox__span"></span>
                                                </label>
                                                <div class="search-bar__counter"><?php echo $item->count ?></div>
                                            </li>

										<?php endforeach; ?>

                                    </ul>

                                    <div class="search-bar__change-button-wrap">
                                        <button data-change
                                                data-select-all="<?php _e( 'select all', 'beetroot-wp-test' ) ?>"
                                                data-hide-all="<?php _e( 'deselect all', 'beetroot-wp-test' ) ?>"
                                                class="search-bar__change-all"><?php _e( 'select all', 'beetroot-wp-test' ) ?></button>
                                    </div>

								<?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!--todo:-->
                    <div class="search-bar__drop-down-wrap">
                        <button data-placeholder="<?php _e( 'All locations', 'beetroot-wp-test' ) ?>" class="search-bar__drop-down-btn"><?php _e( 'All locations', 'beetroot-wp-test' ) ?></button>
                        <div class="search-bar__drop-down <?php echo $locations_drop_down_modifier ?>">

							<?php if ( $job_locations && count( $job_locations ) > 0 ): ?>

                                <div class="search-bar__drop-down-col">
                                    <h3 class="search-bar__drop-down-title"><?php _e( 'Offices', 'beetroot-wp-test' ) ?></h3>
                                    <ul class="search-bar__drop-down-list">

										<?php foreach ( $job_locations as $item ): ?>

                                            <li class="search-bar__drop-down-item">
                                                <label class="checkbox search-bar__checkbox"><?php echo $item->name ?>
                                                    <input data-filter="locations_office"
                                                           data-value="<?php echo $item->term_id ?>"
                                                           data-name="<?php echo $item->name ?>"
                                                           type="checkbox"
                                                           class="checkbox__input">
                                                    <span class="checkbox__span"></span>
                                                </label>
                                                <div class="search-bar__counter"><?php echo $item->count ?></div>
                                            </li>

										<?php endforeach; ?>

                                    </ul>
                                    <div class="search-bar__change-button-wrap">
                                        <button data-change
                                                data-select-all="<?php _e( 'select all', 'beetroot-wp-test' ) ?>"
                                                data-hide-all="<?php _e( 'deselect all', 'beetroot-wp-test' ) ?>"
                                                class="search-bar__change-all"><?php _e( 'select all', 'beetroot-wp-test' ) ?></button>
                                    </div>
                                </div>

							<?php endif; ?>

							<?php if ( $job_locations_academy && count( $job_locations_academy ) > 0 ): ?>

                                <div class="search-bar__drop-down-col search-bar__drop-down-col--green">
                                    <h3 class="search-bar__drop-down-title"><?php _e( 'Academies', 'beetroot-wp-test' ) ?></h3>
                                    <ul class="search-bar__drop-down-list">

										<?php foreach ( $job_locations_academy as $item ): ?>

                                            <li class="search-bar__drop-down-item">
                                                <label class="checkbox search-bar__checkbox"><?php echo $item->name ?>
                                                    <input data-filter="locations_academy"
                                                           data-value="<?php echo $item->term_id ?>"
                                                           data-name="<?php echo $item->name ?>"
                                                           type="checkbox"
                                                           class="checkbox__input">
                                                    <span class="checkbox__span"></span>
                                                </label>
                                                <div class="search-bar__counter"><?php echo $item->count ?></div>
                                            </li>

										<?php endforeach; ?>

                                    </ul>
                                    <div class="search-bar__change-button-wrap">
                                        <button data-change
                                                data-select-all="<?php _e( 'select all', 'beetroot-wp-test' ) ?>"
                                                data-hide-all="<?php _e( 'deselect all', 'beetroot-wp-test' ) ?>"
                                                class="search-bar__change-all"><?php _e( 'select all', 'beetroot-wp-test' ) ?></button>
                                    </div>
                                </div>

							<?php endif; ?>

                        </div>
                    </div>
                </div>

                <ul class="tags-list tags-list--disabled job-filter__tags-list hidden">

					<?php if ( $job_technologies && count( $job_technologies ) > 0 ): ?>
						<?php foreach ( $job_technologies as $item ): ?>

                            <li class="tags-list__item">
                                <button data-filter="technology"
                                        data-value="<?php echo $item->term_id ?>"
                                        class="tags-list__btn">
                                    <span class="tags-list__item-name"><?php echo $item->name ?></span>
                                    <span class="tags-list__item-count"><?php echo $item->count ?></span>
                                </button>
                            </li>

						<?php endforeach; ?>
					<?php endif; ?>

                    <li class="tags-list__item tags-list__item--count-to-show">
                        <button data-show="more" class="tags-list__btn">
							<?php _e( 'More', 'lang-td' ) ?>
                        </button>
                    </li>
                    <li class="tags-list__item tags-list__item--count-to-show hidden">
                        <button data-show="less" class="tags-list__btn">
							<?php _e( 'Less', 'lang-td' ) ?>
                        </button>
                    </li>

                    <li data-view-switch class="tags-list__item tags-list__item--view-switch">
                        <button data-grid-type="grid"
                                class="tags-list__grid-type active">
							<?php get_template_part( 'template-parts/inline-svg/grid-type-svg' ) ?>
                        </button>
                        <button data-grid-type="list"
                                class="tags-list__grid-type">
							<?php get_template_part( 'template-parts/inline-svg/list-type-svg' ) ?>
                        </button>
                    </li>

                </ul>

				<?php if ( $jobs_query->have_posts() ) : ?>

                    <div class="job-filter__grid-wrap">
                        <div class="job-filter__grid-header">
                            <div class="job-filter__header-col">
                                <span data-posts-counter><?php echo $posts_count_string ?></span>
                            </div>
                            <div class="job-filter__header-col"><?php _e( 'department', 'beetroot-wp-test' ) ?></div>
                            <div class="job-filter__header-col"><?php _e( 'location', 'beetroot-wp-test' ) ?></div>
                            <div class="job-filter__header-col"><?php _e( 'tags', 'beetroot-wp-test' ) ?></div>
                            <div class="job-filter__header-col"><?php _e( 'client', 'beetroot-wp-test' ) ?></div>
                        </div>

                        <div data-filter-grid
                             data-ajax-url="<?php echo admin_url( 'admin-ajax.php' ) ?>"
                             data-posts-per-page="<?php echo $posts_per_page ?>"
                             class="job-filter__grid">

							<?php while ( $jobs_query->have_posts() ) : $jobs_query->the_post(); ?>
								<?php get_template_part( 'template-parts/loop/cpt-item' ) ?>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>

                        </div>

                        <div class="job-filter__load-more-wrap">
                            <button data-filter-load-more
                                    class="job-filter__load-more-btn"><?php _e( 'Load more', 'beetroot-wp-test' ) ?></button>
                        </div>

                    </div>

				<?php else: ?>

                        <div class="noPostsText">No posts found.</div>

				<?php endif; ?>
            </div>
        </div>
    </div>
</section>

<section class="pre-footer">
    <div class="container">
        <div class="pre-footer__inner">

			<?php if ( ! empty( $pre_footer_text ) ): ?>

                <h4 class="pre-footer__title"><?php echo $pre_footer_text ?></h4>

			<?php endif; ?>

			<?php if ( ! empty( $pre_footer_cta ) ): ?>

                <a href="<?php echo $pre_footer_cta['url'] ?>"
                   target="<?php echo $pre_footer_cta['target'] ?>"
                   class="pre-footer__cta default-cta"><?php echo $pre_footer_cta['title'] ?></a>

			<?php endif; ?>

        </div>
    </div>
</section>

<?php get_footer(); ?>




