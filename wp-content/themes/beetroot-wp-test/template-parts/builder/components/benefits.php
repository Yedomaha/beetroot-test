<?php
/**
 * Block Name: Benefits
 */
$title         = get_field( 'title' );
$benefits_list = get_field( 'benefits_list' );
?>

<section class="benefits">
    <div class="container container--sm">
        <div class="benefits__inner">

			<?php if ( ! empty( $title ) ): ?>

                <div class="benefits__title"><?php echo $title ?></div>

			<?php endif; ?>

			<?php if ( ! empty( $benefits_list ) ): ?>

                <ul class="benefits__list">

					<?php foreach ( $benefits_list as $item ):
						$item_icon = $item['icon'];
						$item_text = $item['text'];
						?>

                        <li class="benefits__item">

							<?php if ( ! empty( $item_icon ) ): ?>

                                <div class="benefits__icon-wrap">
                                    <img class="benefits__icon" <?php echo getImageAttributesById( $item_icon['id'], 400 ); ?>>
                                </div>

							<?php endif; ?>

							<?php if ( ! empty( $item_text ) ): ?>

                                <div class="benefits__item-text">
									<?php echo $item_text ?>
                                </div>

							<?php endif; ?>

                        </li>

					<?php endforeach; ?>

                </ul>

			<?php endif; ?>

        </div>
    </div>
</section>
