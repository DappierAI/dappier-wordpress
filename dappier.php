<?php

/**
 * Plugin Name:     Dappier for WordPress
 * Plugin URI:      https://dappier.com/
 * Description:     Integrate Dappier AI on your WordPress site.
 * Version:         0.5.3
 *
 * Author:          Dappier
 * Author URI:      https://dappier.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Must be at the top of the file.
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Main Dappier_Plugin Class.
 *
 * @since 0.1.0
 */
final class Dappier_Plugin {

	/**
	 * @var   Dappier_Plugin The one true Dappier_Plugin
	 * @since 0.1.0
	 */
	private static $instance;

	/**
	 * Main Dappier_Plugin Instance.
	 *
	 * Insures that only one instance of Dappier_Plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   0.1.0
	 * @static  var array $instance
	 * @uses    Dappier_Plugin::setup_constants() Setup the constants needed.
	 * @uses    Dappier_Plugin::includes() Include the required files.
	 * @uses    Dappier_Plugin::hooks() Activate, deactivate, etc.
	 * @see     Dappier_Plugin()
	 * @return  object | Dappier_Plugin The one true Dappier_Plugin
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup.
			self::$instance = new Dappier_Plugin;
			// Methods.
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'textdomain' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'textdomain' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function setup_constants() {
		// Plugin version.
		if ( ! defined( 'DAPPIER_PLUGIN_VERSION' ) ) {
			define( 'DAPPIER_PLUGIN_VERSION', '0.5.3' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'DAPPIER_PLUGIN_DIR' ) ) {
			define( 'DAPPIER_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'DAPPIER_PLUGIN_URL' ) ) {
			define( 'DAPPIER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
	}

	/**
	 * Include required files.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function includes() {
		// Include vendor libraries.
		require_once __DIR__ . '/vendor/autoload.php';

		// Includes.
		foreach ( glob( plugin_dir_path( __FILE__ ) . 'classes/*.php' ) as $file ) { include $file; }
		foreach ( glob( plugin_dir_path( __FILE__ ) . 'includes/*.php' ) as $file ) { include $file; }

		// Instantiate classes.
		$settings = new Dappier_Settings;
		$endpoint = new Dappier_Endpoints;
	}

	/**
	 * Run the hooks.
	 *
	 * @since   0.1.0
	 * @return  void
	 */
	public function hooks() {
		add_action( 'plugins_loaded',   [ $this, 'updater' ] );
		add_action( 'activated_plugin', [ $this, 'redirect' ] );

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
	}

	/**
	 * Setup the updater.
	 *
	 * composer require yahnis-elsts/plugin-update-checker
	 *
	 * @since 0.1.0
	 *
	 * @uses https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @return void
	 */
	public function updater() {
		// Bail if plugin updater is not loaded.
		if ( ! class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
			return;
		}

		// // Setup the updater.
		// $updater = PucFactory::buildUpdateChecker( 'https://github.com/maithemewp/plugin-slug/', __FILE__, 'mai-user-post' );

		// // Set the branch that contains the stable release.
		// $updater->setBranch( 'main' );

		// // Maybe set github api token.
		// if ( defined( 'MAI_GITHUB_API_TOKEN' ) ) {
		// 	$updater->setAuthentication( MAI_GITHUB_API_TOKEN );
		// }

		// // Add icons for Dashboard > Updates screen.
		// if ( function_exists( 'mai_get_updater_icons' ) && $icons = mai_get_updater_icons() ) {
		// 	$updater->addResultFilter(
		// 		function ( $info ) use ( $icons ) {
		// 			$info->icons = $icons;
		// 			return $info;
		// 		}
		// 	);
		// }
	}

	/**
	 * Plugin activation.
	 *
	 * @return  void
	 */
	public function activate() {
		// Bail if the redirect has already been run.
		if ( ! is_null( dappier_get_option( 'activation_redirect' ) ) ) {
			return;
		}

		// Set onboarding option.
		dappier_update_option( 'activation_redirect', true );
	}

	/**
	 * Redirect to settings page on activation.
	 *
	 * @param string $plugin The plugin basename.
	 *
	 * @return void
	 */
	public function redirect( $plugin ) {
		// Bail if the activation is not for this plugin.
		if ( plugin_basename( __FILE__ ) !== $plugin ) {
			return;
		}

		// Bail if the redirect has already been run.
		if ( ! dappier_get_option( 'activation_redirect' ) ) {
			return;
		}

		// Set onboarding option.
		dappier_update_option( 'activation_redirect', false );

		exit( wp_safe_redirect( esc_url( admin_url( 'options-general.php?page=dappier' ) ) ) );
	}
}

/**
 * The main function for that returns Dappier_Plugin instance.
 *
 * @since 0.1.0
 *
 * @return object|Dappier_Plugin The one true Dappier_Plugin Instance.
 */
function dappier_plugin() {
	return Dappier_Plugin::instance();
}

// Get Dappier_Plugin Running.
dappier_plugin();
