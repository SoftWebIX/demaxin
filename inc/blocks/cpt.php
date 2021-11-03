<?php

register_block_type(
    'demaxin/cpt',
    array(
        'attributes' => array(
            'postsToShow' => array(
                'type'    => 'number',
                'default' => 5
            ),
            'pagination'  => array(
                'type'    => 'boolean',
                'default' => false
            ),
        ),
        'render_callback' => 'render_cpt_block'
    )
);

function render_cpt_block( $attributes, $content ) {

    $orderby_options = apply_filters(
        'catalog_orderby',
        array(
            'menu_order' => __( 'Default sorting', 'demaxin_test' ),
            'date'       => __( 'Sort by latest', 'demaxin_test' )
        )
    );

    $orderby = isset( $_GET[ 'orderby' ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'orderby' ] ) ) : 'menu_order';

    if ( ! array_key_exists( $orderby, $orderby_options ) ) {
        $orderby = current( array_keys( $orderby_options ) );
    }

    $classes_options = apply_filters(
        'catalog_class',
        array(
            '6' => __( 'Showing 6 class', 'demaxin_test' ),
            '3' => __( 'Showing 3 class', 'demaxin_test' ),
        )
    );

    $classes = isset( $_GET[ 'classes' ] ) ? sanitize_text_field( wp_unslash( $_GET[ 'classes' ] ) ) : '6';

    if ( ! array_key_exists( $classes, $classes_options ) ) {
        $classes = current( array_keys( $classes_options ) );
    }

    $query_args = array(
        'post_type'         => 'events',
        'posts_per_page'    => $classes,
        'post_status'       => 'publish',
        'order'             => 'DESC',
        'orderby'           => $orderby,
    );

    $paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
    if ( isset( $attributes[ 'pagination' ] ) && $attributes[ 'pagination' ] ) {
        $query_args[ 'paged' ] = $paged;
    }

    $q = new \WP_Query( $query_args );

    $class_wrapper = 'demaxin-block-cpt';

    ob_start();

    ?>
        <div class="<?php echo esc_attr( $class_wrapper ); ?>">
            <div>
                <?php
                    if ( $q->have_posts() ) {

                        $per_pages     = $query_args[ 'posts_per_page' ];
                        $total_pages   = $q->post_count;
                        $current_pages = $q->max_num_pages;

                        ?>
                            <div class="<?php echo esc_attr( $class_wrapper . '__header' ) ?>">
                                <form class="<?php echo esc_attr( $class_wrapper . '__header--sorting' ) ?>" method="get">
                                    <select style="background-image: url(<?php echo get_template_directory_uri() . '/assets/images/angle-down.svg' ?>);" name="orderby" class="<?php echo esc_attr( $class_wrapper . '__header--sorting-orderby' ) ?>">
                                        <?php foreach( $orderby_options as $key => $name ) : ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $orderby, $key ); ?>><?php echo esc_html( $name ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <select style="background-image: url(<?php echo get_template_directory_uri() . '/assets/images/angle-down.svg' ?>);" name="classes" class="<?php echo esc_attr( $class_wrapper . '__header--sorting-classes' ) ?>">
                                        <?php foreach( $classes_options as $key => $name ) : ?>
                                            <option value="<?php echo esc_attr( $key ); ?>" <?php selected( $classes, $key ); ?>><?php echo esc_html( $name ); ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                                <p class="<?php echo esc_attr( $class_wrapper . '__header--results-count' ) ?>">
                                    <?php
                                        if ( 1 === $total_pages ) {
                                            echo esc_html__( 'Showing the single result', 'demaxin_test' );
                                        } elseif ( $total_pages <= $per_pages || -1 === $per_pages ) {
                                            //translators: %d: is a total results
                                            echo sprintf( _n( 'Showing all %d results', 'Showing all %d results', $total_pages, 'demaxin_test' ), $total_pages );
                                        } else {
                                            //translators: 1: is a current results 2: is a total results
                                            echo sprintf( _n( 'Showing 1 through %1$d out of %2$d articles...', 'Showing 1 through %1$d out of %2$d events...', $current_pages, 'demaxin_test' ), $current_pages, $total_pages );
                                        }
                                    ?>
                                </p>
                            </div>
                        <?php

                        ob_start();

                        ?> <div class="<?php echo esc_attr( $class_wrapper . '__grid' ); ?>"> <?php
                            while ( $q->have_posts() ) :
                                    $q->the_post();

                                    $post_id                   = get_the_ID();
                                    $meta_box_start_hour_event = get_post_meta( $post_id, '_post_start_hour', true );
                                    $meta_box_end_hour_event   = get_post_meta( $post_id, '_post_end_hour', true );
                                    $meta_box_date_event       = get_post_meta( $post_id, '_post_date_event', true );
                                    $meta_box_location_event   = get_post_meta( $post_id, '_post_location_event', true );
                                    $event_difficulty          = wp_get_post_terms( $post_id, 'events-difficulty' );

                                    $start_hour_time_format = date( get_option( 'time_format' ), strtotime( $meta_box_start_hour_event ) );
                                    $end_hour_time_format   = date( get_option( 'time_format' ), strtotime( $meta_box_end_hour_event ) );

                                    if ( get_option( 'create_events_table' ) ) {
                                        $customers_counter = table_max_length_customers( $post_id );
                                    } else {
                                        $customers_counter = 10;
                                    }
                                ?>
                                    <div class="<?php echo esc_attr( $class_wrapper . '__grid--post' ); ?>" data-limit="<?php echo esc_attr( $customers_counter ); ?>">
                                        <div class="<?php echo esc_attr( $class_wrapper . '__post' ); ?>">
                                            <?php if ( has_post_thumbnail() ) { ?>
                                                <div class="<?php echo esc_attr( $class_wrapper . '__post--image' ); ?>">
                                                    <a href="<?php echo the_permalink(); ?>">
                                                        <?php the_post_thumbnail( array( 373, 400 ) ); ?>
                                                    </a>
                                                </div>
                                            <?php } ?>
                                            <div class="<?php echo esc_attr( $class_wrapper . '__post--content' ) ?>">
                                                <?php the_title( '<h2 class="' . esc_attr( $class_wrapper . '__post--content-title' ) .
                                                    '"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
                                                <div class="<?php echo esc_attr( $class_wrapper . '__post--content-excerpt' )
                                                ?>">
                                                    <?php
                                                        $excerpt = get_the_excerpt();
                                                        $excerpt = substr( $excerpt , 0, 129 );

                                                        echo $excerpt . '...';
                                                    ?>
                                                </div>
                                                <ul>
                                                    <li><svg width="12" height="12" style="margin-right: 8px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="clock" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-clock fa-w-16 fa-2x"><path fill="currentColor" d="M256,8C119,8,8,119,8,256S119,504,256,504,504,393,504,256,393,8,256,8Zm92.49,313h0l-20,25a16,16,0,0,1-22.49,2.5h0l-67-49.72a40,40,0,0,1-15-31.23V112a16,16,0,0,1,16-16h32a16,16,0,0,1,16,16V256l58,42.5A16,16,0,0,1,348.49,321Z" class=""></path></svg><?php echo $start_hour_time_format . ' - ' .
                                                            $end_hour_time_format; ?></li>
                                                    <li><svg aria-hidden="true" width="12" height="12" style="margin-right: 8px;" focusable="false" data-prefix="fas" data-icon="calendar-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="svg-inline--fa fa-calendar-alt fa-w-14 fa-2x"><path fill="currentColor" d="M0 464c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V192H0v272zm320-196c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM192 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12h-40c-6.6 0-12-5.4-12-12v-40zM64 268c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zm0 128c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM400 64h-48V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H160V16c0-8.8-7.2-16-16-16h-32c-8.8 0-16 7.2-16 16v48H48C21.5 64 0 85.5 0 112v48h448v-48c0-26.5-21.5-48-48-48z" class=""></path></svg><?php echo $meta_box_date_event; ?></li>
                                                    <li><svg aria-hidden="true" style="margin-right: 8px;" focusable="false" data-prefix="fas" width="12" height="12" data-icon="map-marker-alt" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="svg-inline--fa fa-map-marker-alt fa-w-12 fa-2x"><path fill="white" d="M172.268 501.67C26.97 291.031 0 269.413 0 192 0 85.961 85.961 0 192 0s192 85.961 192 192c0 77.413-26.97 99.031-172.268 309.67-9.535 13.774-29.93 13.773-39.464 0zM192 272c44.183 0 80-35.817 80-80s-35.817-80-80-80-80 35.817-80 80 35.817 80 80 80z" class=""></path></svg><?php echo $meta_box_location_event; ?></li>
                                                </ul>
                                                <?php if ( ! empty( $event_difficulty ) && ! is_wp_error( $event_difficulty ) ) { ?>
                                                    <div class="<?php echo esc_attr( $class_wrapper . '__post--content-type' ) ?>">
                                                        <svg aria-hidden="true" width="12" height="12" style="margin-right: 4px;" focusable="false" data-prefix="fas" data-icon="star" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-star fa-w-18 fa-2x"><path fill="currentColor" d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" class=""></path></svg>
                                                        <?php
                                                            echo $event_difficulty[ 0 ]->name;
                                                        ?>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <button class="<?php echo esc_attr( $class_wrapper . '__post--add-event' ); ?>">
                                                <img width="15" height="15" src="<?php echo get_template_directory_uri() .
                                                    '/assets/images/plus.png'; ?>" alt="add-plus" />
                                            </button>
                                        </div>
                                        <div class="<?php echo esc_attr( $class_wrapper . '__popup' ); ?>">
                                            <form class="<?php echo esc_attr( $class_wrapper . '__popup--booking-form'
                                            ); ?>" method="POST">
                                                <button id="close-popup" type="button" style="position: absolute; border: none; top: 5px; right: 7px; padding: 0; background-color: transparent; cursor: pointer;"><img width="12" height="12" src="<?php echo get_template_directory_uri() . '/assets/images/close.svg'; ?>" alt="close-popup-window" /></button>
                                                <h3 style="color: black;"><?php echo esc_html__( 'Booking Form', 'demaxin_test' );
                                                ?></h3>
                                                <input type="hidden" name="_event_id" value="<?php echo $post_id ?>" />
                                                <table>
                                                    <tr>
                                                        <td colspan="4"><input id="customer_fullname" type="text"
                                                                   name="_customer_fullname" placeholder="<?php _e( 'Full Name*',
                                                                'demaxin_test' ); ?>" value="" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="4"><input id="customer_email" type="email"
                                                                   name="_customer_email" placeholder="<?php _e( 'Email*',
                                                                'demaxin_test' ); ?>" value="" /></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            <input id="customer_start_date" class="date start" type="text"
                                                                   name="_customer_start_date"
                                                                   autocomplete="off"
                                                                   placeholder="<?php _e( 'Date start*',
                                                                       'demaxin_test' ); ?>"
                                                                   value="" />
                                                        </td>
                                                        <td>
                                                            <input id="customer_start_time" class="time start" type="text"
                                                                   name="_customer_start_time"
                                                                   placeholder="<?php _e( 'Start time*',
                                                                       'demaxin_test' ); ?>"
                                                                   value="" />
                                                        </td>
                                                        <td style="padding: 0;">
                                                            <span><?php _e( 'to', 'demaxin_test' ); ?></span>
                                                        </td>
                                                        <td>
                                                            <input id="customer_end_time" class="time end" type="text"
                                                                   name="_customer_end_time"
                                                                   placeholder="<?php _e( 'End time*',
                                                                       'demaxin_test' ); ?>" value="" />
                                                        </td>
                                                    </tr>
                                                </table>
                                                <input class="<?php echo esc_attr( $class_wrapper . '__popup--booking-form__submit' ); ?>" value="<?php _e( 'Submit', 'demaxin_test' ); ?>" type="submit" />
                                                <div class="<?php echo esc_attr( $class_wrapper . '__popup--booking-form__success' ); ?>">
                                                    <div>
                                                        <h2><?php echo esc_html( 'Thank you!', 'demaxin_test' ); ?></h2>
                                                        <p><?php echo esc_html( 'Your submission has been sent.', 'demaxin_test' ); ?></p>
                                                    </div>
                                                </div>
                                                <?php if ( $customers_counter >= 10 ) { ?>
                                                    <div class="<?php echo esc_attr( $class_wrapper . '__popup--booking-form__limited' ); ?>">
                                                        <div>
                                                            <h2><?php echo esc_html( 'Sorry, your booking was unsuccessful', 'demaxin_test' ); ?></h2>
                                                            <p><?php echo esc_html( 'Maximum bookings exceeded...', 'demaxin_test' ); ?></p>
                                                            <button id="select-other-event"><?php echo esc_html( 'Book another lessons', 'demaxin_test' ); ?></button>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            </form>
                                        </div>
                                    </div>
                                <?php
                            endwhile;
                        ?> </div> <?php

                        wp_reset_postdata();
                        ob_end_flush();
                    } else {

                        echo '<p>' . esc_html__( 'Nothing found.', 'demaxin_test' ) . '</p>';
                    }
                ?>
            </div>

            <?php if ( isset( $attributes[ 'pagination' ] ) && $attributes[ 'pagination' ] ) { ?>
                <nav role="navigation" class="navigation pagination">
                    <?php
                        $total_pages = $q->max_num_pages;

                        $format  = $GLOBALS[ 'wp_rewrite' ]->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
                        $format .= $GLOBALS[ 'wp_rewrite' ]->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

                        add_filter( 'number_format_i18n', function( $format ) {

                            $number = intval( $format );

                            if ( intval ( $number / 10 ) > 0 ) {
                                return $format;
                            }

                            return '0' . $format;
                        } );

                        $pagination_args = array(
                            'base'         => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'total'        => $total_pages,
                            'current'      => max( 1, get_query_var( 'paged' ) ),
                            'format'       => $format,
                            'show_all'     => false,
                            'type'         => 'plain',
                            'end_size'     => 3,
                            'mid_size'     => 1,
                            'prev_next'    => true,
                            'prev_text'    => sprintf( '<i></i> %1$s', _x( 'PREV', 'Previous post', 'demaxin_test' ) ),
                            'next_text'    => sprintf( '%1$s <i></i>', _x( 'NEXT', 'Next post', 'demaxin_test' ) ),
                            'add_args'     => false,
                            'add_fragment' => ''
                        );

                        echo paginate_links( $pagination_args );
                    ?>
                </nav>
            <?php } ?>
        </div>
    <?php

    $result = ob_get_clean();
    return $result;
}