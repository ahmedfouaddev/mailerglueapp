<?php

namespace MailerGlueApp\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Bar class.
 */
class Bar {

	/**
	 * Output.
	 */
	public function output() {
		?>
		<div class="mailerglue-ui">
			<div class="mailerglue-primary-bar"></div>
		</div>
		<?php
	}

}