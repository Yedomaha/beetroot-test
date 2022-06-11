<?php
/**
 * Block Name: Project extra
 */

$extra_links_title = get_field('extra_links_title');
$extra_logos_description = get_field('extra_logos_description');
$extra_title_logo = get_field('extra_title_logo');
?>
<div class="project-extra fade-in__container">
    <div class="container container--full-width">
        <div class="project-extra__container project-extra__line-anim">
            <div class="project-extra__section project-extra__section--links project-extra__line-anim">
                <?php if (!empty($extra_links_title)) : ?>
                    <h3 class="project-extra__title fade-in__animated-start"><?php echo $extra_links_title; ?></h3>
                <?php endif; ?>
                <?php if (have_rows('extra_links')): ?>
                    <ul class="project-extra__links-list">
                        <?php while (have_rows('extra_links')): the_row();
                            $link = get_sub_field('link');
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            if (!empty($link['url'])) : ?>
                                <li class="fade-in__animated-start">
                                    <a href="<?php echo $link['url']; ?>"
                                       target="<?php echo $link_target; ?>"><?php echo $link['title']; ?></a>
                                </li>
                            <?php endif; ?>
                        <?php endwhile; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="project-extra__section project-extra__section--awards">
                <?php if (!empty($extra_logos_description)) : ?>
                    <p class="project-extra__description fade-in__animated-start"><?php echo $extra_logos_description; ?></p>
                <?php endif; ?>
                <?php if (!empty($extra_title_logo)) : ?>
                    <h3 class="project-extra__title fade-in__animated-start"><?php echo $extra_title_logo; ?></h3>
                <?php endif; ?>
                <?php if (have_rows('logos')): ?>
                    <div class="project-extra__awards">
                        <?php while (have_rows('logos')): the_row();
                            $light_logo = get_sub_field('light_logo');
                            $dark_logo = get_sub_field('dark_logo');
                            ?>
                            <div class="project-extra__logo project-extra__logo--red-dot fade-in__animated-start">
                                <?php if (!empty($dark_logo['url'])) : ?>
                                    <img class="project-extra__logo-img--positive"
                                         src="<?php echo $dark_logo['url']; ?>"
                                         alt="<?php echo $dark_logo['alt']; ?>">
                                <?php endif; ?>
                                <?php if (!empty($light_logo['url'])) : ?>
                                    <img class="project-extra__logo-img--negative"
                                         src="<?php echo $light_logo['url']; ?>"
                                         alt="<?php echo $light_logo['alt']; ?>">
                                <?php endif; ?>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
