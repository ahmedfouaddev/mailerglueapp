<?php

namespace MailerGlueApp;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * User class.
 */
class User {

	private $id;

	private $data;

	private $access_token;

	/**
	 * Construct.
	 */
	public function __construct() {

	}

	/**
	 * Sets a user by a given email.
	 */
	public function set( $user_id = 0 ) {

		$user = get_userdata( $user_id );

		if ( $user && ! empty( $user->ID ) ) {
			$this->id	 = $user->ID;
			$this->data	 = $user->data;
		}
	}

	/**
	 * Sets a user by a given email.
	 */
	public function set_by_email( $email ) {

		$user = get_user_by( 'email', $email );

		if ( $user && ! empty( $user->ID ) ) {
			$this->id	 = $user->ID;
			$this->data	 = $user->data;
		}
	}

	/**
	 * Get user's ID.
	 */
	public function get_id() {

		return $this->id ? $this->id : 0;
	}

	/**
	 * Get user's email.
	 */
	public function get_email() {

		return ! empty( $this->data->user_email ) ? $this->data->user_email : '';
	}

	/**
	 * Get user's name.
	 */
	public function get_name() {

		$fname = get_user_meta( $this->id, 'first_name', true );
		$lname = get_user_meta( $this->id, 'last_name', true );

		if ( $fname || $lname ) {
			$name = $fname . ' ' . $lname;
		} else {
			$name = '';
		}

		return $name;
	}

	/**
	 * Get user's password.
	 */
	public function get_password() {

		return ! empty( $this->data->user_pass ) ? $this->data->user_pass : '';
	}

	/**
	 * Get access token.
	 */
	public function get_access_token() {

		if ( ! empty( $this->access_token ) ) {
			return $this->access_token;
		}

		return get_user_meta( $this->get_id(), '_mailerglue_token', true );
	}

	/**
	 * Check if user has a valid ID.
	 */
	public function has_id() {

		return $this->id ? true : false;
	}

	/**
	 * Checks if the provided password is valid for a user.
	 */
	public function check_credentials( $password ) {

		return wp_check_password( $password, $this->get_password() );
	}

	/**
	 * Sets access token for a user.
	 */
	public function set_access_token() {

		$token = bin2hex( random_bytes( 20 ) );

		update_user_meta( $this->get_id(), '_mailerglue_token', $token );

		$this->access_token = $token;

		return $this->access_token;
	}

	/**
	 * Validates the user provided token.
	 */
	public function validate_token( $provided_token ) {

		$token = get_user_meta( $this->get_id(), '_mailerglue_token', true );

		return $token && $token === $provided_token;
	}

	/**
	 * Checks if account is activated.
	 */
	public function is_activated() {

		$status = $this->get_account_status();

		return $status === 'verified' ? true : false;
	}

	/**
	 * Returns the user's account status.
	 */
	public function get_account_status() {

		$status = get_user_meta( $this->get_id(), '_mailerglue_status', true );

		return $status;
	}

	/**
	 * Set account ready for activation.
	 */
	public function setup_activation() {

		if ( $this->get_activation_code() ) {

			$code = $this->get_activation_code();

			$this->send_activation_email( $code );
		} else {

			$code = random_int( 100000, 999999 );

			$this->send_activation_email( $code );
		}

		$this->update_meta( array( 'code' => $code, 'status' => 'unverified' ) );

		return $code;
	}

	/**
	 * Set an account as activated.
	 */
	public function activate_account() {

		delete_user_meta( $this->get_id(), '_mailerglue_code' );

		update_user_meta( $this->get_id(), '_mailerglue_status', 'verified' );
	}

	/**
	 * Get user's activation code.
	 */
	public function get_activation_code() {

		$activation_code = get_user_meta( $this->get_id(), '_mailerglue_code', true );

		return $activation_code;
	}

	/**
	 * Update account meta.
	 */
	public function update_meta( $data ) {

		if ( empty( $data ) || ! is_array( $data ) ) {
			return;
		}

		foreach( $data as $key => $value ) {
			update_user_meta( $this->get_id(), '_mailerglue_' . $key, $value );
		}
	}

	/**
	 * This sends an email with activation code in it.
	 */
	public function send_activation_email( $code = '' ) {
		if ( empty( $code ) ) {
			return;
		}

		$email = new \MailerGlueApp\Email;

		$email->tags( array( 'code' => $code ) );
		$email->to( $this->get_email() );
		$email->subject( __( 'Activate your Mailer Glue account', 'mailerglueapp' ) );
		$email->message( $this->get_email_activation_template() );

		$email->send();
	}

	/**
	 * Get email activation template.
	 */
	public function get_email_activation_template() {
		ob_start();

		include_once MAILERGLUEAPP_PLUGIN_DIR . 'includes/emails/email-activation.php';

		return ob_get_clean();
	}

}
