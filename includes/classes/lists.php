<?php

namespace MailerGlueApp;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Lists class.
 */
class Lists {

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Update (or create) a list.
	 */
	public function update( $args = array(), $account_id = 0 ) {

		$global_id 		= ! empty( $args[ 'global_id' ] ) ? sanitize_text_field( $args[ 'global_id' ] ) : '';
		$title 			= ! empty( $args[ 'title' ] ) ? sanitize_text_field( $args[ 'title' ] ) : '';
		$description 	= ! empty( $args[ 'description' ] ) ? sanitize_text_field( $args[ 'description' ] ) : '';

		$list_id = $this->list_exists_by_global_id( $global_id );

		if ( ! empty( $list_id ) ) {

			$post_args = array(
				'ID'			=> $list_id,
				'post_type'		=> 'mailerglueapp_list',
				'post_author'	=> 1,
				'post_status'	=> 'publish',
				'post_title'	=> $title,
			);

			$list_id = wp_update_post( $post_args );

		} else {

			$post_args = array(
				'post_type'		=> 'mailerglueapp_list',
				'post_author'	=> 1,
				'post_status'	=> 'publish',
				'post_title'	=> $title,
			);

			$list_id = wp_insert_post( $post_args );
		}

		update_post_meta( $list_id, 'global_id', $global_id );
		update_post_meta( $list_id, 'description', $description );
		update_post_meta( $list_id, 'account_id', $account_id );
	}

	/**
	 * Checks if list exists by checking the global ID.
	 */
	public function list_exists_by_global_id( $global_id = '' ) {

		$lists = get_posts( array(
			'post_type'		=> 'mailerglueapp_list',
			'post_status'	=> get_post_stati(),
			'meta_key'  	=> 'global_id',
			'meta_value' 	=> $global_id,
			'number'		=> 1,
		) );

		return ! empty( $lists ) ? $lists[0]->ID : false;
	}

}