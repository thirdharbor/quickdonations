<?php
/**
 * Template Name: WC Donation Template
 * Template Post Type: wc-donation
 */

get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        while ( have_posts() ) :
            the_post();
            
            // Display the campaign title and content
            the_title('<h1 class="entry-title">', '</h1>');
            the_content();
            
            // Include your campaign grid here
            echo do_shortcode('[campaign_grid id="' . get_the_ID() . '"]');

            // If comments are open or there are comments, load up the comment template
            if ( comments_open() || get_comments_number() ) :
                comments_template();
            endif;

        endwhile; // End of the loop.
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php

get_footer();
