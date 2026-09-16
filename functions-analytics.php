<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Analytics Codes
 *
 * Handle injection of analytics codes (Google Analytics)
 *
 * @version  0.5
 * @package  Child theme
 * @subpackage  Includes
 * @author   Digitally Cultured
 */

/**
 * Inject Google Analytics
 *
 * @return void
 */
function dc_inject_ga() {

    // enter the Google Analytics ID for your property here
    $ga_id = apply_filters( 'dc_ga_id', '' );

    $html = '
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=' . $ga_id . '"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag(\'js\', new Date());
        gtag(\'config\', \'' . $ga_id . '\');
    </script>
    ';

    echo $html;
}
add_action( 'wp_head', 'dc_inject_ga', 99 );
