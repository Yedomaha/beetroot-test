<?php
/**
 * Block Name: Editor
 */
$editor = get_field( 'editor' ); ?>

<section class="editor">
    <div class="container container--sm">
        <div class="editor__inner">

			<?php if ( ! empty( $editor ) ): ?>

                <div class="text-content editor__content"><?php echo $editor ?></div>

			<?php endif; ?>

        </div>
    </div>
</section>