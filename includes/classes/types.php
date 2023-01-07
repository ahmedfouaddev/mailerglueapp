<?php

namespace MailerGlueApp;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Types class.
 */
class Types {

	/**
	 * Construct.
	 */
	public function __construct() {

		// Register post types.
		add_action( 'init', array( $this, 'register_post_types' ), 5 );

		add_filter( 'use_block_editor_for_post_type', array( $this, 'use_block_editor_for_post_type' ), 99999, 2 );

	}

	/**
	 * Register core post types.
	 */
	public function register_post_types() {

		if ( ! is_blog_installed() || post_type_exists( 'mailerglueapp_email' ) ) {
			return;
		}

		do_action( 'mailerglueapp_register_post_types' );

		// Create campaign post type.
		register_post_type(
			'mailerglueapp_email',
			apply_filters(
				'mailerglueapp_email_post_type_template',
				array(
					'labels'             => array(
						'name'                  => __( 'Campaigns', 'mailerglueapp' ),
						'singular_name'         => __( 'Campaign', 'mailerglueapp' ),
						'menu_name'             => esc_html_x( 'All Campaigns', 'Admin menu name', 'mailerglueapp' ),
						'add_new'               => __( 'Add Campaign', 'mailerglueapp' ),
						'add_new_item'          => __( 'Add New Campaign', 'mailerglueapp' ),
						'edit'                  => __( 'Edit', 'mailerglueapp' ),
						'edit_item'             => __( 'Edit Campaign', 'mailerglueapp' ),
						'new_item'              => __( 'New Campaign', 'mailerglueapp' ),
						'view_item'             => __( 'View Campaign', 'mailerglueapp' ),
						'search_items'          => __( 'Search Campaigns', 'mailerglueapp' ),
						'not_found'             => __( 'No Campaigns found', 'mailerglueapp' ),
						'not_found_in_trash'    => __( 'No Campaigns found in trash', 'mailerglueapp' ),
						'parent'                => __( 'Parent Campaign', 'mailerglueapp' ),
						'filter_items_list'     => __( 'Filter Campaigns', 'mailerglueapp' ),
						'items_list_navigation' => __( 'Campaigns navigation', 'mailerglueapp' ),
						'items_list'            => __( 'Campaigns list', 'mailerglueapp' ),
					),
					'description'         	=> __( 'This is where you can add new Campaigns to Mailer Glue plugin.', 'mailerglueapp' ),
					'capability_type'		=> 'post',
					'exclude_from_search' 	=> false,
					'show_in_menu'        	=> false,
					'hierarchical'        	=> false,
					'supports'           	=> array( 'title', 'editor', 'thumbnail', 'custom-fields' ),
					'taxonomies'        	=> array( '' ),
					'public'              	=> true,
					'show_ui'             	=> true,
					'publicly_queryable'  	=> true,
					'query_var'           	=> true,
					'show_in_nav_menus'		=> true,
					'show_in_admin_bar'   	=> true,
					'show_in_rest'		  	=> true,
				)
			)
		);

		// Create list post type.
		register_post_type(
			'mailerglueapp_list',
			apply_filters(
				'mailerglueapp_list_post_type_template',
				array(
					'labels'             => array(
						'name'                  => __( 'Lists', 'mailerglueapp' ),
						'singular_name'         => __( 'List', 'mailerglueapp' ),
						'menu_name'             => esc_html_x( 'All Lists', 'Admin menu name', 'mailerglueapp' ),
						'add_new'               => __( 'Add List', 'mailerglueapp' ),
						'add_new_item'          => __( 'Add New List', 'mailerglueapp' ),
						'edit'                  => __( 'Edit', 'mailerglueapp' ),
						'edit_item'             => __( 'Edit List', 'mailerglueapp' ),
						'new_item'              => __( 'New List', 'mailerglueapp' ),
						'view_item'             => __( 'View List', 'mailerglueapp' ),
						'search_items'          => __( 'Search Lists', 'mailerglueapp' ),
						'not_found'             => __( 'No Lists found', 'mailerglueapp' ),
						'not_found_in_trash'    => __( 'No Lists found in trash', 'mailerglueapp' ),
						'parent'                => __( 'Parent List', 'mailerglueapp' ),
						'filter_items_list'     => __( 'Filter Lists', 'mailerglueapp' ),
						'items_list_navigation' => __( 'Lists navigation', 'mailerglueapp' ),
						'items_list'            => __( 'Lists list', 'mailerglueapp' ),
					),
					'description'         	=> __( 'This is where you can add new Lists to Mailer Glue plugin.', 'mailerglueapp' ),
					'capability_type'		=> 'post',
					'exclude_from_search' 	=> true,
					'show_in_menu'        	=> false,
					'hierarchical'        	=> false,
					'supports'           	=> array( '' ),
					'taxonomies'        	=> array( '' ),
					'public'              	=> false,
					'show_ui'             	=> true,
					'publicly_queryable'  	=> false,
					'query_var'           	=> false,
					'show_in_nav_menus'		=> true,
					'show_in_admin_bar'   	=> false,
					'show_in_rest'		  	=> true,
				)
			)
		);

		// Create user post type.
		register_post_type(
			'mailerglueapp_user',
			apply_filters(
				'mailerglueapp_user_post_type_template',
				array(
					'labels'             => array(
						'name'                  => __( 'Subscribers', 'mailerglueapp' ),
						'singular_name'         => __( 'Subscriber', 'mailerglueapp' ),
						'menu_name'             => esc_html_x( 'All Lists', 'Admin menu name', 'mailerglueapp' ),
						'add_new'               => __( 'Add Subscriber', 'mailerglueapp' ),
						'add_new_item'          => __( 'Add New Subscriber', 'mailerglueapp' ),
						'edit'                  => __( 'Edit', 'mailerglueapp' ),
						'edit_item'             => __( 'Edit Subscriber', 'mailerglueapp' ),
						'new_item'              => __( 'New Subscriber', 'mailerglueapp' ),
						'view_item'             => __( 'View Subscriber', 'mailerglueapp' ),
						'search_items'          => __( 'Search Subscribers', 'mailerglueapp' ),
						'not_found'             => __( 'No Subscribers found', 'mailerglueapp' ),
						'not_found_in_trash'    => __( 'No Subscribers found in trash', 'mailerglueapp' ),
						'parent'                => __( 'Parent Subscriber', 'mailerglueapp' ),
						'filter_items_list'     => __( 'Filter Subscribers', 'mailerglueapp' ),
						'items_list_navigation' => __( 'Subscribers navigation', 'mailerglueapp' ),
						'items_list'            => __( 'Subscribers list', 'mailerglueapp' ),
					),
					'description'         	=> __( 'This is where you can add new Subscribers to Mailer Glue plugin.', 'mailerglueapp' ),
					'capability_type'		=> 'post',
					'exclude_from_search' 	=> true,
					'show_in_menu'        	=> false,
					'hierarchical'        	=> false,
					'supports'           	=> array( '' ),
					'taxonomies'        	=> array( '' ),
					'public'              	=> false,
					'show_ui'             	=> true,
					'publicly_queryable'  	=> false,
					'query_var'           	=> false,
					'show_in_nav_menus'		=> true,
					'show_in_admin_bar'   	=> false,
					'show_in_rest'		  	=> true,
				)
			)
		);

	}

	/**
	 * This force Gutenberg to be used for our post types.
	 */
	public function use_block_editor_for_post_type( $is_enabled, $post_type ) {

		if ( in_array( $post_type, mailerglueapp_get_custom_post_types() ) ) {
			return false;
		}

		if ( strstr( $post_type, 'mailerglueapp' ) ) {
			return true;
		}

		if ( in_array( $post_type, mailerglueapp_get_post_types() ) ) {
			return true;
		}

		return $is_enabled;
	}

}