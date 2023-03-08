<?php // phpcs:disable WooCommerce.Commenting.CommentTags
/**
 * Like wp_parse_args but supports recursivity
 * By default converts the returned type based on the $args and $defaults
 *
 * @author Sergio (kallookoo) <kallookoo@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-2.0.html GPL2 or later
 * @version 1.4.0
 *
 * @package dsergio\com\WordPress\Helpers
 */

if ( ! function_exists( 'wp_parse_args_recursive' ) ) {
	/**
	 * Like wp_parse_args but supports recursivity
	 * By default converts the returned type based on the $args and $defaults
	 *
	 * @param  string|array|object $args                   Values to merge with $defaults.
	 * @param  string|array|object $defaults               Optional. Values that serves as the defaults.
	 *                                                     Default empty array.
	 * @param  boolean             $preserve_type          Optional. Convert output array into object if $args or $defaults if it is.
	 *                                                     Default true.
	 * @param  boolean             $preserve_integer_keys  Optional. If given, integer keys will be preserved and merged instead of appended.
	 *                                                     Default false.
	 *
	 * @return array|object $output Merged user defined values with defaults.
	 */
	function wp_parse_args_recursive( $args, $defaults = array(), $preserve_type = true, $preserve_integer_keys = false ) {
		$output = array();
		foreach ( array( $defaults, $args ) as $list ) {
			$parsed_args = array();
			if ( is_array( $list ) ) {
				$parsed_args = $list;
			} elseif ( is_object( $list ) ) {
				$parsed_args = get_object_vars( $list );
			} elseif ( is_string( $list ) ) {
				parse_str( $list, $parsed_args );
			}
			foreach ( $parsed_args as $key => $value ) {
				if ( is_int( $key ) && ! $preserve_integer_keys ) {
					$output[] = $value;
				} elseif (
					isset( $output[ $key ] ) &&
					( is_array( $output[ $key ] ) || is_object( $output[ $key ] ) ) &&
					( is_array( $value ) || is_object( $value ) )
				) {
					$output[ $key ] = wp_parse_args_recursive( $value, $output[ $key ], $preserve_type, $preserve_integer_keys );
				} else {
					$output[ $key ] = $value;
				}
			}
		}
		if ( $preserve_type && ( is_object( $args ) || is_object( $defaults ) ) ) {
			$output = (object) $output;
		}
		return $output;
	}
}
