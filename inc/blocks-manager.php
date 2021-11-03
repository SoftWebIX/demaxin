<?php

function block_categories_all( $block_categories, $editor_context ) {

    //Add Custom blocks category
    $block_categories = array_merge(
        $block_categories,
        array(
            array(
                'slug' => 'demaxin-blocks',
                'title' => __( 'Demaxin Blocks', 'demaxin_test' ),
            ),
        )
    );

    return $block_categories;
}

function block_categories( $categories, $post ) {

    //Add Getwid blocks category
    $categories = array_merge(
        $categories,
        array(
            array(
                'slug' => 'demaxin-blocks',
                'title' => __( 'Demaxin Blocks', 'demaxin_test' ),
            ),
        )
    );

    return $categories;
}

if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {

    add_filter( 'block_categories_all', 'block_categories_all', 10, 2 );
} else {

    add_filter( 'block_categories', 'block_categories', 10, 2 );
}

function include_blocks() {

    $block_files = array(
        'cpt',
        'schedule-table'
    );

    foreach( $block_files as $block_file ) {

        $path = get_stylesheet_directory() . '/inc/blocks/' . $block_file . '.php';

        if ( file_exists( $path ) ) {
            require_once( $path );
        }
    }
}

add_action( 'init', 'include_blocks' );
