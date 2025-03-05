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
	register_block_type(__DIR__ . '/build/testimonial-slider', array('render_callback' => 'fwd_render_testimonial_slider'));
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

	// Set up the WP_Query arguments for getting all terms in the custom taxonomy
	$terms = get_terms(
		array(
			'taxonomy' => 'fwd-service-category',
			'orderby'  => 'name',  // Optional: ordering terms by name
			'order'    => 'ASC',   // Optional: order terms ascending
		)
	);

	// Check if there are terms
	if ($terms && ! is_wp_error($terms)) {

		// Create the services navigation (unchanged)
		echo '<nav class="services-nav">';

		// Fetch all the service posts to display the navigation
		$args = array(
			'post_type'      => 'fwd-service',
			'posts_per_page' => -1,
			'order'          => 'ASC',
			'orderby'        => 'title'
		);

		$query = new WP_Query($args);
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				echo '<a href="#' . esc_attr(get_the_ID()) . '">' . esc_html(get_the_title()) . '</a>';
			}
			wp_reset_postdata();
		}

		echo '</nav>';

		// Loop through the terms and get posts for each term
		echo '<section>';

		foreach ($terms as $term) {
			// Output the term name as a section header
			echo '<h2>' . esc_html($term->name) . '</h2>';

			// Create a new query for the posts associated with this term
			$args = array(
				'post_type'      => 'fwd-service',
				'posts_per_page' => -1,
				'order'          => 'ASC',
				'orderby'        => 'title',
				'tax_query'      => array(
					array(
						'taxonomy' => 'fwd-service-category', // Replace with your actual taxonomy key
						'field'    => 'slug',
						'terms'    => $term->slug,
					),
				),
			);

			$term_query = new WP_Query($args);

			// Check if there are any posts for this term
			if ($term_query->have_posts()) {
				// Loop through and display the posts
				while ($term_query->have_posts()) {
					$term_query->the_post();
					echo '<article id="' . esc_attr(get_the_ID()) . '">';
					echo '<h3>' . esc_html(get_the_title()) . '</h3>';
					the_content();
					echo '</article>';
				}
				wp_reset_postdata();
			} else {
				echo '<p>No services found under this category.</p>';
			}
		}

		echo '</section>';
	}

	return ob_get_clean();
}

// Callback function for the Testimonial Slider
function fwd_render_testimonial_slider($attributes, $content)
{
	ob_start();
	$swiper_settings = array(
		'pagination' => $attributes['pagination'],
		'navigation' => $attributes['navigation']
	);

	// Set a CSS custom property for the arrow color
	$arrow_color = isset($attributes['arrowColor']) ? $attributes['arrowColor'] : '#e1ed71'; // Default fallback color
	$style = ' --arrow-color: ' . esc_attr($arrow_color) . ';';

?>
	<div <?php echo get_block_wrapper_attributes(array('style' => $style)); ?>>
		<script>
			const swiper_settings = <?php echo json_encode($swiper_settings); ?>;
		</script>
		<?php
		$args = array(
			'post_type'      => 'fwd-testimonial',
			'posts_per_page' => -1
		);
		$query = new WP_Query($args);
		if ($query->have_posts()) : ?>
			<div class="swiper">
				<div class="swiper-wrapper">
					<?php while ($query->have_posts()) : $query->the_post(); ?>
						<div class="swiper-slide">
							<?php the_content(); ?>
						</div>
					<?php endwhile; ?>
				</div>
			</div>
			<?php if ($attributes['pagination']) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
			<?php if ($attributes['navigation']) : ?>
				<button class="swiper-button-prev"></button>
				<button class="swiper-button-next"></button>
			<?php endif; ?>
		<?php
			wp_reset_postdata();
		endif;
		?>
	</div>
<?php
	return ob_get_clean();
}
