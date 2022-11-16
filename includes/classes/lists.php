<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lists class.
 */
class Lists {

	private $prefix;
	private $table;

	/**
	 * Construct.
	 */
	public function __construct() {
		global $wpdb;

		$this->prefix = $wpdb->prefix . 'mailerglueapp_';
		$this->table  = $this->prefix . 'lists';
	}

	/**
	 * Add a list.
	 */
	public function add_list( $args = array() ) {
		global $wpdb;

		$name = ! empty( $args[ 'name' ] ) ? $args[ 'name' ] : '';

		$wpdb->insert( $this->table, array(
			'name'			=> $name,
			'create_time'	=> current_time( 'mysql', 1 ),
		) );
	}

	/**
	 * Remove a list.
	 */
	public function remove_list( $list_id = 0 ) {
		global $wpdb;

		$wpdb->delete( $this->table, array( 'list_id' => $list_id ) );
	}

}
