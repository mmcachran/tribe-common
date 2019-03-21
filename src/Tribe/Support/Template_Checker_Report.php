<?php
/**
 * Assembles a report of recently updated plugin views and template overrides in
 * possible revision, for each plugin that registers itself and its template
 * filepaths.
 */
class Tribe__Support__Template_Checker_Report {
	const VERSION_INDEX         = 0;
	const INCLUDED_VIEWS_INDEX  = 1;
	const THEME_OVERRIDES_INDEX = 2;

	/**
	 * Contains the individual view/template reports for each registered plugin.
	 *
	 * @var array
	 */
	protected static $plugin_reports = array();

	/**
	 * Container for finished report.
	 *
	 * @var string
	 */
	protected static $complete_report = '';

	/**
	 * Provides an up-to-date report concerning template changes.
	 *
	 * @return string
	 */
	public static function generate() {
		foreach ( self::registered_plugins() as $plugin_name => $plugin_template_system ) {
			self::generate_for( $plugin_name, $plugin_template_system );
		}

		self::wrap_report();
		return self::$complete_report;
	}

	protected static function registered_plugins() {
		/**
		 * Provides a mechanism for plugins to register information about their template/view
		 * setups.
		 *
		 * This should be done by adding an entry to $registere_template_systems where the key
		 * should be the plugin name and the element an array structured as follows:
		 *
		 *     [
		 *       plugin_version,
		 *       path_to_included_views,
		 *       path_to_theme_overrides
		 *     ]
		 *
		 * @var array $registered_template_systems
		 */
		return apply_filters( 'tribe_support_registered_template_systems', array() );
	}

	/**
	 * Creates a report for the specified plugin.
	 *
	 * @param string $plugin_name
	 * @param array  $template_system
	 */
	protected static function generate_for( $plugin_name, array $template_system ) {

		$scanner = new Tribe__Support__Template_Checker(
			$template_system[ self::VERSION_INDEX ],
			$template_system[ self::INCLUDED_VIEWS_INDEX ],
			$template_system[ self::THEME_OVERRIDES_INDEX ]
		);

		$newly_introduced_or_updated = $scanner->get_views_tagged_this_release();
		$outdated_or_unknown = $scanner->get_outdated_overrides( true );

		$report = '<table class="tribe-template-status">';

		$report .= '<thead>';
		$report .= '<tr class="table-heading">';
		$report .= '<th colspan="4">' . esc_html( $plugin_name ) . '</th>';
		$report .= '</tr>';
		$report .= '</thead>';

		$report .= '<tbody>';

		if ( empty( $newly_introduced_or_updated ) && empty( $outdated_or_unknown ) ) {
			$report .= '<tr><td>' . __( 'No notable changes detected', 'tribe-common' ) . '</td></td>';
		}

		if ( ! empty( $newly_introduced_or_updated ) ) {
			$report .= '<tr><td><p>' . sprintf( __( 'Templates introduced or updated with this release (%s):', 'tribe-common' ), $template_system[ self::VERSION_INDEX ] ) . '</p></td>';

			$report .= '<td><ul>';

			foreach ( $newly_introduced_or_updated as $view_name => $version ) {
				$report .= '<li>' . esc_html( $view_name ) . '</li>';
			}

			$report .= '</ul></td></tr>';
		}

		if ( ! empty( $outdated_or_unknown ) ) {
			$report .= '<tr><td colspan="4"><p>' . __( 'Existing theme overrides that may need revision:', 'tribe-common' ) . '</p></td></tr>';

			$report .= '<tr><td class="label">Overrides</td>';
			$report .= '<td>';
			$report .= '<ul>';

			foreach ( $outdated_or_unknown as $view_name => $version ) {
				$version_note = empty( $version )
					? __( 'version data missing from override', 'tribe-common' )
					: sprintf( __( 'based on %s version', 'tribe-common' ), $version );

				$current_version = $scanner->get_template_version( $template_system[ self::INCLUDED_VIEWS_INDEX ] . '/' . $view_name );

				$override_folder = substr( $template_system[ self::THEME_OVERRIDES_INDEX ], strpos( $template_system[ self::THEME_OVERRIDES_INDEX ], 'themes' ) + strlen( 'themes' ) );

				$view_override_location = $override_folder . '/' . $view_name ;

				$text = sprintf( __( 'version <span class="error"><strong>%s</strong></span> is out of date. The core version is <strong>%s</strong>', 'tribe-common' ), $version, $current_version );

				$report .= '<li><code>' . $view_override_location . '</code>' . $text . '</li>';
			}

			$report .= '</ul></td></tr>';

			$report .= '<tr><td class="label">' . __( 'Outdated Templates', 'tribe-common' ) . '</td><td>';
			$report .= '<mark class="error"><span class="dashicons dashicons-warning"></span></mark> <a href="https://m.tri.be/outdated-templates" target="_blank">';
			$report .= __( 'Learn how to update', 'tribe-common' );
			$report .= '</a></td></tr>';
		}

		$report .= '</tbody></table>';

		self::$plugin_reports[ $plugin_name ] = $report;
	}

	/**
	 * Wraps the individual plugin template reports ready for display.
	 */
	protected static function wrap_report() {
		if ( empty( self::$plugin_reports ) ) {
			self::$complete_report = '<p>' . __( 'No notable template changes detected.', 'tribe-common' ) . '</p>';
		} else {
			self::$complete_report = '<p>' . __( 'Information about recent template changes and potentially impacted template overrides is provided below.', 'tribe-common' ) . '</p>'
				. '<div>' . join( ' ', self::$plugin_reports ) . '</div>';
		}
	}
}
