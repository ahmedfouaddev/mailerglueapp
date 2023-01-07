<?php

namespace MailerGlueApp\Admin;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Tabs class.
 */
class Tabs {

	/**
	 * Output.
	 */
	public function output( $tabs = array() ) {

		if ( empty( $tabs ) || ! is_array( $tabs ) ) {
			return;
		}

		?>
		<div class="mailerglue-ui">
			<div class="components-panel__body mailerglue-tabs is-opened">
				<div class="components-panel__row">
					<ul>
						<?php foreach( $tabs as $page => $title ) : ?>
						<li><a class="components-button is-link <?php if ( $this->is_current_tab( $page ) ) echo 'mailerglue-active'; ?>" href="<?php echo $this->generate_link( $page ); ?>"><?php echo esc_html( $title ); ?></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Checks if given link is active tab.
	 */
	public function is_current_tab( $page ) {
		global $pagenow, $plugin_page, $post_type;

		$current_page = isset( $_GET[ 'page' ] ) ? esc_attr( $_GET[ 'page' ] ) : '';

		if ( $post_type == $page ) {
			return true;
		} else if ( $current_page == $page ) {
			return true;
		}

		return false;
	}

	/**
	 * Generate tab link.
	 */
	public function generate_link( $page = '' ) {

		if ( strstr( $page, 'mailerglueapp_' ) ) {
			$page = 'edit.php?post_type=' . $page;
		}

		if ( strstr( $page, 'mailerglueapp-' ) ) {
			$page = 'admin.php?page=' . $page;
		}

		$page = esc_url( admin_url( $page ) );

		return $page;
	}

}