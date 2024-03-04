<?php
/**
 * Template Name: Bio page
 */

get_header(); ?>

    <div id="container" class="<?php bravada_get_layout_class(); ?>">
        <main id="main bravada-child-bio-container" class="main">
            <?php cryout_before_content_hook(); ?>

            <?php get_template_part( 'content/content', 'page' ); ?>

            <?php cryout_after_content_hook(); ?>
        </main><!-- #main -->
    </div><!-- #container -->

<?php get_footer();
