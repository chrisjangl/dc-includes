<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Admin Color Scheme
 *
 * Registers the Digitally Cultured wp-admin color scheme and makes it the
 * default for newly registered users.
 *
 * @package     Child theme
 * @subpackage  Includes
 * @author      Digitally Cultured
 */

add_action( 'admin_init', 'additional_admin_color_schemes' );
add_action( 'user_register', 'set_default_admin_color' );

function additional_admin_color_schemes() {
	wp_admin_css_color( 'digitallycultured', __( 'Digitally Cultured' ),
		get_theme_file_uri( 'dc-includes/admin-colors/digitallycultured/colors.min.css' ),
		array( '#4b4c4e', '#3e7fe3', '#ff8400', '#f2fcff' ),
		array( 'base' => '#4b4c4e', 'focus' => '#ff8400', 'current' => '#3e7fe3' )
	);
}

function set_default_admin_color( $user_id ) {
	wp_update_user( array(
		'ID'          => $user_id,
		'admin_color' => 'digitallycultured',
	) );
}
