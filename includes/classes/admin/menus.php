<?php

namespace MailerGlueApp\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Menus class.
 */
class Menus {

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'setup_admin_menus' ), 10 );

		add_action( 'admin_menu', array( $this, 'menu_order_fix' ), 1000 );
		add_action( 'admin_menu_editor-menu_replaced', array( $this, 'menu_order_fix' ), 1000 );

		add_filter( 'parent_file', array( $this, 'parent_file' ), 10 );
		add_filter( 'submenu_file', array( $this, 'highlight_menu_item' ), 50 );

	}

	/**
	 * Setup admin menus.
	 */
	public function setup_admin_menus() {

		$get_menu_icon = $this->get_menu_icon();

		$subscribers 	= new \MailerGlueApp\Admin\Subscribers;
		$lists 			= new \MailerGlueApp\Admin\Lists;

		add_menu_page( __( 'Mailer Glue App', 'mailerglueapp' ), __( 'Mailer Glue App', 'mailerglueapp' ), 'manage_options', 'mailerglueapp', null, $get_menu_icon, '25.5470' );
		add_submenu_page( 'mailerglueapp', __( 'Subscribers', 'mailerglueapp' ), __( 'Subscribers', 'mailerglueapp' ), 'manage_options', 'edit.php?post_type=mailerglueapp_user' );

	}

	/**
	 * Fix menu order.
	 */
	public function menu_order_fix() {
		global $submenu;

		if ( isset( $submenu ) && is_array( $submenu ) ) {
			foreach( $submenu as $key => $array ) {
				if ( $key === 'mailerglueapp' ) {
					foreach( $array as $index => $value ) {
						if ( isset( $value[2] ) && $value[2] === 'mailerglueapp' ) {
							unset( $submenu[ 'mailerglueapp' ][ $index ] );
						}
					}
				}
			}
		}
	}

	/**
	 * Get admin menu icon.
	 */
	public function get_menu_icon() {
		return 'data:image/svg+xml;base64,' . '';
	}

	/**
	 * Display in correct menu parent.
	 */
	public function parent_file() {
		global $plugin_page, $submenu_file, $parent_file;

		if ( in_array( $submenu_file, array( 'edit.php?post_type=mailerglueapp_email', 'post-new.php?post_type=mailerglueapp_email' ) ) ) {
			$parent_file = 'edit.php?post_type=mailerglueapp_email';
			$plugin_page = 'edit.php?post_type=mailerglueapp_email';
		}

		if ( in_array( $submenu_file, array( 'edit.php?post_type=mailerglueapp_list', 'post-new.php?post_type=mailerglueapp_list', 'edit.php?post_type=mailerglueapp_user', 'post-new.php?post_type=mailerglueapp_user' ) ) ) {
			$parent_file = 'edit.php?post_type=mailerglueapp_user';
			$plugin_page = 'edit.php?post_type=mailerglueapp_user';
		}

		return $parent_file;
	}

	/**
	 * Highlight correct menu item.
	 */
	public function highlight_menu_item( $submenu_file ) {

		if ( in_array( $submenu_file, array( 'edit.php?post_type=mailerglueapp_email', 'post-new.php?post_type=mailerglueapp_email' ) ) ) {
			return 'edit.php?post_type=mailerglueapp_email';
		}

		if ( in_array( $submenu_file, array( 'edit.php?post_type=mailerglueapp_list', 'post-new.php?post_type=mailerglueapp_list', 'edit.php?post_type=mailerglueapp_user', 'post-new.php?post_type=mailerglueapp_user' ) ) ) {
			return 'edit.php?post_type=mailerglueapp_user';
		}

		// Don't change anything
		return $submenu_file;
	}

}