<?php

$table_name = 'wp_demaxin_event_posts';
add_option( 'create_events_table', false );

function table_data_create( $data ) {

    global $wpdb;
    $table_name      = $wpdb->prefix . 'demaxin_event_posts';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
                id int(11) NOT NULL AUTO_INCREMENT,
                event_id int(11) NOT NULL,
                customer_fullname text NOT NULL,
                customer_email text NOT NULL,
                customer_start_date date NOT NULL,
                customer_start_time time NOT NULL,
                customer_end_time time NOT NULL,
                customer_row_numb int(11) NOT NULL,
                PRIMARY KEY (id),
                UNIQUE KEY id (id)
            ) {$charset_collate};";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta( $sql );
    $success = empty( $wpdb->last_error );

    if ( $success ) {
        update_option( 'create_events_table', true );
        table_data_insert( $data );
    } else {
        wp_die( __( 'Could not create table into the database.', 'demaxin_test' ) );
    }
}

function table_data_insert( $data ) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'demaxin_event_posts';

    $start_date = date( 'Y-m-d', strtotime( $data[ '_customer_start_date' ] ) );
    $start_time = date( 'H:i', strtotime( $data[ '_customer_start_time' ] ) );
    $end_time   = date( 'H:i', strtotime( $data[ '_customer_end_time' ] ) );
    $row_con    = intval( $end_time ) - intval( $start_time ) + 1;

    $data_arr = array(
        'event_id'            => $data[ '_event_id' ],
        'customer_fullname'   => $data[ '_customer_fullname' ],
        'customer_email'      => $data[ '_customer_email' ],
        'customer_start_date' => $start_date,
        'customer_start_time' => $start_time,
        'customer_end_time'   => $end_time,
        'customer_row_numb'   => $row_con
    );

    if ( is_array( $data ) && ! empty( $data ) ) {

        $wpdb->insert(
            $table_name,
            $data_arr
        );
    }
}

function get_table_data_customer_of_between( $first_date, $last_date ) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'demaxin_event_posts';

    $query = $wpdb->get_results(
        $wpdb->prepare(
            "
                SELECT event_id, customer_fullname, customer_email, customer_start_date, customer_start_time, customer_end_time, customer_row_numb
                FROM $table_name
                WHERE customer_start_date BETWEEN %s AND %s
                ORDER BY customer_start_date, customer_start_time
            ",
            $first_date,
            $last_date
        ),
        ARRAY_A
    );

    return $query;
}

function table_max_length_customers( $id ) {

    global $wpdb;
    $table_name = $wpdb->prefix . 'demaxin_event_posts';

    $count = $wpdb->get_var(
        $wpdb->prepare(
            "
                SELECT COUNT(*)
                FROM $table_name
                WHERE event_id LIKE %d
            ",
            $id
        )
    );

    return (int)$count;
}
