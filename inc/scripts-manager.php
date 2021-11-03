<?php

function wp_ajax__booking_form() {

    if ( ! check_ajax_referer( 'booking_form', 'nonce' ) ) {
        return;
    }

    $getPostedData = isset( $_POST[ 'data' ] ) ? $_POST[ 'data' ] : '';

    $data = array();
    parse_str( $getPostedData,$data );
    $getData = is_array( $data ) ? $data : [];

    $sanitizedData = [];

    foreach( $getData as $key => $value ) {

        $sanitizedData[ $key ] = sanitize_text_field( $value );
    }

    table_data_create( $sanitizedData );
}

add_action( 'wp_ajax_nopriv_booking_form', 'wp_ajax__booking_form' );
add_action( 'wp_ajax_booking_form', 'wp_ajax__booking_form' );

function enqueue_editor_assets() {

    global $pagenow;

    $dependencies = array( 'wp-i18n', 'wp-element', 'wp-blocks', 'wp-components' );

    if ( $pagenow !== 'widgets.php' ) {
        array_push( $dependencies, 'wp-editor' );
    } else {
        array_push( $dependencies, 'wp-edit-widgets' );
    }

    wp_enqueue_script( 'demaxin-editor-blocks', get_template_directory_uri() . '/dist/js/editor-blocks.min.js', $dependencies, _S_VERSION );
    wp_enqueue_style( 'demaxin-editor-blocks', get_template_directory_uri() . '/dist/css/editor-blocks.min.css', array(), _S_VERSION );
}

add_action( 'enqueue_block_editor_assets', 'enqueue_editor_assets' );

function enqueue_front_assets() {
    
    if ( ! is_admin() ) {

        wp_enqueue_script( 'demaxin-frontend-blocks', get_template_directory_uri() . '/dist/js/frontend-blocks.min.js',
            array( 'wp-element', 'jquery' ), _S_VERSION, true );
        wp_localize_script(
            'demaxin-frontend-blocks',
            'demaxin',
            array(
                'ajax_url'        => admin_url( 'admin-ajax.php' ),
                'nonce'           => wp_create_nonce( 'booking_form' ),
                'sendingText'     => __( 'Sending...', 'demaxin_test' ),
            )
        );

        wp_enqueue_script( 'jquery-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js', array(), '1.0.10' );
        wp_enqueue_script( 'jquery-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js', array(), '1.0.10' );
        wp_enqueue_script( 'jquery-datepair', 'https://jonthornton.github.io/Datepair.js/dist/jquery.datepair.js', array(), '0.4.16' );
        wp_enqueue_script( 'datepair', 'https://jonthornton.github.io/Datepair.js/dist/datepair.js', array(), '0.4.16' );

        wp_enqueue_style( 'jquery-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css', array(), '1.0.10' );
        wp_enqueue_style( 'jquery-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css', array(), '1.0.10' );
        wp_enqueue_style( 'demaxin-editor-blocks', get_template_directory_uri() . '/dist/css/editor-blocks.min.css', array(), _S_VERSION );
    }
}

add_action( 'enqueue_block_assets', 'enqueue_front_assets' );

function enqueue_admin_assets() {

    global $current_screen;

    wp_register_script( 'demaxin-event-settings', get_template_directory_uri() . '/dist/js/events.min.js', array( 'jquery' ), _S_VERSION );
    wp_register_script( 'demaxin-google-map-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAZeqpXKGNzSxvksJtJYsaAPZ6V2iCQ7R0', null, '3.40', true );

    if ( ! empty( $current_screen ) ) {
        switch ( $current_screen->id ) {
            case 'events':
                wp_enqueue_script( 'demaxin-event-settings' );
                wp_enqueue_script( 'demaxin-google-map-api' );
                wp_enqueue_script( 'jquery-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js', array(), '1.0.10' );
                wp_enqueue_script( 'jquery-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js', array(), '1.0.10' );

                wp_enqueue_script( 'jquery-locationpicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-locationpicker/0.1.12/locationpicker.jquery.min.js', array(), '0.1.12' );
                wp_enqueue_style( 'jquery-timepicker', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css', array(), '1.0.10' );
                wp_enqueue_style( 'jquery-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.css', array(), '1.0.10' );

                break;
        }
    }
}

add_action( 'admin_enqueue_scripts', 'enqueue_admin_assets' );