<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Functions for DC branding across the site
 * 
 * Currently includes: 
 *  - Admin color scheme
 *  - Login screen branding
 *  - Developer credits
 * 
 * TODO: 
 */


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


/**
 * Login Screen Branding
 *
 * Custom wp-login.php layout. The layout itself (box sizing, responsive
 * breakpoints, button styling) is shared across sites; font and
 * background image are per-site, set via filters:
 *
 *   add_filter( 'dc_login_font_family', fn() => "'Megrim', cursive" );
 *   add_filter( 'dc_login_font_import_url', fn() => 'https://fonts.googleapis.com/css?family=Megrim' );
 *   add_filter( 'dc_login_background_image', fn() => get_stylesheet_directory_uri() . '/assets/images/login-screen.jpg' );
 *
 * @package     Child theme
 * @subpackage  Includes
 * @author      Digitally Cultured
 */

$login_screen = apply_filters( 'dc_login_screen', 'v1' ); // v1 or v2

switch ( $login_screen ) {
	case 'v1':
		add_action( 'login_enqueue_scripts', 'dc_replace_login_logo' );
		break;
	case 'v2':
		add_action( 'login_enqueue_scripts', 'dc_fullscreen_login' );
		add_filter( 'login_headertitle', 'dc_set_loginheadertitle' );
		break;
}

/**
 * Inject custom CSS for a fullscreen login 
 *
 * @return void
 */
function dc_fullscreen_login() {
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

/**
 * Replace the WordPress login logo with a custom logo
 * 
 * @version 1.0
 * @since 1.0
 * 
 * @return void
 */
function dc_replace_login_logo() { ?>
	<style type="text/css">
		.login h1 a {
			background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/assets/images/login.png) !important;
			background-size: contain !important;
			height: 125px !important;
			width: auto !important;
		}
	</style>
	<?php 
}

/**
 * Gets the footer credits for the site
 * 
 * If an associate is passed, it will add that associate to the credits
 *
 * @param string $associate (optional) The associate to add to the credits. Options: socialco, adrian
 *
 * @return string HTML The footer credits
 */
function dc_get_footer_credits( $associate='' ) {
    $return = "This site is powered by <a href=\"https://digitallycultured.com/\">Digitally Cultured</a>";
    switch ( $associate ) {
        case 'socialco':
            $return .= " and was produced by <a href=\"http://socialcoadvertising.com/\">Socialco</a>.";
            break;
        case 'adrian':
            $return .= ' and is <a href="http://www.adriannaccari.com/">by Adrian</a>.';
            break;
        default: 
            $return .= '.';
    }

    return $return;
}