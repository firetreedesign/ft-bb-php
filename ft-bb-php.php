<?php
/**
 * Plugin Name: PHP Module for Beaver Builder
 * Plugin URI: https://firetreedesign.com/
 * Description: Add PHP code directly to a page using Beaver Builder
 * Version: 1.0.1
 * Author: FireTree Design, LLC
 * Author URI: http://firetreedesign.com
 * GitHub Plugin URI: https://github.com/danielmilner/ft-bb-php
 *
 * @package FT_BB_PHP
 */

define( 'FT_BB_PHP_DIR', plugin_dir_path( __FILE__ ) );
define( 'FT_BB_PHP_URL', plugins_url( '/', __FILE__ ) );

/**
 * Load the module
 */
function ft_bb_php_load_module() {
	if ( class_exists( 'FLBuilder' ) ) {
	    require_once 'ft-bb-php/ft-bb-php.php';
	}
}
add_action( 'init', 'ft_bb_php_load_module' );
