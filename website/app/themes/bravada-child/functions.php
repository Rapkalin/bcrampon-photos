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
include_once 'functions/layoutFunctions.php'; // layout
include_once 'functions/utilsFunctions.php'; // utils

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