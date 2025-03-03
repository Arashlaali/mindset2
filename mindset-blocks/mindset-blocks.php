<?php



/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function mindset_blocks_copyright_date_block_init()
{
	register_block_type(__DIR__ . '/build/copyright-date');
	register_block_type(__DIR__ . '/build/company-address');
	register_block_type(__DIR__ . '/build/company-email');
	register_block_type(__DIR__ . '/build/company-services', array(
		'render_callback' => 'fwd_render_company_services'
	));
}
add_action('init', 'mindset_blocks_copyright_date_block_init');

function mindset_register_custom_fields()
{
	register_post_meta(
		'page',
		'company_email',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'single'       => true
		)
	);
	register_post_meta(
		'page',
		'company_address',
		array(
			'type'         => 'string',
			'show_in_rest' => true,
			'single'       => true
		)
	);
}
add_action('init', 'mindset_register_custom_fields');


function fwd_render_company_services($attributes)
{
	ob_start();
?>
	<div <?php echo get_block_wrapper_attributes(); ?>>
		<?php
		$args = array(
			'orderby' => 'title',
			'order' => 'DESC',
			'post_type'      => 'fwd-service',
			'posts_per_page' => 100
		);

		$query = new WP_Query($args);

		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();

				echo '<article id="post-' . get_the_ID() . '">';
				echo '<h2>' . get_the_title() . '</h2>'; // Title in h2
				the_content(); // Content from block editor
				echo '</article>';
			}
			wp_reset_postdata();
		}
		?>
	</div>
<?php
	return ob_get_clean();
}
