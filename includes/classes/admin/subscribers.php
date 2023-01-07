<?php

namespace MailerGlueApp\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Subscribers class.
 */
class Subscribers {

	private $post_type = 'mailerglueapp_user';

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'mailerglueapp_user', array( $this, 'output_pre' ), 10 );
		add_action( 'edit-mailerglueapp_user', array( $this, 'output_pre' ), 10 );

		add_action( 'do_meta_boxes', array( $this, 'hide_publish_metabox' ), 10 );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );
	}

	/**
	 * Before output.
	 */
	public function output_pre() {

		$mailerglueapp_bar = new \MailerGlueApp\Admin\Bar;
		$mailerglueapp_bar->output();

		$mailerglueapp_tabs = new \MailerGlueApp\Admin\Tabs;

		$tabs = array(
			'mailerglueapp_user' 	=> __( 'Subscribers', 'mailerglue' ),
			'mailerglueapp_list'	=> __( 'Lists', 'mailerglue' ),
		);

		$mailerglueapp_tabs->output( $tabs );

	}

	/**
	 * Remove any unwanted meta boxes.
	 */
	public function hide_publish_metabox() {

		remove_meta_box( 'submitdiv', $this->post_type, 'side' );
		remove_meta_box( 'slugdiv', $this->post_type, 'normal' );
	}

	/**
	 * Edit row actions.
	 */
	public function post_row_actions( $actions, $post ) { 

		unset( $actions[ 'inline hide-if-no-js' ], $actions[ 'inline' ] );

		return $actions;
	}

}