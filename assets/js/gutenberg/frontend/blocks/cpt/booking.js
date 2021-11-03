;
( function ( $, window, document ) {
	
	'use strict';
	
	const bookingEventForm = {
		
		onInit: () => {
			
			bookingEventForm.initHeight();
			bookingEventForm.popUp();
			bookingEventForm.initEventDate();
			bookingEventForm.submitBookingForm();
		},
		initHeight: () => {

			$( '.demaxin-block-cpt__grid--post' ).each(function( index ) {
				const heightContent = $( this ).find( '.demaxin-block-cpt__post--content' ).css( 'height' );
				$( this ).find( '.demaxin-block-cpt__post--content-type' ).css( 'width', `${ heightContent }` );
			} );
		},
		popUp: () => {
			
			$( '.demaxin-block-cpt__post--add-event' ).on( 'click', e => {
				
				e.preventDefault();
				$( 'html, body' ).css( 'overflow', 'hidden' );
				$( e.currentTarget ).parent().parent().find( '.demaxin-block-cpt__popup' ).addClass( 'is-visible' );
			} );
			
			$( '.demaxin-block-cpt__grid--post' ).on( 'click', '#close-popup', e => {

				e.preventDefault();
				$( 'html, body' ).css( 'overflow', 'visible' );
				$( e.currentTarget ).parent().parent().removeClass( 'is-visible' );
			} );

			$( '.demaxin-block-cpt__grid--post' ).on( 'click', '#select-other-event', e => {

				e.preventDefault();
				$( 'html, body' ).css( 'overflow', 'visible' );
				$( e.currentTarget ).parent().parent().parent().parent().removeClass( 'is-visible' );
				console.log( $( e.currentTarget ).parent().parent().parent().parent() );
			} );
		},
		initEventDate: () => {

			$( '.demaxin-block-cpt__popup--booking-form .time' ).timepicker( {
				showDuration: true,
				interval: 60,
				step: 60,
				disableTextInput: true,
				timeFormat: 'g:ia',
				minTime: '8:00am',
				maxTime: '3:00pm',
			} );

			$( '.demaxin-block-cpt__popup--booking-form .date' ).datepicker( {
				format: 'yyyy-mm-dd',
				disableTextInput: true,
				startDate: '-0m'
			} );
			
			$( '.demaxin-block-cpt__popup--booking-form' ).datepair();
		},
		isEmail: email => {
			const regex = "/^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/";
			return regex.test(email);
		},
		submitValidBookingForm: () => {

			$( '.demaxin-block-cpt__popup--booking-form input' ).on( 'input', e => {

				if ( ! $( e.currentTarget ).val() ) {
					$( e.currentTarget ).addClass( 'error' );
				} else {
					$( e.currentTarget ).removeClass( 'error' );
				}
			} );
		},
		submitBookingForm: () => {
			
			$( '.demaxin-block-cpt__popup--booking-form' ).on( 'submit', e => {

				const that         = $( e.currentTarget ),
				      fullnameVal  = that.find( '#customer_fullname' ).val(),
				      emailVal     = that.find( '#customer_email' ).val(),
				      startDateVal = that.find( '#customer_start_date' ).val(),
				      startTimeVal = that.find( '#customer_start_time' ).val(),
				      endTimeVal   = that.find( '#customer_end_time' ).val();

				bookingEventForm.submitValidBookingForm();

				if ( fullnameVal === '' ) {
					that.find( '#customer_fullname' ).addClass( 'error' );
				}

				if ( emailVal === '' ) {
					that.find( '#customer_email' ).addClass( 'error' );
				}

				if ( startDateVal === '' ) {
					that.find( '#customer_start_date' ).addClass( 'error' );
				}

				if ( startTimeVal === '' ) {
					that.find( '#customer_start_time' ).addClass( 'error' );
				}

				if ( endTimeVal === '' ) {
					that.find( '#customer_end_time' ).addClass( 'error' );
				}

				if ( fullnameVal !== '' && emailVal !== '' && startDateVal !== '' && startTimeVal !== '' && endTimeVal !== '' ) {
					$.post( {
						url: demaxin.ajax_url,
						data: {
							action: 'booking_form',
							nonce: demaxin.nonce,
							data: that.serialize()
						},
						beforeSend: () => {
							that.find( '.demaxin-block-cpt__popup--booking-form__submit' ).text( demaxin.sendingText );
						},
						success: data => {
							that.find( '.demaxin-block-cpt__popup--booking-form__success' ).addClass( 'showing' );

							setTimeout( () => {
								that.parent().removeClass( 'is-visible' );
								that.find( '.demaxin-block-cpt__popup--booking-form__success' ).removeClass( 'showing' );
								that[ 0 ].reset();
								$( 'html, body' ).css( 'overflow', 'visible' );
							}, 2500 );
						}
					} );
				}

				e.preventDefault();
			} );
		}
	}
	
	$( window ).on( 'load', () => {
		
		bookingEventForm.onInit();
	} );
} )( jQuery, window, document );