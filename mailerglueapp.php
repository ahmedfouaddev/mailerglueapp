<?php
/**
 * Plugin Name: Mailer Glue (App)
 * Plugin URI: https://app.mailerglue.com/
 * Description: The Mailer Glue App.
 * Author: Mailer Glue
 * Author URI: https://app.mailerglue.com
 * Requires at least: 6.0
 * Requires PHP: 7.0
 * Version: 1.0.0
 * Text Domain: mailerglueapp
 * Domain Path: /i18n/languages/
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'MailerGlueApp' ) ) {

/**
 * Main Class.
 */
final class MailerGlueApp {

	/**
	 * @var Instance.
	 */
	private static $instance;

	/**
	 * @var $api_version.
	 */
	public $api_version = 'v1';

	/**
	 * @var Version.
	 */
	public $version = '1.0.0';
  
	/**
	 * Main Instance.
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof MailerGlueApp ) ) {
			self::$instance = new MailerGlueApp;
			self::$instance->setup_constants();

			add_action( 'plugins_loaded', array( self::$instance, 'load_text_domain' ) );

			self::$instance->autoload();
			self::$instance->includes();
		}

		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mailerglueapp' ), $this->version );
	}

	/**
	 * Disable unserializing of the class.
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mailerglueapp' ), $this->version );
	}

	/**
	 * Setup plugin constants.
	 */
	private function setup_constants() {

		// Plugin version.
		if ( ! defined( 'MAILERGLUEAPP_VERSION' ) ) {
			define( 'MAILERGLUEAPP_VERSION', $this->version );
		}

		// API version.
		if ( ! defined( 'MAILERGLUEAPP_API_VERSION' ) ) {
			define( 'MAILERGLUEAPP_API_VERSION', $this->api_version );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAILERGLUEAPP_PLUGIN_DIR' ) ) {
			define( 'MAILERGLUEAPP_PLUGIN_DIR', plugin_dir_path(__FILE__) );
		}

		// Plugin Folder URL.
		if ( ! defined( 'MAILERGLUEAPP_PLUGIN_URL' ) ) {
			define( 'MAILERGLUEAPP_PLUGIN_URL', plugin_dir_url(__FILE__) );
		}

		// Plugin Root File.
		if ( ! defined( 'MAILERGLUEAPP_PLUGIN_FILE' ) ) {
			define( 'MAILERGLUEAPP_PLUGIN_FILE', __FILE__ );
		}

	}

	/**
	 * Autoload.
	 */
	private function autoload() {
		spl_autoload_register( array( $this, 'autoloader' ) );
	}

	/**
	 * Includes.
	 */
	public function includes() {

		$this->install	= new MailerGlueApp\Install();
		$this->api		= new MailerGlueApp\API();
		$this->types	= new MailerGlueApp\Types();

		if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
			$this->admin_menus 		= new MailerGlueApp\Admin\Menus;
			$this->admin_scripts 	= new MailerGlueApp\Admin\Scripts;
		}

	}

	/**
	 * @param $class
	 */
	public function autoloader( $classname ) {

		$classname = ltrim( $classname, '\\' );
		$classname = str_replace( __NAMESPACE__, '', $classname );
		$classname = str_replace( '\\', '/', $classname );
		$classname = str_replace( 'MailerGlueApp/', '', $classname );
		$classname = str_replace( '_', '-', $classname );

		$path = MAILERGLUEAPP_PLUGIN_DIR . 'includes/classes/' . strtolower( $classname ) . '.php';
		if ( file_exists( $path ) ) {
			include_once $path;
		}
	}

	/**
	 * Loads the plugin language files.
	 */
	public function load_text_domain() {
		global $wp_version;

		// Set filter for plugin's languages directory.
		$languages_dir	= dirname( plugin_basename( MAILERGLUEAPP_PLUGIN_FILE ) ) . '/i18n/languages/';
		$languages_dir	= apply_filters( 'mailerglueapp_languages_directory', $languages_dir );

		// Traditional WordPress plugin locale filter.

		$get_locale = get_locale();

		if ($wp_version >= 4.7) {

			$get_locale = get_user_locale();
		}

		unload_textdomain( 'mailerglueapp' );

		/**
		 * Defines the plugin language locale used.
		 *
		 * @var $get_locale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
		 *					otherwise uses `get_locale()`.
		 */
		$locale		   = apply_filters( 'plugin_locale', $get_locale, 'mailerglueapp' );
		$mofile		   = sprintf( '%1$s-%2$s.mo', 'mailerglueapp', $locale );

		// Look for wp-content/languages/mailerglueapp/mailerglueapp-{lang}_{country}.mo
		$mofileglobal1 = WP_LANG_DIR . '/mailerglueapp/mailerglueapp-' . $locale . '.mo';

		// Look in wp-content/languages/plugins/mailerglueapp
		$mofileglobal2 = WP_LANG_DIR . '/plugins/mailerglueapp/' . $mofile;

		if ( file_exists( $mofileglobal1 ) ) {

			load_textdomain( 'mailerglueapp', $mofileglobal1 );

		} elseif ( file_exists( $mofileglobal2 ) ) {

			load_textdomain( 'mailerglueapp', $mofileglobal2 );

		} else {

			// Load the default language files.
			load_plugin_textdomain( 'mailerglueapp', false, $languages_dir );
		}

	}

}

} // End if class_exists check.

/**
 * The main function.
 */
function mailerglueapp() {
	return MailerGlueApp::instance();
}

// Get Running.
mailerglueapp();
