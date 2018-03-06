<?php

/**
 * Autoloading classes from
 * @var CLASS_PATH defined in config file
 */

set_include_path( get_include_path().PATH_SEPARATOR.CLASS_DIR );
set_include_path( get_include_path().PATH_SEPARATOR.CLASS_DIR.INTERFACE_DIR );
spl_autoload_extensions( '.class.php' );
spl_autoload_register();

require_once 'vendor/autoload.php';


function esc( $request ) {
	if( isset( $request ) and !is_array( $request ) ) {
		return filter_var( $request, FILTER_SANITIZE_SPECIAL_CHARS );
	} elseif ( is_array( $request ) ) {
		array_filter( $request, 'trim' );
		filter_var_array( $request, FILTER_SANITIZE_SPECIAL_CHARS );
	}
	return $request;
}
function esc_url( String $str ) {
	return urlencode( $str );
}

