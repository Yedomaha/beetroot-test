<?php
/**
 * Pagination
 * See example index.php or archive.php
 */
function pagination()
{
    global $wp_query;
    if ($wp_query->max_num_pages > 1) : ?>
        <div class="cards__pagination-wrapper">
            <div class="pagination">
                <?php
                if (get_previous_posts_link()) {
                    echo '<div class="pagination__btn pagination__btn--prev prev">' . get_previous_posts_link('<svg xmlns="http://www.w3.org/2000/svg" width="5" height="10" viewBox="0 0 5 10"><defs><clipPath id="v0dwa"><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></clipPath></defs><g><g transform="matrix(0 1 1 0 -2.5 2.5)"><g><g><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></g><g clip-path="url(#v0dwa)"><g><path d="M-13-10h30v30h-30z"/></g></g></g></g></g></svg>' . '</div>');
                } else {
                    echo '<div class="pagination__btn pagination__btn--prev prev pagination__btn--disabled"><svg xmlns="http://www.w3.org/2000/svg" width="5" height="10" viewBox="0 0 5 10"><defs><clipPath id="v0dwa"><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></clipPath></defs><g><g transform="matrix(0 1 1 0 -2.5 2.5)"><g><g><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></g><g clip-path="url(#v0dwa)"><g><path d="M-13-10h30v30h-30z"/></g></g></g></g></g></svg></div>';
                } ?>
                <ul class="pagination__list">

                    <?php
                    $paged = get_query_var('paged') ? absint(get_query_var('paged')) : 1;
                    $max = intval($wp_query->max_num_pages);

                    /**    Add current page to the array */
                    if ($paged >= 1)
                        $links[] = $paged;

                    /**    Add the pages around the current page to the array */
                    if ($paged >= 3) {
                        $links[] = $paged - 1;
                        $links[] = $paged - 2;
                    }

                    if (($paged + 2) <= $max) {
                        $links[] = $paged + 2;
                        $links[] = $paged + 1;
                    }

                    /**    Link to first page, plus ellipses if necessary */
                    if (!in_array(1, $links)) {
                        $class = 1 == $paged ? ' active' : '';
                        ?>

                        <?php
                        printf('<li class="pagination__item number%s"><a href="%s">%02g</a></li>' . "\n", $class, esc_url(get_pagenum_link(1)), '1');

                        if (!in_array(2, $links))
                            echo '<li class="pagination__link number_more">...</li>';
                    }
                    ?>
                    <?php
                    /**    Link to current page, plus 2 pages in either direction if necessary */
                    sort($links);
                    foreach ((array)$links as $link) {
                        $class = $paged == $link ? ' active' : '';
                        printf('<li class="pagination__item number%s"><a class="pagination__link" href="%s">%02g</a></li>' . "\n", $class, esc_url(get_pagenum_link($link)), $link);
                        echo $active_divider = $paged == $link ? '<li class="pagination__item pagination__item__divider"><span class="pagination__divider"></span></li>' : '';
                    }
                    ?>

                    <?php
                    /**    Link to last page, plus ellipses if necessary */
                    if (!in_array($max, $links)) {
                        if (!in_array($max - 1, $links))
                            echo '<li class="pagination__link number_more">...</li>' . "\n";

                        $class = $paged == $max ? ' active' : '';
                        printf('<li class="pagination__item number%s"><a class="pagination__link" href="%s">%s</a></li>' . "\n", $class, esc_url(get_pagenum_link($max)), $max);
                        ?>
                        <?php
                    }
                    ?>
                </ul>

                <?php
                if (get_next_posts_link()) {
                    echo '<div class="pagination__btn pagination__btn--next prev">' . get_next_posts_link('<svg xmlns="http://www.w3.org/2000/svg" width="5" height="10" viewBox="0 0 5 10"><defs><clipPath id="v0dwa"><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></clipPath></defs><g><g transform="matrix(0 1 1 0 -2.5 2.5)"><g><g><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></g><g clip-path="url(#v0dwa)"><g><path d="M-13-10h30v30h-30z"/></g></g></g></g></g></svg>' . '</div>');
                } else {
                    echo '<div class="pagination__btn pagination__btn--next next pagination__btn--disabled"><svg xmlns="http://www.w3.org/2000/svg" width="5" height="10" viewBox="0 0 5 10"><defs><clipPath id="v0dwa"><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></clipPath></defs><g><g transform="matrix(0 1 1 0 -2.5 2.5)"><g><g><path d="M-1.64 3.25l-.72.96L2 7.48l4.36-3.27-.72-.96L2 5.98z"/></g><g clip-path="url(#v0dwa)"><g><path d="M-13-10h30v30h-30z"/></g></g></g></g></g></svg></div>';
                } ?>

            </div>
        </div>
    <?php endif;
}