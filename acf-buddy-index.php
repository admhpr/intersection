<?php
/*
Plugin Name: Advanced Custom Fields Buddy
Description: Quick render nested flexible content and repeaters 
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

register_activation_hook( __FILE__, 'activate_acf_buddy' );
function activate_acf_buddy() {
	$admin_url = admin_url().'/plugin-install.php?tab=plugin-information&plugin=acf-buddy';
	if ( ! class_exists('ACF')  ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( sprintf( __( 'This plugin REQUIRES Advanced Custom Fields v5< to be installed and activated' ), '<em>', '</em>', '<a href="'.$admin_url.'" target="_blank">', '</a>', '<a href="javascript:history.back()">' ) );
	}
}

include( plugin_dir_path( __FILE__ ) . 'inc/acf-buddy.php');

?>