<?php

/* LAYOUT */

/* Override parent theme */
add_action( 'cryout_headerimage_hook', 'bravada_child_header_image_hook' );
add_action( 'cryout_master_footer_hook', 'bravada_child_copyright_hook', 9 );
add_action( 'cryout_master_footerbottom_hook', 'bravada_child_footerbottom_hook', 9 );
add_action( 'init', 'bravada_child_footerbottom_hook', 9 );
add_action( 'wp_body_open', 'bravada_child_cryout_skiplink_hook', 1 );

if (!function_exists('bravada_child_copyright_hook')) {
    function bravada_child_copyright_hook()
    {
        if (function_exists('bravada_copyright')) {
            remove_action ( 'cryout_master_footer_hook', 'bravada_copyright' );
            add_action ( 'cryout_master_footer_hook', 'bravada_child_copyright' );
        }
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
            remove_action('cryout_headerimage_hook', 'bravada_meta_arrow', 1);
            add_action ( 'cryout_headerimage_hook', 'bravada_child_meta_arrow', 1 );
        }
    }
}

if (!function_exists('bravada_child_meta_arrow')) {
    /**
     * @return void
     */
    function bravada_child_meta_arrow () {
        $achorTarget = cryout_is_landingpage() ? "#footer-top" : (is_category() ? "#content" : "#main");
        ?>
        <a href="<?php echo $achorTarget ?>" class="meta-arrow" tabindex="-1">
            <div class="bravada-child-arrow-container">
                <!-- <p class="bravada-child-arrow-text">Discover More</p> -->
                <i class="icon-arrow" title="<?php esc_attr_e( 'Read more', 'bravada' ) ?>"></i>
            </div>
        </a>
        <?php
    }
}

if (! function_exists( 'bravada_child_header_image' )) {
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
}

if (!function_exists('bravada_child_copyright')) {
    /**
     * @return void
     */
    function bravada_child_copyright() {

        echo '<div id="site-copyright">' . do_shortcode( cryout_get_option( 'theme_copyright' ) ). '</div>';
        echo '<div style="display:block; margin: 0.5em 1.5em;">' . __( "Site by", "bravada" ) .
            '<a target="_blank" href="' . "https://github.com/Rapkalin/" . '" title="';
        echo 'Site by Freelance developer Rapkalin"> ' . 'Rapkalin</a> based on the Bravada Theme</div>';
        if ( has_nav_menu( 'footer' ) )
            wp_nav_menu( array(
                'container_class'	=> 'footermenu',
                'theme_location'	=> 'footer',
                'after'				=> '<span class="sep">/</span>',
                'depth'				=> 1
            ) );
        dynamic_sidebar('footer-widget-area');

        ?> </div>
        <?php

    }
}

if (!function_exists('bravada_child_get_image_sizes_list')) {
    function bravada_child_get_image_sizes_list ()
    {
        global $_wp_additional_image_sizes;
        return $_wp_additional_image_sizes;
    }
}

if (!function_exists('bravada_child_cryout_skiplink')) {
    function bravada_child_cryout_skiplink_hook () {
        if (function_exists('cryout_skiplink')) {
            remove_action ( 'wp_body_open', 'cryout_skiplink', 2 );
            add_action ( 'wp_body_open', 'bravada_child_cryout_skiplink', 2 );
        }
    }
}

if (!function_exists('bravada_child_cryout_skiplink')) {
    function bravada_child_cryout_skiplink () {
        $achorTarget = cryout_is_landingpage() ? "#footer-top" : (is_category() ? "#content" : "#main");
        ?>
            <a id="skip" class="skip-link screen-reader-text test" href="<?php echo $achorTarget ?>" title="<?php esc_attr_e( 'Skip to content', 'cryout' ); ?>"> <?php _e( 'Skip to content', 'cryout' ); ?> </a>
        <?php
    }
}

// cryout_skiplink() located in cryout/prototypes.php
