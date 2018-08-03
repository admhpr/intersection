<?php
/*
Plugin Name: Intersection
Description: A suite of tools to use with the `Sectional` theme 
Author: Adam Harpur
Author URI: https://adamharpur.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

register_activation_hook( __FILE__, 'activate_intersection' );
function activate_intersection() {
	$admin_url = admin_url().'/plugin-install.php?tab=plugin-information&plugin=intersection';
	if ( ! class_exists('ACF')  ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( __( 'This plugin REQUIRES Advanced Custom Fields v5< to be installed and activated' ), '<em>', '</em>', '<a href="'.$admin_url.'" target="_blank">', '</a>', '<a href="javascript:history.back()">' ) );
	}
}

require_once 'lib/autoload.php';


?>