<?php

/* UTILS */

/* Bravada child theme setup */
add_action( 'after_setup_theme', 'wpdocs_thwpdocs_bravada_child_theme_setup' );

/* Load styles and scripts */
add_action( 'wp_enqueue_scripts', 'bravada_child_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'bravada_child_register_style', 11 );
add_action( 'admin_enqueue_scripts', 'load_admin_style' );

/* Load internationalisation / translations */
add_action( 'after_setup_theme', 'bravada_child_theme_locale' );

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

        wp_register_style( 'bravada-child-style-footer', get_stylesheet_directory_uri() . '/styles/footer.css');
        wp_enqueue_style( 'bravada-child-style-footer');

        wp_register_style( 'bravada-child-style-layout', get_stylesheet_directory_uri() . '/styles/layout.css');
        wp_enqueue_style( 'bravada-child-style-layout');

        wp_register_style( 'bravada-child-style-404', get_stylesheet_directory_uri() . '/styles/404.css');
        wp_enqueue_style( 'bravada-child-style-404');
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