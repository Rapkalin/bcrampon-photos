<?php

/**
 * Custom template tags for this child theme.
 */

/*
 * Include functions
 * Functions files are just a way to split the function.php for a specific page
 */
include_once 'functions/frontPageFunctions.php'; // front page
include_once 'functions/taxonomiesFunctions.php'; // taxonomy pages

// Frontend side
// require_once( get_stylesheet_directory() . "/includes/setup.php" );       	// Setup and init theme
// require_once( get_stylesheet_directory() . "/includes/styles.php" );      	// Register and enqeue css styles and scripts
// require_once( get_stylesheet_directory() . "/includes/loop.php" );        	// Loop functions
// require_once( get_stylesheet_directory() . "/includes/comments.php" );    	// Comment functions
// require_once( get_stylesheet_directory() . "/includes/core.php" );        	// Core functions
// require_once( get_stylesheet_directory() . "/includes/hooks.php" );       	// Hooks
// require_once( get_stylesheet_directory() . "/includes/meta.php" );        	// Custom Post Metas
require_once( get_stylesheet_directory() . "/includes/landing-page.php" );	// Landing Page outputs
// require_once get_stylesheet_directory() . '/options/banner-event.php';
// require get_stylesheet_directory() . '/inc/template-tags-child.php';

/* Bravada child theme setup */
add_action( 'after_setup_theme', 'wpdocs_thwpdocs_bravada_child_theme_setup' );

/* Load styles and scripts */
add_action( 'wp_enqueue_scripts', 'bravada_child_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'bravada_child_register_style', 11 );
add_action( 'admin_enqueue_scripts', 'load_admin_style' );

/* Load internationalisation / translations */
add_action( 'after_setup_theme', 'bravada_child_theme_locale' );

/* Override paretn theme */
add_action( 'cryout_master_footer_hook', 'bravada_child_copyright_hook', 9 );
add_action( 'cryout_master_footerbottom_hook', 'bravada_child_footerbottom_hook', 9 );
add_action( 'cryout_headerimage_hook', 'bravada_child_header_image_hook', 9 );
add_action( 'cryout_headerimage_hook', 'bravada_meta_arrow');
add_action( 'init', 'bravada_child_footerbottom_hook', 9 );

if (!function_exists('wpdocs_thwpdocs_bravada_child_theme_setup')) {
    function wpdocs_thwpdocs_bravada_child_theme_setup() {
        add_image_size( 'bravada-child-grid-thumb', 600, 600, ['center', 'center'] );
        add_image_size( 'bravada-child-grid-single-thumb', 2000, 500, ['center', 'center'] );
        add_image_size( 'bravada-child-grid-double-thumb', 980, 500, ['center', 'center'] );
    }
}

if (!function_exists('bravada_child_enqueue_styles')) {
    /**
     * Add the parent theme style
     *
     * @return void
     */
    function bravada_child_enqueue_styles()
    {
        $parenthandle = 'bravada-style'; // This is 'twentyfifteen-style' for the Twenty Fifteen theme.
        $theme = wp_get_theme();
        wp_enqueue_style( $parenthandle, get_template_directory_uri() . '/style.css',
            [],  // if the parent theme code has a dependency, copy it to here
            $theme->parent()->get('Version')
        );
    }
}

if (!function_exists('bravada_child_register_style')) {
    /**
     * Register and add child theme style
     *
     * @return void
     */
    function bravada_child_register_style()
    {
        wp_register_style( 'bravada-child-style', get_stylesheet_uri());
        wp_enqueue_style( 'bravada-child-style');

        wp_register_style( 'bravada-child-style-frontpage', get_stylesheet_directory_uri() . '/styles/frontpage.css' );
        wp_enqueue_style( 'bravada-child-style-frontpage');

        wp_register_style( 'bravada-child-style-taxonomypage', get_stylesheet_directory_uri() . '/styles/taxonomypage.css');
        wp_enqueue_style( 'bravada-child-style-taxonomypage');

        wp_register_style( 'bravada-child-style-biopage', get_stylesheet_directory_uri() . '/styles/biopage.css');
        wp_enqueue_style( 'bravada-child-style-biopage');
    }
}

if (!function_exists('load_admin_style')) {
    /**
     * Load custom admin css
     *
     * @return void
     */
    function load_admin_style() {
        wp_register_style( 'bravada_child_admin_css', get_stylesheet_directory_uri() . '/styles/admin/admin.css', false, '1.0.0' );
        wp_enqueue_style( 'bravada_child_admin_css', get_stylesheet_directory_uri() . '/styles/admin/admin.css', false, '1.0.0' );
    }
}

if (!function_exists('bravada_child_theme_locale')) {
    /**
     * @return void
     *
     * Load translation files from child and parent themes
     */
    function bravada_child_theme_locale()
    {
        load_child_theme_textdomain( 'bravada', get_stylesheet_directory() . '/languages' );
        load_child_theme_textdomain( 'bcrampon', get_stylesheet_directory() . '/languages' );
    }
}

if (!function_exists('bravada_child_footerbottom_hook')) {
    /**
     * Remove the old footer and add the new one
     * @return void
     */
    function bravada_child_footerbottom_hook()
    {
        # Remove the bravada's theme footer function to overide it
        if (function_exists('bravada_master_footer')) {
            remove_action( 'cryout_master_footerbottom_hook', 'bravada_master_footer' );
        }
    }
}

if (!function_exists('bravada_child_header_image_hook')) {
    function bravada_child_header_image_hook()
    {
        if (function_exists('bravada_header_image')) {
            remove_action ( 'cryout_headerimage_hook', 'bravada_header_image', 99 );
            add_action ( 'cryout_headerimage_hook', 'bravada_child_header_image', 99 );
        }

        if (function_exists('bravada_meta_arrow')) {
            remove_action('cryout_headerimage_hook', 'bravada_meta_arrow');
            add_action ( 'cryout_headerimage_hook', 'bravada_child_meta_arrow' );
        }
    }
}

if (!function_exists('bravada_child_copyright_hook')) {
    function bravada_child_copyright_hook()
    {
        if (function_exists('bravada_copyright')) {
            remove_action ( 'cryout_master_footer_hook', 'bravada_copyright' );
            add_action ( 'cryout_master_footer_hook', 'bravada_child_copyright' );
        }
    }
}

if (! function_exists( 'bravada_child_header_image' )) :
    /**
     *
     */
    function bravada_child_header_image() {

        if ( cryout_on_landingpage() && cryout_get_option('theme_lpslider') != 3) return; // if on landing page and static slider not set to header image, exit.
        $header_image = bravada_header_image_url();$taxonomy = is_tax() ? get_queried_object() : null;
        if ( is_front_page() && function_exists( 'the_custom_header_markup' ) && has_header_video() ) {
            the_custom_header_markup();
        } elseif ($taxonomy) {
            $taxonomy_image = z_taxonomy_image_url($taxonomy->term_id);
            ?>
            <div id="header-overlay"></div>
            <div class="header-image" <?php cryout_echo_bgimage( esc_url( $taxonomy_image ) ) ?>></div>
            <img
                class="header-image"
                alt="<?php if ( is_single() ) the_title_attribute();
                elseif ( is_archive() ) echo esc_attr( get_the_archive_title() );
                else echo esc_attr( get_bloginfo( 'name' ) ) ?>"
                src="<?php echo esc_url( $taxonomy_image ) ?>"
            />
            <?php
        } elseif ( ! empty( $header_image ) ) { ?>
            <div id="header-overlay"></div>
            <div class="header-image" <?php cryout_echo_bgimage( esc_url( $header_image ) ) ?>></div>
            <img
                class="header-image"
                alt="<?php if ( is_single() ) the_title_attribute();
                elseif ( is_archive() ) echo esc_attr( get_the_archive_title() );
                else echo esc_attr( get_bloginfo( 'name' ) ) ?>"
                src="<?php echo esc_url( $header_image ) ?>"
            />
            <?php cryout_header_widget_hook(); ?>
            <?php
        }
    } // bravada_header_image()
endif;

if (!function_exists('bravada_child_meta_arrow')) {
    /**
     * @return void
     */
    function bravada_child_meta_arrow () {
        $achorTarget = is_category() ? "#content" : "#main";
        ?>
        <a href="<?php echo $achorTarget ?>" class="meta-arrow" tabindex="-1">
            <div class="bravada-child-arrow-container">
                <p class="bravada-child-arrow-text">Discover More</p>
                <i class="icon-arrow" title="<?php esc_attr_e( 'Read more', 'bravada' ) ?>"></i>
            </div>
        </a>
        <?php
    }
}

if (!function_exists('bravada_child_copyright')) {
    /**
     * @return void
     */
    function bravada_child_copyright() {

        echo '<div id="site-copyright">' . do_shortcode( cryout_get_option( 'theme_copyright' ) ). '</div>';
        echo '<div style="display:block; margin: 0.5em auto;">' . __( "Site by", "bravada" ) .
            '<a target="_blank" href="' . "https://github.com/Rapkalin/" . '" title="';
        echo 'Site by Freelance developer Rapkalin"> ' . 'Rapkalin</a> based on the Bravada Theme</div>';
        if ( has_nav_menu( 'footer' ) )
            wp_nav_menu( array(
                'container_class'	=> 'footermenu',
                'theme_location'	=> 'footer',
                'after'				=> '<span class="sep">/</span>',
                'depth'				=> 1
            ) );
        ?> </div> <?php

    }
}

if (!function_exists('bravada_child_get_image_sizes_list')) {
    function bravada_child_get_image_sizes_list ()
    {
        global $_wp_additional_image_sizes;
        return $_wp_additional_image_sizes;
    }
}