<?php

/* COMMENTS */

/* Remove comments and trackbacks support on every post type */
add_action( 'init', 'bravada_child_disable_comments_support' );

/* Unregister the recent comments widget */
add_action( 'widgets_init', 'bravada_child_disable_comments_widget' );

/* Block the admin screens dedicated to comments */
add_action( 'admin_init', 'bravada_child_disable_comments_admin_screens' );
add_action( 'admin_menu', 'bravada_child_disable_comments_admin_menu' );
add_action( 'admin_bar_menu', 'bravada_child_disable_comments_admin_bar', 999 );

/* Close comments and pings everywhere, admin-ajax and XML-RPC entry points included */
add_filter( 'comments_open', '__return_false', 20 );
add_filter( 'pings_open', '__return_false', 20 );

/* Hide the comments already stored in database from the frontend */
if ( ! is_admin() ) {
    add_filter( 'comments_array', '__return_empty_array', 20 );
    add_filter( 'get_comments_number', '__return_zero', 20 );
}

/* Never send a comment notification email, whatever the discussion options hold */
add_filter( 'notify_post_author', '__return_false', 20 );
add_filter( 'notify_moderator', '__return_false', 20 );

/* Remove the comments feed link from the head */
add_filter( 'feed_links_show_comments_feed', '__return_false' );

/* Remove the pingback endpoint advertised to other sites */
add_filter( 'xmlrpc_methods', 'bravada_child_disable_pingback_methods' );
add_filter( 'wp_headers', 'bravada_child_disable_pingback_header' );

if (!function_exists('bravada_child_disable_comments_support')) {
    /**
     * Drop the comments and trackbacks support of every registered post type
     *
     * @return void
     */
    function bravada_child_disable_comments_support()
    {
        foreach ( get_post_types() as $post_type ) {
            if ( post_type_supports( $post_type, 'comments' ) ) {
                remove_post_type_support( $post_type, 'comments' );
                remove_post_type_support( $post_type, 'trackbacks' );
            }
        }
    }
}

if (!function_exists('bravada_child_disable_comments_widget')) {
    /**
     * Remove the recent comments widget from the available widgets
     *
     * @return void
     */
    function bravada_child_disable_comments_widget()
    {
        unregister_widget( 'WP_Widget_Recent_Comments' );
    }
}

if (!function_exists('bravada_child_disable_comments_admin_screens')) {
    /**
     * Redirect the comments and discussion admin screens to the dashboard
     *
     * @return void
     */
    function bravada_child_disable_comments_admin_screens()
    {
        global $pagenow;

        if ( in_array( $pagenow, ['edit-comments.php', 'options-discussion.php'], true ) ) {
            wp_safe_redirect( admin_url() );
            exit;
        }
    }
}

if (!function_exists('bravada_child_disable_comments_admin_menu')) {
    /**
     * Remove the comments and discussion entries from the admin menus
     *
     * @return void
     */
    function bravada_child_disable_comments_admin_menu()
    {
        remove_menu_page( 'edit-comments.php' );
        remove_submenu_page( 'options-general.php', 'options-discussion.php' );
    }
}

if (!function_exists('bravada_child_disable_comments_admin_bar')) {
    /**
     * Remove the comments bubble from the admin bar
     *
     * @param WP_Admin_Bar $wp_admin_bar
     *
     * @return void
     */
    function bravada_child_disable_comments_admin_bar( $wp_admin_bar )
    {
        $wp_admin_bar->remove_node( 'comments' );
    }
}

if (!function_exists('bravada_child_disable_pingback_methods')) {
    /**
     * Remove the pingback methods from the XML-RPC API, keeping the other ones untouched
     *
     * @param array $methods
     *
     * @return array
     */
    function bravada_child_disable_pingback_methods( $methods )
    {
        unset( $methods['pingback.ping'], $methods['pingback.extensions.getPingbacks'] );

        return $methods;
    }
}

if (!function_exists('bravada_child_disable_pingback_header')) {
    /**
     * Remove the X-Pingback header
     *
     * @param array $headers
     *
     * @return array
     */
    function bravada_child_disable_pingback_header( $headers )
    {
        unset( $headers['X-Pingback'] );

        return $headers;
    }
}
