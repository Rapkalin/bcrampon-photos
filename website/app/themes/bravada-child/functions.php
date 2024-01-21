<?php

/**
 * Custom template tags for this child theme.
 */

// require get_stylesheet_directory() . '/inc/template-tags-child.php';
// require_once get_stylesheet_directory() . '/options/banner-event.php';

/* Load styles and scripts */
add_action( 'wp_enqueue_scripts', 'bravada_child_enqueue_styles' );
add_action( 'wp_enqueue_scripts', 'bravada_child_register_style', 11 );

/* Load internationalisation / translations */
add_action( 'after_setup_theme', 'bravada_child_theme_locale' );

add_action( 'cryout_master_footerbottom_hook', 'bravada_child_footerbottom_hook', 9 );

// Frontend side
// require_once( get_stylesheet_directory() . "/includes/setup.php" );       	// Setup and init theme
// require_once( get_stylesheet_directory() . "/includes/styles.php" );      	// Register and enqeue css styles and scripts
// require_once( get_stylesheet_directory() . "/includes/loop.php" );        	// Loop functions
// require_once( get_stylesheet_directory() . "/includes/comments.php" );    	// Comment functions
// require_once( get_stylesheet_directory() . "/includes/core.php" );        	// Core functions
// require_once( get_stylesheet_directory() . "/includes/hooks.php" );       	// Hooks
// require_once( get_stylesheet_directory() . "/includes/meta.php" );        	// Custom Post Metas
require_once( get_stylesheet_directory() . "/includes/landing-page.php" );	// Landing Page outputs

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

/**
 * Register and add child theme style
 *
 * @return void
 */
function bravada_child_register_style()
{
    wp_register_style( 'bravada-child-style', get_stylesheet_uri());
    wp_enqueue_style( 'bravada-child-style');
}

/**
 * @return void
 *
 * Load translation files from child and parent themes
 */
function bravada_child_theme_locale(): void
{
    load_child_theme_textdomain( 'bravada', get_stylesheet_directory() . '/languages' );
    load_child_theme_textdomain( 'bcrampon', get_stylesheet_directory() . '/languages' );
}

/**
 * Remove the old footer and add the new one
 * @return void
 */
function bravada_child_footerbottom_hook()
{
    # Remove the bravada's theme footer function to overide it
    if (function_exists('bravada_master_footer')) {
        remove_action( 'cryout_master_footerbottom_hook', 'bravada_master_footer' );
        add_action('cryout_master_footerbottom_hook', 'bravada_child_master_footer', 11);
    }
}

/**
 * Output the new footer
 * @return void
 */
function bravada_child_master_footer()
{
    do_action( 'cryout_footer_hook' );
    echo '<div style="display:block; margin: 0.5em auto;">' . __( "Site by", "bravada" ) .
        '<a target="_blank" href="' . "https://github.com/Rapkalin/" . '" title="';
    echo 'Site by Freelance developer Rapkalin"> ' . 'Rapkalin</a> based on the Bravada Theme';
}

