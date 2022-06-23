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

        <section class="job-openings">
            <div class="container">
                <div class="job-openings__inner">
                    <div class="job-openings__title-area">
                        <h1 class="job-openings__title">Join the team</h1>
                        <div class="job-openings__text">A career at Beetroot is for those who are passionate and
                            curious.
                            It's a home
                            for those who take responsibility and approach life with the right attitude.
                        </div>
                    </div>
                    <div class="job-filter job-openings__filter">
                        <div class="search-bar job-filter__search-bar">
                            <div class="search-bar__input-wrap">
                                <input class="search-bar__input"
                                       placeholder="<?php _e( 'Search job openings', 'beetroot-wp-test' ) ?>"
                                       data-filter="search"
                                       type="text">
                            </div>
                            <div class="search-bar__drop-down-wrap">
                                <button class="search-bar__drop-down-btn">All departments</button>
                            </div>
                            <div class="search-bar__drop-down-wrap">
                                <button class="search-bar__drop-down-btn">All locations</button>
                            </div>
                        </div>
                        <ul class="tags-list job-filter__tags-list">
                            <li class="tags-list__item active">
                                <span class="tags-list__item-name">Hot</span>
                                <span class="tags-list__item-count">6</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">Java</span>
                                <span class="tags-list__item-count">43</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">WordPress</span>
                                <span class="tags-list__item-count">37</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">C#</span>
                                <span class="tags-list__item-count">29</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">PHP</span>
                                <span class="tags-list__item-count">18</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">Python</span>
                                <span class="tags-list__item-count">13</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">C++</span>
                                <span class="tags-list__item-count">11</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">Ruby</span>
                                <span class="tags-list__item-count">8</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">Angular</span>
                                <span class="tags-list__item-count">7</span>
                            </li>
                            <li class="tags-list__item">
                                <span class="tags-list__item-name">iOS</span>
                                <span class="tags-list__item-count">7</span>
                            </li>

                            <li class="tags-list__item hidden">
                                <span class="tags-list__item-name">Android</span>
                                <span class="tags-list__item-count">6</span>
                            </li>
                            <li class="tags-list__item hidden">
                                <span class="tags-list__item-name">Android</span>
                                <span class="tags-list__item-count">6</span>
                            </li>
                            <li class="tags-list__item hidden">
                                <span class="tags-list__item-name">Android</span>
                                <span class="tags-list__item-count">6</span>
                            </li>

                            <li data-show="more" class="tags-list__item"><?php _e( 'More', 'lang-td' ) ?></li>
                            <li data-show="less" class="tags-list__item active"><?php _e( 'Less', 'lang-td' ) ?></li>

                            <li data-view-switch class="tags-list__item tags-list__item--view-switch">
                                <div data-grid-type="grid"
                                     class="tags-list__grid-type tags-list__grid-type--grid active">
									<?php get_template_part( 'template-parts/inline-svg/grid-type-svg' ) ?>
                                </div>
                                <div data-grid-type="list" class="tags-list__grid-type tags-list__grid-type--list">
									<?php get_template_part( 'template-parts/inline-svg/list-type-svg' ) ?>
                                </div>
                            </li>
                        </ul>
                        <div class="job-filter__grid">
                            <div class="job-filter__header">
                                <div class="job-filter__header-col">37 openings</div>
                                <div class="job-filter__header-col">department</div>
                                <div class="job-filter__header-col">location</div>
                                <div class="job-filter__header-col">tags</div>
                                <div class="job-filter__header-col">client</div>
                            </div>

                            <article class="cpt-item">
                                <div class="cpt-item__inner">
                                    <div class="cpt-item__header">
                                        <h2 class="cpt-item__title cpt-item__title--with-logo">
                                            <a href="">Senior Magento Developer</a>
                                        </h2>
                                        <div class="cpt-item__logo-wrap">
                                            <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                                 alt="">
                                        </div>
                                    </div>
                                    <div class="cpt-item__text">
                                        <p>Work on big e-commerce projects, have influence on making decisions and feel
                                            part
                                            of a working environment that values trust.</p>
                                    </div>
                                    <div class="cpt-item__department">Quality Assurance</div>
                                    <div class="cpt-item__footer">
                                        <a class="cpt-item__cta"
                                           href=""><?php _e( 'Details', 'beetroot-wp-test' ) ?></a>
                                        <div class="cpt-item__locations">
					                        <?php get_template_part( 'template-parts/inline-svg/location-svg' ) ?>
                                            <div class="cpt-item__locations-list">Kyiv</div>
                                        </div>
                                        <ul class="cpt-item__tags-list">
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">PHP</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                        </ul>
                                    </div>
                                    <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">PHP</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                    </ul>
                                    <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">
                                        <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                             alt="">
                                    </div>
                                </div>
                            </article>
                            <article class="cpt-item">
                                <div class="cpt-item__inner">
                                    <div class="cpt-item__header">
                                        <h2 class="cpt-item__title cpt-item__title--with-logo">
                                            <a href="">Senior Magento Developer</a>
                                        </h2>
                                        <div class="cpt-item__logo-wrap">
                                            <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                                 alt="">
                                        </div>
                                    </div>
                                    <div class="cpt-item__text">
                                        <p>Work on big e-commerce projects, have influence on making decisions and feel
                                            part
                                            of a working environment that values trust.</p>
                                    </div>
                                    <div class="cpt-item__department">Quality Assurance</div>
                                    <div class="cpt-item__footer">
                                        <a class="cpt-item__cta"
                                           href=""><?php _e( 'Details', 'beetroot-wp-test' ) ?></a>
                                        <div class="cpt-item__locations">
					                        <?php get_template_part( 'template-parts/inline-svg/location-svg' ) ?>
                                            <div class="cpt-item__locations-list">Kyiv</div>
                                        </div>
                                        <ul class="cpt-item__tags-list">
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">PHP</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                        </ul>
                                    </div>
                                    <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">PHP</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                    </ul>
                                    <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">
                                        <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                             alt="">
                                    </div>
                                </div>
                            </article>
                            <article class="cpt-item">
                                <div class="cpt-item__inner">
                                    <div class="cpt-item__header">
                                        <h2 class="cpt-item__title cpt-item__title--with-logo">
                                            <a href="">Senior Magento Developer</a>
                                        </h2>
                                        <div class="cpt-item__logo-wrap">
                                            <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                                 alt="">
                                        </div>
                                    </div>
                                    <div class="cpt-item__text">
                                        <p>Work on big e-commerce projects, have influence on making decisions and feel
                                            part
                                            of a working environment that values trust.</p>
                                    </div>
                                    <div class="cpt-item__department">Quality Assurance</div>
                                    <div class="cpt-item__footer">
                                        <a class="cpt-item__cta"
                                           href=""><?php _e( 'Details', 'beetroot-wp-test' ) ?></a>
                                        <div class="cpt-item__locations">
					                        <?php get_template_part( 'template-parts/inline-svg/location-svg' ) ?>
                                            <div class="cpt-item__locations-list">Kyiv</div>
                                        </div>
                                        <ul class="cpt-item__tags-list">
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">PHP</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                        </ul>
                                    </div>
                                    <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">PHP</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                    </ul>
                                    <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">
                                        <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                             alt="">
                                    </div>
                                </div>
                            </article>
                            <article class="cpt-item">
                                <div class="cpt-item__inner">
                                    <div class="cpt-item__header">
                                        <h2 class="cpt-item__title cpt-item__title--with-logo">
                                            <a href="">Senior Magento Developer</a>
                                        </h2>
                                        <div class="cpt-item__logo-wrap">
                                            <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                                 alt="">
                                        </div>
                                    </div>
                                    <div class="cpt-item__text">
                                        <p>Work on big e-commerce projects, have influence on making decisions and feel
                                            part
                                            of a working environment that values trust.</p>
                                    </div>
                                    <div class="cpt-item__department">Quality Assurance</div>
                                    <div class="cpt-item__footer">
                                        <a class="cpt-item__cta"
                                           href=""><?php _e( 'Details', 'beetroot-wp-test' ) ?></a>
                                        <div class="cpt-item__locations">
					                        <?php get_template_part( 'template-parts/inline-svg/location-svg' ) ?>
                                            <div class="cpt-item__locations-list">Kyiv</div>
                                        </div>
                                        <ul class="cpt-item__tags-list">
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">PHP</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                        </ul>
                                    </div>
                                    <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">PHP</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                    </ul>
                                    <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">
                                        <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                             alt="">
                                    </div>
                                </div>
                            </article>
                            <article class="cpt-item">
                                <div class="cpt-item__inner">
                                    <div class="cpt-item__header">
                                        <h2 class="cpt-item__title cpt-item__title--with-logo">
                                            <a href="">Senior Magento Developer</a>
                                        </h2>
                                        <div class="cpt-item__logo-wrap">
                                            <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                                 alt="">
                                        </div>
                                    </div>
                                    <div class="cpt-item__text">
                                        <p>Work on big e-commerce projects, have influence on making decisions and feel
                                            part
                                            of a working environment that values trust.</p>
                                    </div>
                                    <div class="cpt-item__department">Quality Assurance</div>
                                    <div class="cpt-item__footer">
                                        <a class="cpt-item__cta"
                                           href=""><?php _e( 'Details', 'beetroot-wp-test' ) ?></a>
                                        <div class="cpt-item__locations">
					                        <?php get_template_part( 'template-parts/inline-svg/location-svg' ) ?>
                                            <div class="cpt-item__locations-list">Kyiv</div>
                                        </div>
                                        <ul class="cpt-item__tags-list">
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">PHP</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                        </ul>
                                    </div>
                                    <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">PHP</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                    </ul>
                                    <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">
                                        <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                             alt="">
                                    </div>
                                </div>
                            </article>
                            <article class="cpt-item">
                                <div class="cpt-item__inner">
                                    <div class="cpt-item__header">
                                        <h2 class="cpt-item__title cpt-item__title--with-logo">
                                            <a href="">Senior Magento Developer</a>
                                        </h2>
                                        <div class="cpt-item__logo-wrap">
                                            <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                                 alt="">
                                        </div>
                                    </div>
                                    <div class="cpt-item__text">
                                        <p>Work on big e-commerce projects, have influence on making decisions and feel
                                            part
                                            of a working environment that values trust.</p>
                                    </div>
                                    <div class="cpt-item__department">Quality Assurance</div>
                                    <div class="cpt-item__footer">
                                        <a class="cpt-item__cta"
                                           href=""><?php _e( 'Details', 'beetroot-wp-test' ) ?></a>
                                        <div class="cpt-item__locations">
					                        <?php get_template_part( 'template-parts/inline-svg/location-svg' ) ?>
                                            <div class="cpt-item__locations-list">Kyiv</div>
                                        </div>
                                        <ul class="cpt-item__tags-list">
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">PHP</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                            <li class="cpt-item__tags-item">
                                                <div class="cpt-item__tag-info">
                                                    <span class="cpt-item__tag-info-text">Magento</span>
                                                    <div class="cpt-item__tag-info-arrow"></div>
                                                </div>
                                                <img src="<?php echo get_template_directory_uri() ?>/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                            </li>
                                        </ul>
                                    </div>
                                    <ul class="cpt-item__tags-list cpt-item__tags-list--list-view">
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info" style="background-color: rgb(145, 214, 190);">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow" style="border-top-color: rgb(145, 214, 190);"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">PHP</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/php-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                        <li class="cpt-item__tags-item">
                                            <div class="cpt-item__tag-info">
                                                <span class="cpt-item__tag-info-text">Magento</span>
                                                <div class="cpt-item__tag-info-arrow"></div>
                                            </div>
                                            <img src="http://beetroot-test.loc/wp-content/themes/beetroot-wp-test/dist/img/tech-icons/magento-icon.svg" alt="" class="cpt-item__tags-img">
                                        </li>
                                    </ul>
                                    <div class="cpt-item__logo-wrap cpt-item__logo-wrap--list-view">
                                        <img src="<?php echo get_template_directory_uri() ?>/dist/img/client-1.png"
                                             alt="">
                                    </div>
                                </div>
                            </article>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="pre-footer">
            <div class="container">
                <div class="pre-footer__inner">
                    <h4 class="pre-footer__title">Don’t see a role that fits?</h4>
                    <a href="" class="pre-footer__cta default-cta">Contact us</a>
                </div>
            </div>
        </section>

<?php get_footer(); ?>