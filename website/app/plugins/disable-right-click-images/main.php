<?php
/*
Plugin Name: Disable Right Click Images
Version: 1.0.1
Plugin URI: https://noorsplugin.com/disable-right-click-images-wordpress/
Author: naa986
Author URI: https://noorsplugin.com/
Description: Disable Right Click on Images in WordPress
*/

if(!defined('ABSPATH')) exit;
if(!class_exists('DISABLE_RIGHT_CLICK_IMAGES'))
{
    class DISABLE_RIGHT_CLICK_IMAGES
    {
        var $plugin_version = '1.0.1';
        var $plugin_url;
        var $plugin_path;
        function __construct()
        {
            define('DISABLE_RIGHT_CLICK_IMAGES_VERSION', $this->plugin_version);
            define('DISABLE_RIGHT_CLICK_IMAGES_SITE_URL',site_url());
            define('DISABLE_RIGHT_CLICK_IMAGES_URL', $this->plugin_url());
            define('DISABLE_RIGHT_CLICK_IMAGES_PATH', $this->plugin_path());
            add_action('wp_enqueue_scripts', array($this, 'plugin_scripts'), 0);
        }
        function plugin_scripts()
        {
            if (!is_admin()) 
            {
                wp_enqueue_script('jquery');
                wp_register_script('disablerightclickimages', DISABLE_RIGHT_CLICK_IMAGES_URL.'/script.js', array('jquery'), DISABLE_RIGHT_CLICK_IMAGES_VERSION);
                wp_enqueue_script('disablerightclickimages');
            }
        }
        function plugin_url()
        {
            if($this->plugin_url) return $this->plugin_url;
            return $this->plugin_url = plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
        }
        function plugin_path(){ 	
            if ( $this->plugin_path ) return $this->plugin_path;		
            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }       
    }
    $GLOBALS['disable_right_click_images'] = new DISABLE_RIGHT_CLICK_IMAGES();
}
