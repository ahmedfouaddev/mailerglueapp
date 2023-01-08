<?php

namespace MailerGlueApp;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Install class.
 */
class Install {

	private $prefix;

	/**
	 * Construct.
	 */
	public function __construct()
	{
		global $wpdb;

		$this->prefix = $wpdb->prefix . 'mailerglueapp_';

		register_activation_hook( MAILERGLUEAPP_PLUGIN_FILE, array( $this, 'pre_install' ), 10 );
	}

	/**
	 * Runs on plugin activation.
	 */
	public function pre_install( $network_wide = false ) {
		global $wpdb;

		if ( is_multisite() && $network_wide ) {

			foreach( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
				switch_to_blog( $blog_id );
				$this->install();
				restore_current_blog();
			}

		} else {
			$this->install();
		}
	}

	/**
	 * Run the installer.
	 */
	public function install() {

		set_transient( '_mailerglueapp_onboarding', 1, 30 );

	}

}
