<?php
/**
 * Shows an admin notice for the template conflicts
 */
class Tribe__Admin__Notice__Templates {

	public function hook() {

		// display the PHP version notice
		tribe_notice(
			'template-conflict',
			array( $this, 'display_notice' ),
			array(
				'type'    => 'warning',
				'dismiss' => 1,
				'wrap'    => 'p',
			),
			array( $this, 'should_display' )
		);

	}

	/**
	 * Check if we should be displaying the notice
	 *
	 * @since  TBD
	 *
	 * @return boolean
	 */
	public function should_display() {

		global $current_screen;

		if ( 'tribe_events_page_tribe-help' === $current_screen->id ) {
			return false;
		}

		// bail if it's not part of the tribe pages.
		$admin_helpers = tribe( 'admin.helpers' );

		if ( ! $admin_helpers->is_screen() ) {
			return false;
		}

		$plugins = apply_filters( 'tribe_support_registered_template_systems', [] );

		foreach ( $plugins as $plugin_name => $plugin_template_system ) {
			$scanner = new Tribe__Support__Template_Checker(
				$plugin_template_system[ 0 ],
				$plugin_template_system[ 1 ],
				$plugin_template_system[ 2 ]
			);

			$outdated_or_unknown = $scanner->get_outdated_overrides( true );

			if ( ! empty( $outdated_or_unknown ) ) {
				return true;
			}

		}

		return false;
	}

	/**
	 * HTML for the PHP notice
	 *
	 * @since  TBD
	 *
	 * @return string
	 */
	public function display_notice() {
		// The notice
		$notice  = '<strong>' . __( 'Template Conflicts', 'tribe-common' ) . '</strong>';

		$notice .= '<p>' . __( 'Due to your customization(s), there is currently a template conflict. Please review Events > Help for more information.', 'tribe-common' ) . '</p>';

		$notice .= sprintf(
			'<a href="%1$s" class="button button-primary button-tribe-notice">%2$s</a>',
			'edit.php?post_type=tribe_events&page=tribe-help',
			esc_html__( 'Visit Help Page', 'tribe-common' )
		);

		return $notice;

	}
}
