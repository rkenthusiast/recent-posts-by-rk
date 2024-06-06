<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://rkenthusiast.com
 * @since      1.0.0
 *
 * @package    Recent_Posts_By_Rk
 * @subpackage Recent_Posts_By_Rk/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Recent_Posts_By_Rk
 * @subpackage Recent_Posts_By_Rk/includes
 * @author     Rk Enthusiast <rkenthusiast@gmail.com>
 */
class Recent_Posts_By_Rk_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'recent-posts-by-rk',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
