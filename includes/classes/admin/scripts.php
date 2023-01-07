<?php

namespace MailerGlueApp\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Scripts class.
 */
class Scripts {

	/**
	 * Construct.
	 */
	public function __construct() {

		// Output the top bar and tabs.
		add_action( 'load-post-new.php', array( $this, 'output_before_admin' ), 1 );
		add_action( 'load-post.php', array( $this, 'output_before_admin' ), 1 );
		add_action( 'load-edit.php', array( $this, 'output_before_admin' ), 1 );

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 9 );

		add_filter( 'screen_options_show_screen', array( $this, 'screen_options_show_screen' ), 99 );

		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 11 );
		add_filter( 'update_footer', array( $this, 'admin_footer_text' ), 11 );
	}

	/**
	 * Add content that runs before everything.
	 */
	public function output_before_admin() {
		global $post_type, $pagenow;

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) && ( strstr( $screen->id, 'edit-mailerglueapp' ) || strstr( $screen->id, 'mailerglueapp_' ) ) ) {
			add_action( 'all_admin_notices', array( $this, 'add_admin_content' ), 10 );
		}

	}

	/**
	 * Add admin content.
	 */
	public function add_admin_content() {

		$screen = get_current_screen();

		if ( ! empty( $screen->id ) && ( strstr( $screen->id, 'edit-mailerglueapp' ) || strstr( $screen->id, 'mailerglueapp_' ) ) ) {
			do_action( $screen->id );
		}
	}

	/**
	 * Enqueue admin scripts.
	 */
	public function admin_enqueue_scripts() {

	}

	/**
	 * Hide screen options button.
	 */
	public function screen_options_show_screen( $show_screen ) {
		global $post_type;

		if ( ! empty( $post_type ) && strstr( $post_type, 'mailerglueapp' ) ) {
			return false;
		}

		return $show_screen;
	}

	/**
	 * Hide admin footer text.
	 */
	public function admin_footer_text( $text ) {
		global $parent_file;

		if ( strstr( $parent_file, 'mailerglueapp' ) ) {
			return '';
		}

		return $text;
	}

}