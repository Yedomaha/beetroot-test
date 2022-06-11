<?php
/*
Template Name: All Pages (Develop)
*/
$posts_pages = get_field('posts_pages');
?>
<?php get_header() ?>
<main class="pages-list">
    <?php if (!empty($posts_pages)) :
        foreach ($posts_pages as $block) :
            $caption = $block['caption'];
            $relationship = $block['relationship'];
            ?>
            <ul class="dark-header">
                <?php if (!empty($caption)) : ?>
                    <h3><?php echo $caption; ?></h3>
                <?php endif; ?>
                <?php
                //Post typess
                if (!empty($relationship)) :
                    foreach ($relationship as $item) :
                        $posts_pages_select = $item['posts_pages_select'];
                        $custom_links = $item['custom_links'];
                        ?>
                        <li>
                        <?php if (!empty($posts_pages_select)) : ?>
                        <?php
                        $args = array(
                            'posts_per_page' => -1,
                            'post_type' => 'any',
                            'order' => 'ASC',
                            'orderby' => 'post__in',
                            'post__in' => $posts_pages_select
                        );
                        $query = new WP_Query($args);
                        ?>
                        <?php if ($query->have_posts()) : ?>
                            <?php while ($query->have_posts()) : ?>
                                <?php $query->the_post();
                                $title = get_the_title();
                                $link = get_the_permalink();
                                ?>
                                <a href="<?php echo $link; ?>" target="_blank"><?php echo $title; ?></a>
                            <?php endwhile; ?>
                            <?php wp_reset_query(); ?>
                        <?php endif; ?>
                        </li>
                    <?php endif; ?>

                        <?php
                        //Custom links
                        if (!empty($custom_links)) : ?>
                            <?php foreach ($custom_links as $links) :
                                $link = $links['link'];
                                ?>
                                <li>
                                    <?php if ($link) :
                                        $link_url = $link['url'];
                                        $link_title = $link['title'];
                                        $link_target = $link['target'] ? $link['target'] : '_self';
                                        ?>
                                        <a href="<?php echo $link_url; ?>"
                                           target="_blank"><?php echo $link_title; ?></a>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>


                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        <?php endforeach; ?>
    <?php endif; ?>
</main>

<?php get_footer() ?>
