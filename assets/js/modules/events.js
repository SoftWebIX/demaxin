;
( function ( $, window, document ) {
	
	'use strict';
	
	const eventsMetaBoxSettings = {
		
		onInit: () => {

			eventsMetaBoxSettings.initTimePicker();
			eventsMetaBoxSettings.initDatePicker();
			eventsMetaBoxSettings.initLocationPicker();
		},
		initTimePicker: () => {
			
			const TIME_FORMAT = Boolean( parseInt( $( '#time_format' ).val() ) );
			
			$( '#event_start' ).timepicker( {
				showPeriod: TIME_FORMAT,
				showPeriodLabels: TIME_FORMAT,
				defaultTime: '00:00'
			} );
			
			$( '#event_end' ).timepicker( {
				showPeriod: TIME_FORMAT,
				showPeriodLabels: TIME_FORMAT,
				defaultTime: '00:00'
			} );
		},
		initDatePicker: () => {
			
			$( '#event_date' ).datepicker( {
				dateFormat: 'dd MM yy'
			} );
		},
		initLocationPicker: () => {
			
			$( '#google-map' ).locationpicker( {
				zoom: 6,
				location: {
					latitude: $( '#event_latitude' ).val(),
					longitude: $( '#event_longitude' ).val()
				},
				inputBinding: {
					latitudeInput: $( '#event_latitude' ),
					longitudeInput: $( '#event_longitude' ),
					locationNameInput: $( '#event_location' )
				},
				onchanged: ( currentLocation, radius, isMarkerDropped ) => {
					let lastLat  = currentLocation.latitude;
					let lastLng  = currentLocation.longitude;
					let geocoder = new google.maps.Geocoder;
					let latlng   = { lat: lastLat, lng: lastLng };
					
					$( '#event_latitude' ).val( lastLat );
					$( '#event_longitude' ).val( lastLng );
					
					geocoder.geocode( { 'location': latlng }, ( results, status ) => {
						if ( status === 'OK' ) {
							if ( results[ 0 ] ) {
								$( '#event_location' ).val( results[ 0 ].formatted_address );
							}
						}
					} );
				}
			} );
			
			$( document )
				.on( 'click', '#open_map', e => {
					e.preventDefault();
					$( '#google-map-container' ).show();
				} )
				.on( 'click', '#close_map', e => {
					e.preventDefault();
					$( '#google-map-container' ).hide();
				} );
		}
	}
	
	$( window ).on( 'load', () => {
		
		eventsMetaBoxSettings.onInit();
	} );
	
} )( jQuery, window, document );