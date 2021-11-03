<?php

require_once DIR_PATH . '/vendor/autoload.php';

use Dompdf\Dompdf;

if ( isset( $_GET[ 'action' ] ) && $_GET[ 'action' ] === 'download' ) {

    if ( isset( $_GET[ 'current_year' ] ) ) {
        $year = $_GET[ 'current_year' ];
    }

    if ( isset( $_GET[ 'current_month' ] ) ) {
        $month = $_GET[ 'current_month' ];
    }

    $first_day_of_month = date( 'd', strtotime( $year . '-' . $month . '-01' ) );
    $last_day_of_month  = date( 't', strtotime( $year . '-' . $month . '-' . $first_day_of_month ) );
    $current_start_date = date( 'Y-m-d', strtotime( $year . '-' . $month . '-' . $first_day_of_month ) );
    $current_end_date   = date( 'Y-m-d', strtotime( $year . '-' . $month . '-' . $last_day_of_month ) );
    $current_month      = date( 'F', strtotime( $year . '-' . $month ) );

    $dompdf = new Dompdf();

    $schedule = '<html>';
        $schedule .= '<head>';
            $schedule .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>';
            $schedule .= '<style type="text/css">';
                $schedule .= '
                    body { font-family: DejaVu Sans, sans-serif; }
                    .table {
                        margin: 30px 0 15px 0;
                        border: none;
                        width: 100%;
                        table-layout: fixed;
                    }
                    .table thead th {
                        font-weight: bold;
                       	text-align: left;
                       	border: none;
                       	padding: 10px 15px;
                       	background: #d8d8d8;
                       	font-size: 14px;
                    }
                    .table thead th:first-child {
                    	border-radius: 8px 0 0 8px;
                    }
                    .table thead tr th:last-child {
                    	border-radius: 0 8px 8px 0;
                    }
                    .table tbody td {
                        text-align: left;
                        border: none;
                        padding: 10px 15px;
                        font-size: 14px;
                        vertical-align: middle;
                    }
                    .table tbody tr:nth-child(even) {
                    	background: #f3f3f3;
                    }
                    .table tbody tr td:first-child {
                    	border-radius: 8px 0 0 8px;
                    }
                    .table tbody tr td:last-child {
                    	border-radius: 0 8px 8px 0;
                    }
                    h1 {
                        margin: 10px 0 10px 0;
                        padding: 10px 0 10px 0;
                        font-weight: bold;
                        font-size: 26px;
                        text-align: center;
                    }
                ';
            $schedule .= '</style>';
        $schedule .= '</head>';
        $schedule .= '<body>';
            $schedule .= '<h1>';
                $schedule .= sprintf( esc_html__( 'Yoga Schedule the %1$s and %2$s %3$s %4$s.', 'demaxin_test' ), $first_day_of_month, $last_day_of_month, $current_month, $year );
                esc_html__( 'Schedule for All Month', 'demaxin_test' );
            $schedule .= '</h1>';
            $schedule .= '<table class="table">';
                $schedule .= '<thead>';
                    $schedule .= '<tr>';
                        $schedule .= '<th>';
                            $schedule .= esc_html__( 'Date', 'demaxin_test' );
                        $schedule .= '</th>';
                        $schedule .= '<th>';
                            $schedule .= esc_html__( 'Start Time', 'demaxin_test' );
                        $schedule .= '</th>';
                        $schedule .= '<th>';
                            $schedule .= esc_html__( 'End Time', 'demaxin_test' );
                        $schedule .= '</th>';
                        $schedule .= '<th>';
                            $schedule .= esc_html__( 'Customer Name', 'demaxin_test' );
                        $schedule .= '</th>';
                        $schedule .= '<th>';
                            $schedule .= esc_html__( 'Location', 'demaxin_test' );
                        $schedule .= '</th>';
                        $schedule .= '<th>';
                            $schedule .= esc_html__( 'Instructor', 'demaxin_test' );
                        $schedule .= '</th>';
                    $schedule .= '</tr>';
                $schedule .= '</thead>';
                $schedule .= '<tbody>';
                    $customers_data_arr = get_table_data_customer_of_between( $current_start_date, $current_end_date );
                    for ( $i = 0; $i < count( $customers_data_arr ); $i++ ) {
                        $post_id          = $customers_data_arr[ $i ][ 'event_id' ];
                        $event_location   = get_post_meta( $post_id, '_post_location_event', true );
                        $event_type       = wp_get_post_terms( $post_id, 'events-type' );
                        $event_instructor = wp_get_post_terms( $post_id, 'events-instructor' );
                        $schedule .= '<tr>';
                            $schedule .= '<td>';
                                $schedule .= $customers_data_arr[ $i ][ 'customer_start_date' ];
                            $schedule .= '</td>';
                            $schedule .= '<td>';
                                $schedule .= $customers_data_arr[ $i ][ 'customer_start_time' ];
                            $schedule .= '</td>';
                            $schedule .= '<td>';
                                $schedule .= $customers_data_arr[ $i ][ 'customer_end_time' ];
                            $schedule .= '</td>';
                            $schedule .= '<td>';
                                $schedule .= $customers_data_arr[ $i ][ 'customer_fullname' ];
                            $schedule .= '</td>';
                            $schedule .= '<td>';
                                $schedule .= $event_location;
                            $schedule .= '</td>';
                            $schedule .= '<td>';
                                if ( ! empty( $event_instructor ) && ! is_wp_error( $event_instructor ) ) {
                                    $schedule .= $event_instructor[ 0 ]->name;
                                } else {
                                    $schedule .= '-';
                                }
                            $schedule .= '</td>';

                        $schedule .= '</tr>';
                    }
                $schedule .= '</tbody>';
            $schedule .= '</table>';
        $schedule .= '</body>';
    $schedule .= '</html>';

    $dompdf->loadHtml( $schedule, 'UTF-8' );
    // (Optional) Setup the paper size and orientation
    $dompdf->setPaper( 'A4', 'portrait' );
    // Render the HTML as PDF
    $dompdf->render();

    // Output the generated PDF to Browser
    $dompdf->stream( 'table-schedule', array( 'Attachment' => 1 ) );
}