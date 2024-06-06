<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://rkenthusiast.com
 * @since             1.0.0
 * @package           Recent_Posts_By_Rk
 *
 * @wordpress-plugin
 * Plugin Name:       Recent Posts By RK
 * Plugin URI:        https://rkenthusiast.com
 * Description:       Recent Posts by RK is a WordPress plugin that allows you to display recent posts in a customizable widget. You can select categories, tags, and the number of posts to display.
 * Version:           1.0.0
 * Author:            Rk Enthusiast
 * Author URI:        https://rkenthusiast.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       recent-posts-by-rk
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'RECENT_POSTS_BY_RK_VERSION', '1.0.0' );

// Define plugin path
if ( ! defined( 'RECENT_POSTS_PLUGIN_DIR_URL' ) ) {
    define( 'RECENT_POSTS_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-recent-posts-by-rk-activator.php
 */
function activate_recent_posts_by_rk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-recent-posts-by-rk-activator.php';
	Recent_Posts_By_Rk_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-recent-posts-by-rk-deactivator.php
 */
function deactivate_recent_posts_by_rk() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-recent-posts-by-rk-deactivator.php';
	Recent_Posts_By_Rk_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_recent_posts_by_rk' );
register_deactivation_hook( __FILE__, 'deactivate_recent_posts_by_rk' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-recent-posts-by-rk.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_recent_posts_by_rk() {

	$plugin = new Recent_Posts_By_Rk();
	$plugin->run();

}
run_recent_posts_by_rk();

// Include the widget class
include_once(plugin_dir_path(__FILE__) . 'includes/class-recent-posts-widget.php');

// Register the widget
function register_recent_posts_widget() {
    register_widget('Recent_Posts_Widget');
}
add_action('widgets_init', 'register_recent_posts_widget');