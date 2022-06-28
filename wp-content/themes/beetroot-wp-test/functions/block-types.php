<?php
add_filter( 'allowed_block_types', 'custom_allowed_block_types', 10, 2 );
function custom_allowed_block_types( $allowed_blocks, $post ) {
	$allowed_blocks = array(
		'acf/editor',
		'acf/benefits',

		//'core/image',
		//'core/paragraph',
		//'core/heading',
		//'core/list'
	);

	return $allowed_blocks;
}