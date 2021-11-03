;
( function ( $, window, document ) {

    'use strict';

    const filteringPost = {

        onInit: () => {

            filteringPost.initForm();
        },
        initForm: () => {
            // Orderby
            $( '.demaxin-block-cpt__header--sorting' ).on( 'change', 'select', e => {
                $( e.currentTarget ).closest( 'form' ).trigger( 'submit' );
            } );
        }
    }

    $( window ).on( 'load', () => {

        filteringPost.onInit();
    } );
} )( jQuery, window, document );