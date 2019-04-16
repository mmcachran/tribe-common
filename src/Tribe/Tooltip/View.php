<?php

/**
 * Class Tribe__Tooltip__View
 *
 * @since TBD
 */
class Tribe__Tooltip__View extends Tribe__Template {

	/**
	 * Tribe__Tooltip__View constructor.
	 *
	 * @since TBD
	 */
	public function __construct() {
		$this->set_template_origin( Tribe__Main::instance() );
		$this->set_template_folder( 'src/views/tooltip' );

		// Configures this templating class to extract variables
		$this->set_template_context_extract( true );

		// Uses the public folders
		$this->set_template_folder_lookup( true );
	}

	/**
	 * Public wrapper for build method
	 *
	 * @since TBD
	 *
	 * @param array|string $message Array of messages or single message as string.
	 * @param array $args Extra arguments.
	 * @return string A string of html for the tooltip.
	 */
	public function render_tooltip( $message, $args = [] ) {
		if ( empty( $message ) ) {
			return;
		}

		$html = $this->build_tooltip( $message, $args );

		return $html;
	}

	/**
	 * Factory method for tooltip HTML
	 *
	 * @since TBD
	 *
	 * @param array|string $message array of messages or single message as string.
	 * @param array $args Extra arguments, defaults include icon, classes, direction, and context (for the filters).
	 * @return string A string of html for the tooltip.
	 */
	private function build_tooltip( $message, $original_args ) {
		$default_args = [
			'classes'   => '',
			'icon'      => 'info',
			'direction' => 'down',
			'context'   => '',
		];

		$args = wp_parse_args( $original_args, $default_args );

		$args[ 'message' ] = $message;

		ob_start();

		/**
		 * Allow us to filter the tooltip template
		 *
		 * @since  TBD
		 *
		 * @param string $template The tooltip template name.
		 * @param array $args Extra arguments, defaults include icon, classes, direction, and context.
		 */
		$template_name = apply_filters( 'tribe_tooltip_template', 'tooltip', $args );

		$template = $this->template( $template_name, $args, false );

		if ( ! empty( $template ) ) {
			 echo $template;
		}

		$html = ob_get_clean();

		/**
		 * Allow us to filter the tooltip output
		 *
		 * @since  TBD
		 *
		 * @param string $html The tooltip HTML.
		 * @param array|string $message Array of messages or single message as string.
		 * @param array $args Extra arguments, defaults include icon, classes, direction, and context.
		 */
		return apply_filters( 'tribe_tooltip_html', $html, $message, $args );
	}

}
