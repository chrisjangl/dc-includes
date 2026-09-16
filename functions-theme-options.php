<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Theme options
 *
 * Add/removes/edits settings in the Theme Options; specific to Responsive parent theme
 ** 
 ** TODO: Move away from responsive
 *
 * @version     1.0
 * @package     Child theme
 * @subpackage  Includes
 * @author      Digitally Cultured
 */

 /**** Actions/hooks/filters ****/

    // add_filter( 'responsive_option_sections_filter', 'dc_rename_theme_options' );
    // add_filter( 'responsive_options_filter', 'dc_add_theme_options', 99, 1 );

/**** end Actions/hooks/filters ****/

/**** Function definitions ****/
    function dc_rename_theme_options( $sections ) {
        foreach ( $sections as $key => $section ) {
            if ( $section['id'] == 'social' ) {
                unset( $sections[$key]);
                // $section['title'] = "NAPS (Name, Address, Phone, Social)";
                            
            } 
        }

        array_unshift( $sections, array(
            'title' => __( 'NAPS (Name, Address, Phone, Social)', 'responsive' ),
            'id'    => 'naps'
        ) );

        // return apply_filters( 'responsive_option_sections_filter', $sections );
        return $sections;
    }

    function dc_add_theme_options( $options ) {

        //build the options for our NAPS section
        $options = dc_add_NAPS_theme_options( $options );

        //build options for alternate logo
        $options = dc_add_logo_theme_options( $options );

        // return apply_filters( 'responsive_options_filter', $options );
        return $options;
    }

    function dc_add_NAPS_theme_options( $options ) {

        $options['naps'] = $options['social'];

        array_unshift( $options['naps'], array(
                'title'       => 'Phone',
                'subtitle'    => '',
                'heading'     => '',
                'type'        => 'text',
                'id'          => 'phone_uid',
                'description' => __( 'Enter the main phone number', 'responsive' ),
                'placeholder' => ''
        ) );

        array_unshift( $options['naps'], array(
            'title'	=>	'Country',
            'type'	=>	'text',
            'id'	=>	'address_country',
            'placeholder'	=>	'Country'
        ) );

        array_unshift( $options['naps'], array(
            'title'	=>	'Zip',
            'type'	=>	'text',
            'id'	=>	'address_zip',
            'placeholder'	=>	'Zip'
        ) );

        array_unshift( $options['naps'], array(
            'title'	=>	'State',
            'type'	=>	'text',
            'id'	=>	'address_state',
            'placeholder'	=>	'State'
        ) );

        array_unshift( $options['naps'], array(
            'title'	=>	'City',
            'type'	=>	'text',
            'id'	=>	'address_city',
            'placeholder'	=>	'City'
        ) );
        
        array_unshift( $options['naps'], array(
            'title'	=>	'Address 2',
            'type'	=>	'text',
            'id'	=>	'address_2',
            'placeholder'	=>	'Address 2'
        ) );

        array_unshift( $options['naps'], array(
            'title'	=>	'Street',
            'type'	=>	'text',
            'id'	=>	'address_1',
            'placeholder'	=>	'Street Address'
        ) );

        array_unshift( $options['naps'], array(
            'title'	=>	'Company Name',
            'type'	=>	'text',
            'id'	=>	'company_uid',
            'placeholder'	=>	'Company Name',
            'description'	=>	'Name of the company, to be displayed across the website, mostly with address & contact information'
        ) );

        return $options;

    }

    function dc_add_logo_theme_options( $options ) {
        
        //adding our alternate logo section
        array_push( $options['logo_upload'], 
            array(
                'title'       => __( 'Alternate Logo', 'responsive' ),
                'subtitle'    => '',
                'heading'     => 'heading',
                'type'        => 'description',
                'id'          => '',
                'description' => __( 'This logo will be displayed on colored backgrounds, so it\'s best to use transparent or white logo', 'digitallycultured' ) . sprintf( ' <a href="%s">' . __( 'Click here', 'digitallycultured' ) . '</a>.',
                admin_url( 'themes.php?page=alternate-header' ) ),
                'placeholder' => ''
            )
        );

        return $options;
        
    }
/**** end Function definitions ****/