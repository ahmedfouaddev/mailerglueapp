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
 * Text Domain: mgapp
 * Domain Path: /i18n/languages/
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('MGApp')) {

/**
 * Main Class.
 */
final class MGApp
{

    /**
     * @var Instance.
     */
    private static $instance;

    /**
     * @var Version.
     */
    public $version = '1.0.0';
  
    /**
     * Main Instance.
     */
    public static function instance()
    {
        if (!isset(self::$instance) && !(self::$instance instanceof MGApp)) {
            self::$instance = new MGApp;
            self::$instance->setupConstants();

            add_action('plugins_loaded', array(self::$instance, 'loadTextdomain'));

            self::$instance->includes();
        }

        return self::$instance;
    }

    /**
     * Throw error on object clone.
     */
    public function __clone()
    {
        // Cloning instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'mgapp'), $this->version);
    }

    /**
     * Disable unserializing of the class.
     */
    public function __wakeup()
    {
        // Unserializing instances of the class is forbidden.
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'mgapp'), $this->version);
    }

    /**
     * Setup plugin constants.
     */
    private function setupConstants()
    {

        // Plugin version.
        if (!defined('MGAPP_VERSION')) {
            define('MGAPP_VERSION', $this->version);
        }

        // Plugin version.
        if (!defined('MGAPP_API_VERSION')) {
            define('MGAPP_API_VERSION', '1.0');
        }

        // Plugin Folder Path.
        if (!defined('MGAPP_PLUGIN_DIR')) {
            define('MGAPP_PLUGIN_DIR', plugin_dir_path(__FILE__));
        }

        // Plugin Folder URL.
        if (!defined('MGAPP_PLUGIN_URL')) {
            define('MGAPP_PLUGIN_URL', plugin_dir_url(__FILE__));
        }

        // Plugin Root File.
        if (!defined('MGAPP_PLUGIN_FILE')) {
            define('MGAPP_PLUGIN_FILE', __FILE__);
        }

    }

    /**
     * Include required files.
     */
    private function includes()
    {

        if (is_admin() || (defined('WP_CLI') && WP_CLI)) {
            $this->loadTextdomain();
        }

    }

    /**
     * Loads the plugin language files.
     */
    public function loadTextdomain()
    {
        global $wp_version;

        // Set filter for plugin's languages directory.
        $mgappLangDir  = dirname(plugin_basename(MGAPP_PLUGIN_FILE)) . '/i18n/languages/';
        $mgappLangDir  = apply_filters('mgapp_languages_directory', $mgappLangDir);

        // Traditional WordPress plugin locale filter.

        $getLocale = get_locale();

        if ($wp_version >= 4.7) {

            $getLocale = get_user_locale();
        }

        unload_textdomain('mgapp');

        /**
         * Defines the plugin language locale used.
         *
         * @var $getLocale The locale to use. Uses get_user_locale()` in WordPress 4.7 or greater,
         *                  otherwise uses `get_locale()`.
         */
        $locale        = apply_filters('plugin_locale', $getLocale, 'mgapp');
        $mofile        = sprintf('%1$s-%2$s.mo', 'mgapp', $locale);

        // Look for wp-content/languages/mgapp/mgapp-{lang}_{country}.mo
        $mofileGlobal1 = WP_LANG_DIR . '/mgapp/mgapp-' . $locale . '.mo';

        // Look in wp-content/languages/plugins/mgapp
        $mofileGlobal2 = WP_LANG_DIR . '/plugins/mgapp/' . $mofile;

        if (file_exists($mofileGlobal1)) {

            load_textdomain('mgapp', $mofileGlobal1);

        } elseif (file_exists($mofileGlobal2)) {

            load_textdomain('mgapp', $mofileGlobal2);

        } else {

            // Load the default language files.
            load_plugin_textdomain('mgapp', false, $mgappLangDir);
        }

    }

}

} // End if class_exists check.

/**
 * The main function.
 */
function mgapp()
{
    return MGApp::instance();
}

// Get Running.
mgapp();
