<?php

namespace MailerGlue;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lists class.
 */
class Lists
{

	private $tablePrefix;
	private $tableName;

	/**
	 * Construct.
	 */
	public function __construct()
	{
		global $wpdb;

		$this->tablePrefix = $wpdb->prefix . 'mailerglueapp_';
		$this->tableName   = $this->tablePrefix . 'lists';
	}

	/**
	 * Add a list.
	 */
	public function addList($args = array())
	{
		global $wpdb;

		$name = !empty($args['name']) ? $args['name'] : '';

		$wpdb->insert($this->tableName, array(
			'name'			=> $name,
			'create_time'	=> current_time('mysql', 1),
		));
	}

	/**
	 * Remove a list.
	 */
	public function removeList($listID = 0)
	{
		global $wpdb;

		$wpdb->delete($this->tableName, array('list_id' => $listID));
	}

}
