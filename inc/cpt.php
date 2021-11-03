<?php

/*
 * Events post type.
 */
function register_custom_post_type() {

    // New Post Type
    register_post_type( 'events',
        array(
            'labels'            => array(
                'name'          => esc_html__( 'Events', 'demaxin_test' ),
                'singular_name' => esc_html__( 'Event', 'demaxin_test' )
            ),
            'menu_icon'         => get_template_directory_uri() . '/assets/images/events.png',
            'public'            => true,
            'capability_type'   => 'post',
            'menu_position'     => 8,
            'hierarchical'      => false,
            'has_archive'       => true,
            'rewrite'           => array( 'slug' => 'events-item' ),
            'supports'          => array( 'title', 'editor', 'excerpt', 'thumbnail' ),
        )
    );

   // New Taxonomy
   register_taxonomy(
       'events-type',
       'events',
       array(
           'label' => esc_html__( 'Type', 'demaxin_test' ),
           'labels' => array(
               'name'              => _x( 'Yoga Types', 'taxonomy general name', 'demaxin_test' ),
               'singular_name'     => _x( 'Type', 'taxonomy singular name', 'demaxin_test' ),
               'search_items'      => __( 'Search Types', 'demaxin_test' ),
               'all_items'         => __( 'All Types', 'demaxin_test' ),
               'parent_item'       => __( 'Parent Type', 'demaxin_test' ),
               'parent_item_colon' => __( 'Parent Type:', 'demaxin_test' ),
               'edit_item'         => __( 'Edit Type', 'demaxin_test' ),
               'update_item'       => __( 'Update Type', 'demaxin_test' ),
               'add_new_item'      => __( 'Add New Type', 'demaxin_test' ),
               'new_item_name'     => __( 'New Type Name', 'demaxin_test' ),
               'menu_name'         => __( 'Type', 'demaxin_test' ),
           ),
           'rewrite' => false,
           'add_new_item' => __( 'Add News' ),
           'hierarchical' => true,
       )
   );

   register_taxonomy(
       'events-difficulty',
       'events',
       array(
           'label' => esc_html__( 'Difficulty', 'demaxin_test' ),
           'labels' => array(
               'name'              => _x( 'Yoga Difficulties', 'taxonomy general name', 'demaxin_test' ),
               'singular_name'     => _x( 'Difficulty', 'taxonomy singular name', 'demaxin_test' ),
               'search_items'      => __( 'Search Difficulties', 'demaxin_test' ),
               'all_items'         => __( 'All Difficulties', 'demaxin_test' ),
               'parent_item'       => __( 'Parent Difficulty', 'demaxin_test' ),
               'parent_item_colon' => __( 'Parent Difficulty:', 'demaxin_test' ),
               'edit_item'         => __( 'Edit Difficulty', 'demaxin_test' ),
               'update_item'       => __( 'Update Difficulty', 'demaxin_test' ),
               'add_new_item'      => __( 'Add New Difficulty', 'demaxin_test' ),
               'new_item_name'     => __( 'New Difficulty Name', 'demaxin_test' ),
               'menu_name'         => __( 'Difficulty', 'demaxin_test' ),
           ),
           'rewrite' => false,
           'add_new_item' => __( 'Add News' ),
           'hierarchical' => true,
       )
   );

   register_taxonomy(
       'events-instructor',
       'events',
       array(
           'label' => esc_html__( 'Instructor', 'demaxin_test' ),
           'labels' => array(
               'name'              => _x( 'Yoga Instructors', 'taxonomy general name', 'demaxin_test' ),
               'singular_name'     => _x( 'Instructor', 'taxonomy singular name', 'demaxin_test' ),
               'search_items'      => __( 'Search Instructors', 'demaxin_test' ),
               'all_items'         => __( 'All Instructors', 'demaxin_test' ),
               'parent_item'       => __( 'Parent Instructor', 'demaxin_test' ),
               'parent_item_colon' => __( 'Parent Instructor:', 'demaxin_test' ),
               'edit_item'         => __( 'Edit Instructor', 'demaxin_test' ),
               'update_item'       => __( 'Update Instructor', 'demaxin_test' ),
               'add_new_item'      => __( 'Add New Instructor', 'demaxin_test' ),
               'new_item_name'     => __( 'New Instructor Name', 'demaxin_test' ),
               'menu_name'         => __( 'Instructor', 'demaxin_test' ),
           ),
           'rewrite' => false,
           'add_new_item' => __( 'Add News' ),
           'hierarchical' => true,
       )
   );
}

add_action(	'init', 'register_custom_post_type', 0 );