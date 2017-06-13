<?php

/**
 * Class Tribe__Duplicate__Strategy__Like
 *
 * Models a loose similarity strategy, punctuation is removed from string and words can be in any order.
 *
 * @since TBD
 */
class Tribe__Duplicate__Strategy__Like
	extends Tribe__Duplicate__Strategy__Base
	implements Tribe__Duplicate__Strategy__Interface {

	/**
	 * Returns a string suitable to be used as a WHERE clause in a SQL query.
	 *
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return string
	 *
	 * @since TBD
	 */
	public function where( $key, $value ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		if ( $this->is_a_numeric_post_field( $key ) ) {
			return $wpdb->prepare( "{$key} = %d", $value );
		}

		$frags = $this->get_where_frags( $value );

		$where_frags = array();
		foreach ( $frags as $frag ) {
			$prepared = $wpdb->prepare( '%s', '%' . strtolower( trim( $frag ) ) . '%' );
			$where_frags[] = "{$key} LIKE {$prepared}";
		}

		return implode( ' AND ', $where_frags );
	}

	/**
	 * Removes anything that's not letters, numbers, hypens and underscores from the string and returns its frags.
	 *
	 * @param string $value
	 *
	 * @return array
	 *
	 * @since TBD
	 */
	protected function get_where_frags( $value ) {
		$snaked = preg_replace( '/[^a-z\d-]+/i', '_', $value );
		$frags = array_filter( explode( '_', $snaked ) );

		return $frags;
	}

	/**
	 * Returns a string suitable to be used as a WHERE clause in a SQL query for a custom field JOIN.
	 *
	 * @param string $key
	 * @param mixed  $value
	 * @param string $table_alias
	 *
	 * @return string
	 *
	 * @since TBD
	 */
	public function where_custom_field( $key, $value, $table_alias ) {
		/** @var wpdb $wpdb */
		global $wpdb;

		$frags = $this->get_where_frags( $value );

		$where_frags = array();
		foreach ( $frags as $frag ) {
			$meta_value = $wpdb->prepare( '%s', '%' . strtolower( trim( $frag ) ) . '%' );
			$where_frags[] = $wpdb->prepare( "{$table_alias}.meta_key = %s AND {$table_alias}.meta_value LIKE ", $key ) . $meta_value;
		}

		return implode( " \n\tAND ", $where_frags );
	}
}