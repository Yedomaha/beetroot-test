<?php
/**
 * Block Name: Text
 */
$textarea = get_field('textarea'); ?>
<div class="page-description fade-in__container fade-in__animated-start">
    <div class="container container--full-width">
        <p><?php echo $textarea ? $textarea : 'Textarea'; ?></p>
    </div>
</div>