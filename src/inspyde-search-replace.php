<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name:  Search & Replace
 * Plugin URI:   https://wordpress.org/plugins/search-and-replace/
 * Description:  Search & Replace data in your whole WordPress setup, backup and import your database, change table prefix or migrate your domain to another domain.
 * Author:       Inpsyde GmbH
 * Author URI:   http://inpsyde.com
 * Contributors: s-hinse, derpixler
 * Version:      3.0.0
 * Text Domain:  insr
 * Domain Path:  /languages
 * License:      GPLv3+
 * License URI:  license.txt
 */

namespace Inpsyde\SearchReplace;

register_activation_hook( __FILE__, __NAMESPACE__ . '\activate' );

add_action( 'plugins_loaded', __NAMESPACE__ . '\init' );

/**
 * Register textdomain.
 */
function load_textdomain() {

	$lang_dir = plugin_basename( __DIR__ ) . '/languages/';

	load_plugin_textdomain( 'insr', FALSE, $lang_dir );
}

/**
 * Run on plugin activation, checks requirements.
 */
function activate() {

	load_textdomain();

	$correct_php_version = version_compare( phpversion(), '5.4', '>=' );

	if ( ! $correct_php_version ) {
		deactivate_plugins( basename( __FILE__ ) );

		wp_die(
			'<p>' .
			sprintf(
				__( 'This plugin can not be activated because it requires at least PHP version %1$s. ', 'insr' ),
				5.4
			)
			. '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'back', 'insr' ) . '</a>'
		);

	}
}

/**
 * Load and init in WP Environment.
 */
function init() {

	// This sets the capability needed to run the plugin.
	if ( current_user_can( 'manage_options' ) ) {

		load_textdomain();

		// Set up the autoloader.
		require_once( 'inc/Autoloader.php' );

		$autoloader = new inc\Autoloader( __NAMESPACE__, __DIR__ );
		$autoloader->register();

		// Start the plugin.
		$plugin = new inc\Init();
		$plugin->run( __FILE__ );

	}

}