<?php
/**
 * HTML functions (template-tags) for use in WordPress templates.
 */
use Tribe\Utils\Element_Attributes;
use Tribe\Utils\Element_Classes;

/**
 * Parse input values into a valid array of classes to be used in the templates.
 *
 * @since  4.9.13
 *
 * @param  mixed $classes,... unlimited Any amount of params to be rendered as classes.
 *
 * @return array
 */
function tribe_get_classes() {
	$element_classes = new Element_Classes( func_get_args() );
	return $element_classes->get_classes();
}

/**
 * Parses input values into a valid class html attribute to be used in the templates.
 *
 * @since  4.9.13
 *
 * @param  mixed $classes,... unlimited Any amount of params to be rendered as classes.
 *
 * @return void
 */
function tribe_classes() {
	$element_classes = new Element_Classes( func_get_args() );
	echo $element_classes->get_attribute();
}

/**
 * Parse input values into a valid array of attributes to be used in the templates.
 *
 * @since  4.12.3
 *
 * @param  mixed $attributes,... unlimited Any amount of params to be rendered as attributes.
 *
 * @return array<string> An array of the parsed string attributes.
 */
function tribe_get_attributes() {
	$element_attributes = new Element_Attributes( func_get_args() );
	return $element_attributes->get_attributes_array();
}

/**
 * Parse input values into a valid html attributes to be used in the templates.
 *
 * @since  4.12.3
 *
 * @param  mixed $attributes,... unlimited Any amount of params to be rendered as attributes.
 *
 * @return void
 */
function tribe_attributes() {
	$element_attributes = new Element_Attributes( func_get_args() );
	echo $element_attributes->get_attributes();
}

/**
 * Get attributes for required fields.
 *
 * @since 4.10.0
 *
 * @param boolean $required If the field is required.
 * @param boolean $echo     Whether to echo the string or return it.
 *
 * @return string|void If echo is false, returns $required_string.
 */
function tribe_required( $required, $echo = true ) {
	if ( $required ) {
		$required_string = 'required aria-required="true"';

		if ( ! $echo ) {
			return $required_string;
		}

		echo $required_string;
	}
}

/**
 * Get string for required field labels.
 *
 * @since 4.10.0
 *
 * @param boolean $required If the field is required.
 * @param boolean $echo     Whether to echo the string or return it.
 *
 * @return string|void If echo is false, returns $required_string.
 */
function tribe_required_label( $required, $echo = true ) {
	if ( $required ) {
		$required_string = '<span class="screen-reader-text">'
			. esc_html_x( '(required)', 'The associated field is required.', 'tribe-common' )
			. '</span><span class="tribe-required" aria-hidden="true" role="presentation">*</span>';

		if ( ! $echo ) {
			return $required_string;
		}

		echo $required_string;
	}
}

/**
 * Get attributes for disabled fields.
 *
 * @since 4.10.0
 *
 * @param boolean $disabled If the field is disabled.
 * @param boolean $echo     Whether to echo the string or return it.
 *
 * @return string|void If echo is false, returns $disabled_string.
 */
function tribe_disabled( $disabled, $echo = true ) {
	if ( $disabled ) {
		$disabled_string = 'disabled aria-disabled="true"';

		if ( ! $echo ) {
			return $disabled_string;
		}

		echo $disabled_string;
	}
}

/**
 * Adds rel attribute for links.
 *
 * @since TBD
 *
 * @param string|array $rel  Value(s) for rel attribute.
 * @param boolean      $echo Whether to echo the string or return it.
 *
 * @return string the rel attribute
 */
function tribe_rel( $rel, $echo = true ) {
	if ( empty( $rel ) ) {
		return;
	}

	$attr = 'rel="' . esc_attr( $rel ) . '"';

	if ( ! $echo ) {
		return $attr;
	}

	echo $attr;
}

/**
 * Adds target attribute for links.
 * If target is '_blank' also adds appropriate rel attributes.
 *
 * @since TBD
 *
 * @param string  $target   Value for the target attribute.
 * @param array|string $rel Value(s) for rel attribute.
 * @param boolean $echo     Whether to echo the string or return it.
 *
 * @return string The rel attribute.
 */
function tribe_target( $target, $rel = [], $echo = true ) {
	if ( empty( $rel ) ) {
		return;
	}

	if ( ! is_array( $rel ) ) {
		$rel = explode( ' ', $rel );
	}

	$attr = 'target="' . esc_attr( $target ) . '"';

	if ( '_blank' === $target ) {
		$rel[] = 'noopener';
		$rel[] = 'nofollow';
	}

	if ( ! empty( $rel ) ) {
		$attr .= tribe_rel( $rel, false );
	}


	if ( ! $echo ) {
		return $attr;
	}

	echo $attr;
}
