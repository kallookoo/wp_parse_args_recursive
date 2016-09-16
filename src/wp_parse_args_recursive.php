<?php
/**
 * Like wp_parse_args but supports recursivity
 * By default converts the returned type based on the $args and $defaults
 *
 *
 * @author Sergio P.A. (23r9i0) <info@dsergio.com>
 * @license https://www.gnu.org/licenses/gpl-2.0.html GPL2 or later
 * @version 1.0
 *
 * @param  array|object  $args                   Values to merge with $defaults
 * @param  array|object  $defaults               Array or Object that serves as the defaults.
 * @param  boolean       $preserve_type          Optional. Convert output array into object if $args or $defaults if it is. Default true
 * @param  boolean       $preserve_integer_keys  Optional. If given, integer keys will be preserved and merged instead of appended. Default false.
 *
 * @return array|object  $output                 Merged user defined values with defaults.
 */
if ( ! function_exists( 'wp_parse_args_recursive' ) ) {
	function wp_parse_args_recursive( $args, $defaults, $preserve_type = true, $preserve_integer_keys = false ) {
		$output = array();

		foreach ( array( $defaults, $args ) as $list ) {
			foreach ( (array) $list as $key => $value ) {
				if ( is_integer( $key ) && ! $preserve_integer_keys ) {
					$output[] = $value;
				} elseif (
					isset( $output[ $key ] ) &&
					( is_array( $output[ $key ] ) || is_object( $output[ $key ] ) ) &&
					( is_array( $value ) || is_object( $value ) )
				) {
					$output[ $key ] = wp_parse_args_recursive( $value, $output[ $key ], $preserve_integer_keys );
				} else {
					$output[ $key ] = $value;
				}
			}
		}

		return ( $preserve_type && ( is_object( $args ) || is_object( $defaults ) ) ) ? (object) $output : $output;
	}
}
