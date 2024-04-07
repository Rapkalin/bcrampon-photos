<?php
/**
 * The Header
 *
 * Displays all of the <head> section and everything up till <main>
 *
 * @package Bravada
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<?php cryout_meta_hook(); ?>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<link rel="profile" href="http://gmpg.org/xfn/11">
<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
    <link rel="pingback" href="<?php echo esc_url( get_bloginfo( 'pingback_url' ) ); ?>">
<?php endif; ?>
<link href="https://fonts.cdnfonts.com/css/muli" rel="stylesheet">
<script src="https://kit.fontawesome.com/a8ed703567.js" crossorigin="anonymous"></script>
<meta name="description" content="Découvrez le monde à travers l'objectif de Bernard Crampon, photographe passionné. Immortalisant moments uniques et paysages époustouflants, son blog vous emmène dans un voyage visuel sans précédent.">

    <?php
    cryout_header_hook();
    wp_head();
?>
</head>

<body
    <?php body_class(); cryout_schema_microdata( 'body' );?>>
	<?php do_action( 'wp_body_open' ); ?>
	<?php cryout_body_hook(); ?>

    <?php
        /*
         * The menu is transparent only if
         * the current page is not a children category
         */
        $extraMenuClass = "";
        $taxonomy = is_tax() ? get_queried_object() : null;
        $customTemplatePage = is_page_template('template-bio-page.php');

        if ($taxonomy || $customTemplatePage) {
            $extraMenuClass =  "bravada-over-menu-position-unset";
        }
    ?>

	<div id="site-wrapper">

	<header id="masthead" class="cryout" <?php cryout_schema_microdata( 'header' ) ?>>
		<div
                id="site-header-main"
                class="<?php echo $extraMenuClass ?>"
        >

			<div class="site-header-top">

				<div class="site-header-inside">

                    <?php if (is_category()): ?>
                        <div id="branding">
                           <?php cryout_branding_hook(); ?>
                       </div><!-- #branding -->
                    <?php endif; ?>

                    <div id="header-menu" <?php cryout_schema_microdata( 'menu' ); ?>>
						<?php cryout_topmenu_hook(); ?>
					</div><!-- #header-menu -->

				</div><!-- #site-header-inside -->

			</div><!--.site-header-top-->

			<?php if ( has_nav_menu( 'primary' ) || ( true == cryout_get_option('theme_pagesmenu') ) ) { ?>
			<nav id="mobile-menu" tabindex="-1">
				<?php cryout_mobilemenu_hook(); ?>
				<?php do_action( 'cryout_side_section_hook' ); ?>
			</nav> <!-- #mobile-menu -->
			<?php } ?>

			<div id="bravada-child-desktop-menu" class="site-header-bottom">

				<div class="site-header-bottom-fixed">

					<div class="site-header-inside">

						<?php if ( bravada_check_empty_menu( 'top' ) && ( has_nav_menu( 'top' ) || ( true == cryout_get_option('theme_pagesmenu') ) ) ) { ?>
						<nav id="access" aria-label="<?php esc_attr_e( 'Top Menu', 'bravada' ) ?>" <?php cryout_schema_microdata( 'menu' ); ?>>
							<?php cryout_access_hook(); ?>
						</nav><!-- #access -->
						<?php } ?>

					</div><!-- #site-header-inside -->

				</div><!-- #site-header-bottom-fixed -->

			</div><!--.site-header-bottom-->

		</div><!-- #site-header-main -->

        <?php

            /*
             * We display the header image only if
             * the current page is not a children category
             */
            $category = is_category() ? get_category( get_query_var( 'cat' )) : null;
            if (
                $category &&
                $category->parent === 0 // If parent is not 0 then this is a children category
            ):
        ?>

		<div id="header-image-main">
			<div id="header-image-main-inside">
				<?php cryout_headerimage_hook(); ?>
			</div><!-- #header-image-main-inside -->
		</div><!-- #header-image-main -->

        <?php
            endif;
        ?>

	</header><!-- #masthead -->

	<?php cryout_absolute_top_hook(); ?>

	<div id="content" class="cryout">
		<?php cryout_main_hook(); ?>
