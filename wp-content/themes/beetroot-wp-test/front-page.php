<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package beetroot-wp-test
 */
get_header(); ?>

    <main>

        <section class="job-openings">
            <div class="container">
                <div class="job-openings__title-area">
                    <h1 class="job-openings__title">Join the team</h1>
                    <div class="job-openings__text">A career at Beetroot is for those who are passionate and curious.
                        It's a home
                        for those who take responsibility and approach life with the right attitude.
                    </div>
                </div>
                <div class="job-filter">
                    <div class="search-bar job-filter__search-bar">
                        <div class="search-bar__input-wrap"></div>
                        <div class="search-bar__drop-down-wrap"></div>
                        <div class="search-bar__drop-down-wrap"></div>
                    </div>
                    <ul class="tags-list job-filter__tags-list">
                        <li class="tags-list__item"></li>
                        <li class="tags-list__item"></li>
                        <li class="tags-list__item"></li>
                        <li class="tags-list__item tags-list__item--grid-switcher"></li>
                    </ul>
                    <div class="job-filter__grid">

                    </div>
                </div>
            </div>
        </section>

    </main>

<?php get_footer(); ?>