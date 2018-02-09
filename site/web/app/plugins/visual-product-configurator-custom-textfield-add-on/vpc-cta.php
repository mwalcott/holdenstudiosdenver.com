<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.woocommerceproductconfigurator.com
 * @since             1.0.0
 * @package           Vpc_Cta
 *
 * @wordpress-plugin
 * Plugin Name:       Visual Product Configurator Custom Text Add On
 * Plugin URI:        https://www.woocommerceproductconfigurator.com/demo/custom-text-configuration/
 * Description:       this addon allows you to add text to the preview and also allows you to change the color and the font
 * Version:           1.0.0
 * Author:            Orion
 * Author URI:        https://www.woocommerceproductconfigurator.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       vpc-cta
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

//define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-vpc-cta-activator.php
 */
function activate_vpc_cta() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vpc-cta-activator.php';
	Vpc_Cta_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-vpc-cta-deactivator.php
 */
function deactivate_vpc_cta() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-vpc-cta-deactivator.php';
	Vpc_Cta_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_vpc_cta' );
register_deactivation_hook( __FILE__, 'deactivate_vpc_cta' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-vpc-cta.php';
require plugin_dir_path( __FILE__ ) . 'includes/functions.php';
define( 'VPC_CTA_URL', plugins_url('/', __FILE__) );
define( 'VPC_CTA_DIR', dirname(__FILE__) );
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_vpc_cta() {

	$plugin = new Vpc_Cta();
	$plugin->run();

}
run_vpc_cta();
