<?php

namespace MailerGlueApp\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lists class.
 */
class Lists {

	private $post_type = 'mailerglueapp_list';

	/**
	 * Construct.
	 */
	public function __construct() {

		add_action( 'mailerglueapp_list', array( $this, 'output_pre' ), 10 );
		add_action( 'mailerglueapp_list', array( $this, 'output' ), 20 );

		add_action( 'edit-mailerglueapp_list', array( $this, 'output_pre' ), 10 );

		add_action( 'pre_get_posts', array( $this, 'order_by_default' ), 10 );

		add_action( 'do_meta_boxes', array( $this, 'hide_publish_metabox' ), 10 );

		add_filter( 'post_row_actions', array( $this, 'post_row_actions' ), 10, 2 );

		add_filter( 'mailerglueapp_backend_args', array( $this, 'add_js_args' ), 50 );
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
	 * Output.
	 */
	public function output() {
		global $post;
		?>
		<div id="mailerglue-edit-list" data-post-id="<?php echo absint( $post->ID ); ?>"></div>
		<?php
	}

	/**
	 * Default sorting in admin.
	 */
	public function order_by_default( $query ) {
		if ( ! is_admin() ) {
			return;
		}

		$screen = get_current_screen();

		if ( 'edit' == $screen->base && 'mailerglueapp_list' == $screen->post_type && ! isset( $_GET['orderby'] ) ) {
			$query->set( 'orderby', 'title' );
			$query->set( 'order', 'ASC' );
		}

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

	/**
	 * Add list meta to the JS args.
	 */
	public function add_js_args( $args = array() ) {
		global $pagenow, $post_type, $post;

		if ( $pagenow == 'post.php' && $post_type == 'mailerglueapp_list' && ! empty( $post->ID ) ) {
			$list = new \MailerGlueApp\Lists;
			$list->set( $post->ID );

			$args[ 'list' ] = $list->get_meta();
		}

		return $args;

	}

}