<?php

/**
 * Title: Book Review
 * Slug: mindset-theme/book-review
 * Categories: media,text 
 */

?>


<!-- wp:media-text {"mediaId":47,"mediaLink":"http://localhost:8888/mindset3/post-1/yellow-flowers/","mediaType":"image","mediaWidth":40,"mediaSizeSlug":"large"} -->



<div class="wp-block-media-text is-stacked-on-mobile" style="grid-template-columns:40% auto">
    <figure class="wp-block-media-text__media"><img src="<?php echo esc_url(get_theme_file_uri('assets/images/book-cover.jpg')); ?>" alt="<?php esc_html_e('Placeholder image of a book', 'mindset-theme'); ?>" class="wp-image-47 size-large" /></figure>
    <div class="wp-block-media-text__content"><!-- wp:heading -->
        <h2 class="wp-block-heading"><?php esc_html_e('Title', 'mindset-theme-2'); ?></h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph -->
        <p>Author</p>
        <!-- /wp:paragraph -->

        <!-- wp:paragraph -->
        <p>Description</p>
        <!-- /wp:paragraph -->
    </div>
</div>
<!-- /wp:media-text -->