<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
?>
<address <?php echo get_block_wrapper_attributes(); ?>>
	<?php if ($attributes['svgIcon']) : ?>
		<svg width="800px" height="800px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
			<path d="M4 7.00005L10.2 11.65C11.2667 12.45 12.7333 12.45 13.8 11.65L20 7" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
			<rect x="3" y="5" width="18" height="14" rx="2" stroke="#000000" stroke-width="2" stroke-linecap="round" />
		</svg>
	<?php endif; ?>
	<p><?php echo wp_kses_post(get_post_meta(23, 'company_email', true)); ?></p>
</address>