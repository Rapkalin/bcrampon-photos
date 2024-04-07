<?php
/*
Template Name: Page with carrousel images
*/

get_header(); ?>

    <div id="container" class="<?php bravada_get_layout_class(); ?>">
        <main id="main" class="main">
            <?php get_template_part( 'content/content', 'page' ); ?>
        </main><!-- #main -->
    </div><!-- #container -->

<?php get_footer();
