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
add_action( 'init', 'bravada_child_footerbottom_hook', 9 );

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

    wp_register_style( 'bravada-child-style-frontpage', get_stylesheet_directory_uri() . '/styles/frontpage.css' );
    wp_enqueue_style( 'bravada-child-style-frontpage');

}

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

/**
 * Get all regions
 * Return an array of wp_terms for the corresponding regions
 * @return array|object|stdClass[]|null
 */
function bravada_child_get_region_travel_categories()
{
    global $wpdb;

    // Get the parent id corresponding to the category slug: 'bcrampon-travels'
    $parent_category = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}terms WHERE slug = 'bcrampon-travels'");
    $parent_category_term_id = $parent_category[0]->term_id;

    // Get all the region from the parent category Travels (slug: 'bcrampon-travels')
    $media_terms = $wpdb->get_results( "SELECT term_id FROM {$wpdb->prefix}term_taxonomy WHERE taxonomy = 'attachment_category' AND parent = $parent_category_term_id", OBJECT );

    $term_ids = [];
    foreach ($media_terms as $term) {
        $term_ids[] = $term->term_id;
    }

    $sql_term_ids = implode( ',', $term_ids);
    $region_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}terms WHERE term_id IN ($sql_term_ids)");

    return $region_categories;
}

/**
 * Get all countries for a given region
 * Returns an array of wp_terms for the corresponding country
 * @param stdClass $region_category
 * @return array|object|stdClass[]|null
 */
function bravada_child_get_country_travel_categories_from_region(stdClass $region_category)
{
    global $wpdb;

    $children_term_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}term_taxonomy WHERE parent = $region_category->term_id" );

    $children_term_ids = [];
    foreach ($children_term_categories as $children_term_category) {
        $children_term_ids[] = $children_term_category->term_id;
    }
    $sql_children_term_ids = implode( ',', $children_term_ids);
    $country_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}terms WHERE term_id IN ($sql_children_term_ids)" );

    return $country_categories;
}

/**
 * Get all countries for each region
 * Return an array of countries
 * Each country contains an array of wp_terms for the corresponding countries
 * @param array $region_categories
 * @return array
 */
function bravada_child_get_all_country_travel_categories(array $region_categories) : array
{
    // Get all countries for each region
    $country_categories = [];
    foreach ($region_categories as $region_category) {
        $country_categories[$region_category->name] = bravada_child_get_country_travel_categories_from_region($region_category);
    }

    return $country_categories;
}

/**
 * Get all city terms
 * Returns an array of wp_terms for the corresponding cities
 * @param stdClass $country_category
 * @return array|object|stdClass[]|null
 */
function bravada_child_get_city_travel_categories_from_country(stdClass $country_category) : array
{
    global $wpdb;

    $children_term_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}term_taxonomy WHERE parent = $country_category->term_id" );

    $children_term_ids = [];
    foreach ($children_term_categories as $children_term_category) {
        $children_term_ids[] = $children_term_category->term_id;
    }
    $sql_children_term_ids = implode( ',', $children_term_ids);
    $city_categories = $wpdb->get_results( "SELECT * FROM {$wpdb->prefix}terms WHERE term_id IN ($sql_children_term_ids)" );

    return $city_categories;
}

/**
 * Get all cities for a given country
 * Returns an array of sorted countries that contains an array of wp_terms for the corresponding cities
 * @param array $country_categories
 * @return array
 */
function bravada_child_get_all_city_travel_categories_per_region(array $country_categories) : array
{
    // Get all cities for each country
    $country_cities = [];
    foreach ($country_categories as $country_category) {
        $country_cities[$country_category->name] = bravada_child_get_city_travel_categories_from_country($country_category);
    }


    return $country_cities;
}

/**
 * Get all cities and sort them by countries and regions
 * Returns an array of sorted countries per region
 * The array of each country contains an array of wp_terms for the corresponding cities
 * @param array $country_categories
 * @return array
 */
function bravada_child_get_all_sorted_cities(array $country_categories) : array
{
    $sorted_cities = [];
    foreach ($country_categories as $key => $country_category) {
        $sorted_cities[$key] = bravada_child_get_all_city_travel_categories_per_region($country_category);
    }

    return $sorted_cities;
}
