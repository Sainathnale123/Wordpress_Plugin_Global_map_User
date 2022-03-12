<?php
/**
 * Plugin Name:  Global Partners Map
 * Plugin URI: 
 * Description: Global Partners Map
 * Version: 0.1
 * Author: Sainath Mahadev Nale
 * Author URI: 
 * Text Domain:  Global Partners Map
 * License: MIT
 *
 * @since 0.1
 *
 * @package  globalPartnersmap
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

define( 'Globalpartnersmap_PLUGIN_FILE', __FILE__ );

/**
 * Loads the action plugin
 */
require_once dirname( Globalpartnersmap_PLUGIN_FILE ) . '/includes/Globalpartnersmap_Main.php';

Globalpartnersmap_Main::instance();

register_activation_hook( Globalpartnersmap_PLUGIN_FILE, array( 'Globalpartnersmap_Main', 'activate' ) );

register_deactivation_hook( Globalpartnersmap_PLUGIN_FILE, array( 'Globalpartnersmap_Main', 'deactivate' ) );

register_uninstall_hook( Globalpartnersmap_PLUGIN_FILE, array( 'Globalpartnersmap_Main', 'uninstall' ) ); 
