<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Login Screen Branding
 *
 * Custom wp-login.php layout. The layout itself (box sizing, responsive
 * breakpoints, button styling) is shared across sites; font and background
 * image are per-site, set via filters:
 *
 *   add_filter( 'dc_login_font_family', fn() => "'Megrim', cursive" );
 *   add_filter( 'dc_login_font_import_url', fn() => 'https://fonts.googleapis.com/css?family=Megrim' );
 *   add_filter( 'dc_login_background_image', fn() => get_stylesheet_directory_uri() . '/assets/images/login-screen.jpg' );
 *
 * @package     Child theme
 * @subpackage  Includes
 * @author      Digitally Cultured
 */

add_action( 'login_enqueue_scripts', 'dc_custom_login' );
add_filter( 'login_headertitle', 'dc_set_loginheadertitle' );

function dc_custom_login() {
	$font_family      = apply_filters( 'dc_login_font_family', 'inherit' );
	$font_import_url  = apply_filters( 'dc_login_font_import_url', '' );
	$background_image = apply_filters( 'dc_login_background_image', '' );
	?>
	<style type="text/css">
		<?php if ( $font_import_url ) : ?>
		@import url('<?php echo esc_url( $font_import_url ); ?>');
		<?php endif; ?>

		.login form {
			background: none !important;
			box-shadow: none !important;
		}

		.login h1 a {
			background-image: none !important;
			background-size: contain !important;
			text-indent: 0 !important;
			height: auto !important;
			width: auto !important;
			font-family: <?php echo $font_family; ?> !important;
			font-size: 2em !important;
		}

		.login form p.submit {
			float: none !important;
			clear: both !important;
			text-align: center !important;
		}

		.login form p.submit #wp-submit {
			padding: 0.75em 2em !important;
			height: auto !important;
			width: auto !important;
			text-align: center !important;
			float: none !important;
			margin: 15px auto !important;
			border: none !important;
			border-radius: 2px !important;
			box-shadow: none !important;
			text-shadow: none !important;
			background: rgba(238, 238, 238, 0.1) !important;
			transition: background-color .2s ease-in !important;
			transition: color .05s ease-in !important;
			border: 1px solid #72777c !important;
			color: #72777c !important;
		}

		.login form p.submit #wp-submit:hover {
			background: rgba(114, 119, 124, 0.9) !important;
			color: #fefefe !important;
		}

		@media (min-width: 981px) {
			<?php if ( $background_image ) : ?>
			body {
				background: url('<?php echo esc_url( $background_image ); ?>') !important;
				background-size: cover !important;
			}
			<?php endif; ?>

			#login {
				width: 400px !important;
				padding: 5% !important;
				height: 100% !important;
				margin-left: 0 !important;
				margin-right: auto !important;
				background: #eee !important;
			}

			.login form {
				width: 350px !important;
				margin: 20px auto 0 !important;
			}

			.login h1 a {
				font-size: 2em !important;
			}
		}

		@media (max-width: 1024px) {
			#login {
				margin: auto !important;
			}

			.login form input, .login form input[type="text"] {
				padding: 3% !important;
			}
		}

		@media (max-width: 680px) {
			#login {
				width: 350px !important;
				height: 100vh !important;
			}
		}
	</style>
	<?php
}

function dc_set_loginheadertitle() {
	return get_bloginfo( 'name', 'display' );
}
