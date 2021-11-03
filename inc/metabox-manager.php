<?php

function added_meta_box() {

    add_meta_box( 'demaxin_event_settings', __( 'Event settings', 'demaxin_test' ), 'render_event_settings', 'events', 'normal', 'low', array( 'post_type' => 'events' ) );
}

add_action( 'add_meta_boxes', 'added_meta_box' );

function render_event_settings( $post ) {

    $time_format = get_option( 'time_format' );

    if ( $time_format === 'H:i' ) {
        $time_format_array = array( 'hours' => '0,23', 'am_pm' => false );
    } elseif ( $time_format === 'g:i A' ) {
        $time_format_array = array( 'hours' => '1,12', 'am_pm' => true );
    } else {
        $time_format_array = array( 'hours' => '0,23', 'am_pm' => false );
    }

    ?>
        <input type="hidden" id="time_format" value="<?php echo $time_format_array[ 'am_pm' ] === true ? '1' : '0' ?>"/>
        <?php
            $event_latitude  = get_post_meta( $post->ID, '_post_event_latitude', true );
            $event_longitude = get_post_meta( $post->ID, '_post_event_longitude', true );

            $val_event_latitude  = ( ! empty( $event_latitude ) )  ? $event_latitude  : '48.9215';
            $val_event_longitude = ( ! empty( $event_longitude ) ) ? $event_longitude : '24.70972';
        ?>
        <input type="hidden" id="event_latitude" value="<?php echo $val_event_latitude; ?>" name="_event_latitude"/>
        <input type="hidden" id="event_longitude" value="<?php echo $val_event_longitude; ?>" name="_event_longitude"/>
        <table id="add_event_options_table" class="form-table">
            <tr>
                <td><label for="event_start"><?php _e( 'Select Start Time:', 'demaxin_test' ) ?></label></td>
                <td>
                    <input id="event_start" type="text" value="<?php echo get_post_meta( $post->ID, '_post_start_hour', true ); ?>" name="_start_hour" maxlength="5" size="5">
                    <span class="description"><?php _e( 'hh:mm', 'demaxin_test' ) ?></span>
                </td>
            </tr>
            <tr>
                <td><label for="event_end"><?php _e( 'Select End Time:', 'demaxin_test' ) ?></label></td>
                <td>
                    <input id="event_end" type="text" value="<?php echo get_post_meta( $post->ID, '_post_end_hour',
                        true ); ?>" name="_end_hour" maxlength="5" size="5">
                    <span class="description"><?php _e( 'hh:mm', 'demaxin_test' ) ?></span>
                </td>
            </tr>
            <tr>
                <td><label for="event_date"><?php _e( 'Select Date:', 'demaxin_test' ) ?></label></td>
                <td>
                    <input id="event_date" style="width: 140px;" type="text" value="<?php echo get_post_meta( $post->ID,
                        '_post_date_event', true ); ?>" name="_date_event" maxlength="5" size="5">
                    <span class="description"><?php _e( 'dd MM yy', 'demaxin_test' ) ?></span>
                </td>
            </tr>
            <tr>
                <td><label for="event_location"><?php _e( 'Select Location:', 'demaxin_test' ) ?></label></td>
                <td>
                    <div id="google-map-container" style="display: none; position:
                    relative;">
                        <div id="google-map" style="position: absolute; bottom: -100%; width: 300px; height:
                    200px;"></div>
                        <button id="close_map" style="cursor: pointer; position: absolute; top: -30px; left: -37px;">
                            <img src="<?php echo esc_attr( get_template_directory_uri() . '/assets/images/close.svg' );
                            ?>" alt="Close" height="20" width="20" />
                        </button>
                    </div>
                    <input style="pointer-events: none; background: #e3e3e3; color: #8f8f8f;" id="event_location" type="text" value="<?php echo get_post_meta( $post->ID, '_post_location_event', true ); ?>" name="_location_event">
                    <button id="open_map" style="position: relative; top: 5px; left: 1px; cursor: pointer;"><img
                                src="<?php echo esc_attr( get_template_directory_uri() . '/assets/images/map.svg' ); ?>"
                    alt="Google Map" height="20" width="20" /></button>
                </td>
            </tr>
        </table>
    <?php
}

function save_custom_post( $post_id ) {

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {

        return $post_id;
    }

    // Don't save revisions and autosaves
    if ( wp_is_post_revision( $post_id ) || wp_is_post_autosave( $post_id ) ) {

        return $post_id;
    }

    // Check user permission
    if ( ! current_user_can( 'edit_post', $post_id ) ) {

        return $post_id;
    }

    if ( isset( $_POST[ '_event_latitude' ] ) ) {

        update_post_meta( $post_id, '_post_event_latitude', sanitize_text_field( htmlentities( $_POST[ '_event_latitude'
            ] )
        ) );
    }

    if ( isset( $_POST[ '_event_longitude' ] ) ) {

        update_post_meta( $post_id, '_post_event_longitude', sanitize_text_field( htmlentities( $_POST[ '_event_longitude'
            ] )
        ) );
    }

    if ( isset( $_POST[ '_start_hour' ] ) ) {

        update_post_meta( $post_id, '_post_start_hour', sanitize_text_field( htmlentities( $_POST[ '_start_hour' ] )
        ) );
    }

    if ( isset( $_POST[ '_end_hour' ] ) ) {

        update_post_meta( $post_id, '_post_end_hour', sanitize_text_field( htmlentities( $_POST[ '_end_hour' ] ) ) );
    }

    if ( isset( $_POST[ '_date_event' ] ) ) {

        update_post_meta( $post_id, '_post_date_event', sanitize_text_field( htmlentities( $_POST[ '_date_event' ] )
        ) );
    }

    if ( isset( $_POST[ '_location_event' ] ) ) {

        update_post_meta( $post_id, '_post_location_event', sanitize_text_field( htmlentities( $_POST[ '_location_event' ] ) ) );
    }
}

add_action( 'save_post', 'save_custom_post', 40, 2 );