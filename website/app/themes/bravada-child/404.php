<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package Bravada
 */

get_header(); ?>

	<div id="container" class="<?php bravada_get_layout_class(); ?>">
		<main id="main" class="main">
			<?php cryout_before_content_hook(); ?>

			<header id="post-0" class="pad-container error404 not-found bravada-child-404-header" <?php cryout_schema_microdata( 'element' ); ?>>
				<h1 class="entry-title" <?php cryout_schema_microdata( 'entry-title' ); ?>><?php _e( 'Not Found', 'bravada' ); ?></h1>
					<p <?php cryout_schema_microdata( 'text' ); ?>><?php _e( 'Apologies, but the page you requested could not be found.', 'bravada' ); ?></p>
					<a id="bravada-child-404-link" href="<?= get_home_url() ?>">Go back home</a>
					<p id="bravada-child-404-text">404</p>
			</header>
        </main><!-- #main -->
    </div><!-- #container -->

<?php get_footer(); ?>
