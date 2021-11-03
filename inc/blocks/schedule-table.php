<?php

register_block_type(
    'demaxin/schedule-table',
    array(
        'render_callback' => 'render_schedule_table_block'
    )
);

function create_current_date( $class_wrapper, $current_year = null, $current_month = null ) {

    if ( null == ( $current_year ) ) {
        $current_year =  date( 'Y', time() );
    }

    if ( null == ( $current_month ) ) {
        $current_month = date( 'F', time() );
    }

    return '<div class="' . esc_attr( $class_wrapper . '__header--current-date' ) . '">' .
                sprintf(
                    '<h2 class="' . esc_attr( $class_wrapper . '__header--current-date__month' ) . '">%s: <b>%s</b></h2>',
                    esc_html__( 'Month', 'demaxin_test' ),
                    $current_month . ' ' . $current_year
                ) .
           '</div>';
}

function create_nav( $class_wrapper, $current_year, $current_month, $current_week ) {

    $next_week  = $current_week  == 52 ? 1 : intval( $current_week ) + 1;
    $next_year  = $current_week  == 52 ? intval( $current_year )     + 1 : $current_year;

    $prev_week  = $current_week  == 1 ? 52 : intval( $current_week ) - 1;
    $prev_year  = $current_week  == 1 ? intval( $current_year )      - 1 : $current_year;

    $prev_week_padded = sprintf( '%02d', $prev_week );
    $next_week_padded = sprintf( '%02d', $next_week );

    return '<div class="' . esc_attr( $class_wrapper . '__header--nav' ) . '">' .
                '<a class="' . esc_attr( $class_wrapper . '__header--nav-prev' ) . '" href="?calendar_week=' . $prev_week_padded . '&calendar_year=' . $prev_year . '">' . html_entity_decode( '&#x3c;', 0, 'UTF-8' ) . '</a>' .
                '<a class="' . esc_attr( $class_wrapper . '__header--nav-next' ) . '" href="?calendar_week=' . $next_week_padded . '&calendar_year=' . $next_year . '">' . html_entity_decode( '&#x3e;', 0, 'UTF-8' ) . '</a>' .
           '</div>';
}

function create_download_schedule_button( $class_wrapper, $current_year, $current_month, $current_week ) {

    return '<a href="generatepdf.php?action=download&current_year=' . $current_year . '&current_month=' . $current_month . '" target="_blank" class="' . esc_attr( $class_wrapper . '__header--schedule-btn' ) . '">' .
                '<svg aria-hidden="true" focusable="false" data-prefix="fas" width="10" height="10" style="margin-right: 5px;" data-icon="download" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-download fa-w-16 fa-2x"><path fill="currentColor" d="M216 0h80c13.3 0 24 10.7 24 24v168h87.7c17.8 0 26.7 21.5 14.1 34.1L269.7 378.3c-7.5 7.5-19.8 7.5-27.3 0L90.1 226.1c-12.6-12.6-3.7-34.1 14.1-34.1H192V24c0-13.3 10.7-24 24-24zm296 376v112c0 13.3-10.7 24-24 24H24c-13.3 0-24-10.7-24-24V376c0-13.3 10.7-24 24-24h146.7l49 49c20.1 20.1 52.5 20.1 72.6 0l49-49H488c13.3 0 24 10.7 24 24zm-124 88c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20zm64 0c0-11-9-20-20-20s-20 9-20 20 9 20 20 20 20-9 20-20z" class=""></path></svg>' .
                __( 'Download Schedule', 'demaxin_test' ) .
           '</a>';
}

function render_schedule_table_block( $attributes, $content ) {

    // Date labels.
    $days  = Array( '', esc_html__( 'MONDAY', 'demaxin_test' ), esc_html__( 'TUESDAY', 'demaxin_test' ), esc_html__( 'WEDNESDEY', 'demaxin_test' ), esc_html__( 'THURSTDAY', 'demaxin_test' ), esc_html__( 'FRIDAY', 'demaxin_test' ), esc_html__( 'SATURDAY', 'demaxin_test' ), esc_html__( 'SUNDAY' ) );
    $class_wrapper      = 'demaxin-block-schedule-table';
    $current_year       = null;
    $current_month      = null;
    $current_week       = null;
    $current_day        = 0;
    $customers_data_arr = null;

    $year  = null;
    $month = null;
    $week  = null;

    if ( null == $year && isset( $_GET[ 'calendar_year' ] ) ) {
        $year = $_GET[ 'calendar_year' ];
    } else if ( null == $year ) {
        $year = date( 'Y', time() );
    }

    if ( null == $week && isset( $_GET[ 'calendar_week' ] ) ) {
        $week = $_GET[ 'calendar_week' ];
    } else if ( null == $week ) {
        $week = date( 'W', time() );
    }

    //     update_option( 'create_events_table', false );

    $current_day   = date( 'd', time() );
    $current_year  = $year;
    $current_week  = $week;
    $current_month = date( 'F', strtotime( $current_year . 'W' . $current_week ) );
    $get_month = date( 'm', strtotime( $current_year . 'W' . $current_week ) );

    $get_first_date_of_week = date( 'Y-m-d', strtotime( $current_year . 'W' . $current_week . 1 ) );
    $get_last_date_of_week  = date( 'Y-m-d', strtotime( $current_year . 'W' . $current_week . 7 ) );

    $events = array();

    if ( get_option( 'create_events_table' ) ) {
        $customers_data_arr = get_table_data_customer_of_between( $get_first_date_of_week, $get_last_date_of_week );
        foreach ( $customers_data_arr as $key => $customer_data ) {
            $events[ $customer_data[ 'customer_start_date' ] ][] = $customer_data;
        }
    }

    $out = '<div class="' . esc_attr( $class_wrapper ) . '">';
        $out .= '<div class="' . esc_attr( $class_wrapper . '__header' ) . '">';
            $out .= create_current_date( $class_wrapper, $current_year, $current_month );
            $out .= create_nav( $class_wrapper, $current_year, $current_month, $current_week );
            if ( current_user_can( 'manage_options' ) ) {
                $out .= create_download_schedule_button( $class_wrapper, $current_year, $get_month, $current_week );
            }
        $out .= '</div>';
        $out .= '<table class="' . esc_attr( $class_wrapper . '__events' ) . '">';
            $out .= '<thead>';
                $out .= '<tr>';
                    // Create days in a week
                    for ( $j = 0; $j <= 7; $j++ ) {
                        $get_year           = date( 'Y', strtotime( $current_year . 'W' . $current_week . $j ) );
                        $get_month          = date( 'm', strtotime( $current_year . 'W' . $current_week . $j ) );
                        $get_day            = date( 'd', strtotime( $current_year . 'W' . $current_week . $j ) );
                        $get_now_date       = date( 'Y-m-d', time() );
                        $get_current_date   = date( 'Y-m-d', strtotime( $get_year . '-' . $get_month . '-' . $get_day ) );
                        $current_day_active = $get_now_date === $get_current_date ? ' ' . esc_attr( 'current-day' ) : null;

                        $out .= '<th class="' . esc_attr( $class_wrapper . '__events-heading' ) . $current_day_active . '">';
                            $out .= '<div class="grid">';
                                $out .= '<span>' . $days[ $j ] . '</span>';
                                $out .= '<span>' . $get_day . '</span>';
                            $out .= '</div>';
                        $out .= '</th>';
                    }
                $out .= '</tr>';
            $out .= '</thead>';
            $out .= '<tbody>';

                $time = '7.00';

                // Create hours in a day.
                for ( $i = 0; $i <= 7; $i++ ) {
                    $next = strtotime( '+60mins', strtotime( $time ) );
                    $time = date( 'H.i', $next ); // format the next time
                    $get_time_format = date( 'H:i:s', $next ); // format the next time

                    $out .= '<tr>';
                        $out .= '<td class="' . esc_attr( $class_wrapper . '__events-hours' ) . '">';
                            $out .= $time;
                        $out .= '</td>';
                        // Count booking.
                        for ( $c = 1; $c <= 7; $c++ ) {
                            $get_year              = date( 'Y', strtotime( $current_year . 'W' . $current_week . $c ) );
                            $get_month             = date( 'm', strtotime( $current_year . 'W' . $current_week . $c ) );
                            $get_day               = date( 'd', strtotime( $current_year . 'W' . $current_week . $c ) );
                            $current_col_date      = date( 'Y-m-d', strtotime( "$get_year-$get_month-$get_day $get_time_format" ) );
                            $current_col_date_time = date( 'Y-m-d H:i:s', strtotime( "$get_year-$get_month-$get_day $get_time_format" ) );

                            if ( isset( $events[ $current_col_date ] ) ) {
                                foreach( $events[ $current_col_date ] as $key => $event ) {
                                    $customer_get_date       = $event[ 'customer_start_date' ];
                                    $customer_get_start_time = $event[ 'customer_start_time' ];
                                    $customer_get_end_time   = $event[ 'customer_end_time' ];
                                    $customer_col_s_date     = date( 'Y-m-d H:i:s', strtotime( "$customer_get_date $customer_get_end_time" ) );
                                    $customer_col_f_date     = date( 'Y-m-d H:i:s', strtotime( "$customer_get_date $customer_get_start_time" ) );

                                    if ( ( $current_col_date_time >= $customer_col_f_date ) && ( $current_col_date_time <= $customer_col_s_date ) ) {
                                        if ( $current_col_date_time === $customer_col_f_date ) {
                                            $event_type       = wp_get_post_terms( $event[ 'event_id' ], 'events-type' );
                                            $event_instructor = wp_get_post_terms( $event[ 'event_id' ], 'events-instructor' );

                                            $out .= '<td rowspan="' . $event[ 'customer_row_numb' ]  . '" class="' . esc_attr( $class_wrapper . '__events-book' ) . ' ' . esc_attr( 'activities' ) .'">';
                                                $out .= '<div class="' . esc_attr( $class_wrapper . '__events-book-info' ) . '">';
                                                    $out .= '<b>' . strtok( get_the_title( $event[ 'event_id' ] ), ' ' ) . '</b>';
                                                    $out .= '<br>';
                                                    if ( ! empty( $event_type ) && ! is_wp_error( $event_type ) ) {
                                                        $out .= $event_type[ 0 ]->name;
                                                    }
                                                $out .= '</div>';
                                                if ( ! empty( $event_instructor ) && ! is_wp_error( $event_instructor ) ) {
                                                    $out .= '<div class="' . esc_attr( $class_wrapper . '__events-book-fullname' ) . '"><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="user-alt" width="11" height="11" style="margin-right: 4px;" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-user-alt fa-w-16 fa-2x"><path fill="currentColor" d="M256 288c79.5 0 144-64.5 144-144S335.5 0 256 0 112 64.5 112 144s64.5 144 144 144zm128 32h-55.1c-22.2 10.2-46.9 16-72.9 16s-50.6-5.8-72.9-16H128C57.3 320 0 377.3 0 448v16c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48v-16c0-70.7-57.3-128-128-128z" class=""></path></svg>' . $event_instructor[ 0 ]->name . '</div>';
                                                }
                                            $out .= '</td>';
                                        }
                                    } else {
                                        $out .= '<td class="' . esc_attr( $class_wrapper . '__events-book' ) . ' ' . esc_attr( 'no-activities' ) .'">';
                                            $out .= __( 'No Activities', 'demaxin_test' );
                                        $out .= '</td>';
                                    }
                                }
                            } else {
                                $out .= '<td class="' . esc_attr( $class_wrapper . '__events-book' ) . ' ' . esc_attr( 'no-activities' ) .'">';
                                    $out .= __( 'No Activities', 'demaxin_test' );
                                $out .= '</td>';
                            }
                        }
                    $out .= '</tr>';
                }
            $out .= '</tbody>';
        $out .= '</table>';
    $out .= '</div>';

    return $out;
}