<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Developer Functions
 *
 * Supporting functions to help developer tasks/troubleshooting
 * 
 * @version     1.5
 * @package     Child Theme
 * @subpackage  Includes
 * @author      Digitally Cultured
 * 
 */

 /**** Actions, hooks, filters ****/

    //print_list_hooked_functions( 'woocommerce_before_add_to_cart_button' );
	// add_action( 'wp_head', 'dc_print_template', 99 );
	
	// adds widgets to the WP Dashboard
    if ( apply_filters( 'dc_enable_dashboard_widgets', false ) ) {
        add_action( 'wp_dashboard_setup', 'dc_add_dashboard_widgets' );
    }

 /**** end Actions, hooks, filters ****/

 /**** Function definitions ****/

    /**
     * Print a list of all hooked functions
     *
     * 
     * @version 1.1.0
     * @since  1.0.0
     * @param string 	$hook 	(optional) The name of the action to be evaluated.
     *                      	 Leave it blank to print all hooks.
     * @return void
     */
    function dc_print_hooked_functions( $hook = '' ) {
        global $wp_filter;

        if ( empty( $hook ) || !isset( $wp_filter[$hook] ) ) 
            return;

        print '<pre>';
        
        echo "<h5>$hook</h5>";
        foreach( $wp_filter[$hook]->callbacks as $priority=>$callbacks ) {

            echo "<p><b>Priority $priority</b><br />";

            foreach ( $callbacks as $action ) {
                
                    echo "'" . print_r($action['function']) . "'";
                    echo ", accepts " . $action['accepted_args'] . " argument(s)";

                
            }

            echo "</p>";

        }
        
        print '</pre>';
        
    }

    function dc_cache_buster( $file='' ) {

        if ( $file ) {
            return date("i.s", filemtime( $file ) );
        } else {
            return date("i.s", filemtime( get_stylesheet_directory() . '/style.css' ) );
        }

    }

    /**
     * when turned on (check Actions, hooks & filters, above),
     * will print the name of the current page template at the top of the page
     * 
     * @since 1.1
     * 
     */
    function dc_print_template() {

        $template_name = str_replace('.php','',get_post_meta($wp_query->post->ID,'_wp_page_template',true));
        if ( $template_name ) echo $template_name;
        else return false;
    
    }

    /**
     * check if an administrator is logged in
     * 
     * @version 1.3
     * @since 1.2
     * 
     */
    function is_dc_admin() {
        
        // set the email address of the user to check
        $user_email = '';

        return ( $user_email == wp_get_current_user()->user_email );
    }

	
	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action above.
	 */
	function dc_add_dashboard_widgets() {
		
		wp_add_dashboard_widget(
			'dc_site_location',         		// Widget slug.
			'Server Info',         			    // Title.
			'dc_site_location_dashboard_widget' // Display function.
		);	
	}
	

	/**
	 * Create the function to output the contents of our Dashboard Widget.
     * 
     * Shows:
     * - WP version
     * - PHP version
     * - MySQL version
     * - Server IP address
	 */
	function dc_site_location_dashboard_widget() {

        $widget_style = '
        <style>
            #dc_site_location .inside {
                display: flex;
            }
        </style>
        ';

        echo $widget_style;

        $open_div = '<div style="padding: 10px; margin-bottom: 10px;">';
        $close_div = '</div>';
		$open_heading = "<h3 style=\"font-weight: bolder; text-decoration: underline;\">";
		$close_heading = "</h3>";

        $WordPress_version = function_exists('get_bloginfo') ? get_bloginfo( 'version' ) : '<i>WordPress version not available</i>';
		$server_IP = $_SERVER['SERVER_ADDR'];
        $mySQL_version = isset( $GLOBALS['wpdb'] ) ? $GLOBALS['wpdb']->get_var( "SELECT VERSION()" ) : '<i>MySQL version not available</i>';
		
		// WordPress version
        echo $open_div;
		echo $open_heading . "WordPress version" . "$close_heading";
		echo "<p>" . $WordPress_version . "</p>";
        echo $close_div;

		// PHP version
        echo $open_div;
		echo $open_heading . "PHP version" . "$close_heading";
		echo "<p>" . phpversion() . "</p>";
        echo $close_div;
        
        // MySQL version
        echo $open_div;
        echo $open_heading . "MySQL version" . "$close_heading";
        echo "<p>$mySQL_version</p>";
        echo $close_div;

        // server IP
        echo $open_div;
        echo $open_heading . "Server IP address" . "$close_heading";
        echo "<p>$server_IP</p>";
        echo $close_div;

	}

/**** end Functions ****/



