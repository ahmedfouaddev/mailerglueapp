<?php

namespace MailerGlueApp;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Email class.
 */
class Email {

	public $to;
	public $subject;
	public $message;
	public $tags;
	public $headers;

	/**
	 * Construct.
	 */
	public function __construct() {

		add_filter( 'wp_mail_content_type', array( $this, 'wp_mail_content_type' ) );

		$this->headers = array(
			'Content-Type: text/html; charset=UTF-8',
			'From: Mailer Glue <donotreply@mailerglue.com>',
			'Reply-To: Mailer Glue <donotreply@mailerglue.com>'
		);

		$this->message = $this->get_default_template();
	}

	/**
	 * Get default email template.
	 */
	public function get_default_template() {
		ob_start();

		include MAILERGLUEAPP_PLUGIN_DIR . 'includes/views/email-template.php';

		return ob_get_clean();
	}

	/**
	 * Set content type to html.
	 */
	public function wp_mail_content_type() {
		return 'text/html';
	}

	/**
	 * Set subject.
	 */
	public function subject( $subject = '' ) {

		$this->subject = $subject;
	}

	/**
	 * Set to.
	 */
	public function to( $to = '' ) {

		$this->to = $to;
	}

	/**
	 * Set message.
	 */
	public function message( $message = '' ) {

		$this->message = str_replace( '{mailerglue_content_tag}', $message, $this->message );

		if ( ! empty( $this->tags ) ) {
			foreach( $this->tags as $tag => $value ) {
				$this->message = str_replace( '{' . $tag . '}', $value, $this->message );
			}
		}
	}

	/**
	 * Set tags.
	 */
	public function tags( $tags = array() ) {

		$this->tags = $tags;
	}

	/**
	 * Send the email.
	 */
	public function send() {

		wp_mail( $this->to, $this->subject, $this->message, $this->headers );
	}

}