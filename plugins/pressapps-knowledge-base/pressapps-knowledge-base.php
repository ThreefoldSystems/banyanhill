<?php

/**
 * Plugin Name:       PressApps Knowledge Base
 * Description:       Add knowledge base to your existing site in minutes that will decrease customer queries
 * Version:           3.2.0
 * Author:            PressApps
 * Author URI:        https://codecanyon.net/user/pressapps
 * Text Domain:       pressapps-knowledge-base
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

 /**
 * Skelet Config
 */
$skelet_paths[] = array(
    'prefix'      => 'pakb',
    'dir'         => wp_normalize_path(  plugin_dir_path( __FILE__ ).'includes/' ),
    'uri'         => plugin_dir_url( __FILE__ ).'includes/skelet',
);

/**
 * Load Skelet Framework
 */
if( ! class_exists( 'Skelet_LoadConfig' ) ){
        include_once dirname( __FILE__ ) .'/includes/skelet/skelet.php';
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pressapps-knowledge-base-activator.php
 */
function activate_pressapps_knowledge_base() {

	// Check PHP Version and deactivate & die if it doesn't meet minimum requirements.
	if ( version_compare( PHP_VERSION, '5.3.0', '<' )  ) {
		deactivate_plugins( plugin_basename( dirname( __FILE__ )  ) );
		wp_die( __( 'The minimum PHP version required for this plugin is 5.3.0 Please upgrade the PHP version or contact your hosting provider to do it for you.', 'pressapps-knowledge-base' ) );
	}

	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pressapps-knowledge-base-activator.php';
	Pressapps_Knowledge_Base_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pressapps-knowledge-base-deactivator.php
 */
function deactivate_pressapps_knowledge_base() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pressapps-knowledge-base-deactivator.php';
	Pressapps_Knowledge_Base_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pressapps_knowledge_base' );
register_deactivation_hook( __FILE__, 'deactivate_pressapps_knowledge_base' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pressapps-knowledge-base.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pressapps_knowledge_base() {

	$plugin = new Pressapps_Knowledge_Base();
	$plugin->run();

}
run_pressapps_knowledge_base();

/**
 * Global Variables
 */
if ( class_exists( 'Skelet' ) && ! isset( $pakb ) ) {
	$pakb        = new Skelet( 'pakb' );
}

if ( class_exists( 'PAKB_Loop' ) && ! isset( $pakb_loop ) ) {
	$pakb_loop   = new PAKB_Loop();
}

if ( class_exists( 'PAKB_Helper' ) && ! isset( $pakb_helper ) ) {
	$pakb_helper = new PAKB_Helper();
}
