(function( $ ) {
	var timeoutDoUpdate = null;
	var scriptDataNode = document.getElementById( 'toolset_common_es_data' );
	var errorContainer = $( '.tces-settings-rwd-error' );

	if ( scriptDataNode ) {
		var ScriptData = JSON.parse( WPV_Toolset.Utils.editor_decode64( scriptDataNode.textContent ) );
	}
	wp.apiFetch.use( wp.apiFetch.createNonceMiddleware( ScriptData.wp_rest_nonce ) );

	$( document ).trigger( 'js-toolset-event-update-setting-section-triggered' );

	$( document ).on( 'change input', '.js-wpv-rwd-device', ( e ) => {
		clearTimeout( timeoutDoUpdate );
		timeoutDoUpdate = setTimeout( () => doUpdate( $( e.target ) ), 800 );
	} );

	function doUpdate( input ) {
		var devices = {};
		$( 'input.js-wpv-rwd-device' ).each( function() {
			devices[ $( this ).data( 'device-key' ) ] = { maxWidth: parseInt( $( this ).val(), 10 ) };
		} );

		wp.apiFetch( {
			path: ScriptData[ 'Route/Responsive' ],
			method: 'POST',
			data: {
				action: 'update',
				devices,
			},
		} ).then(
			( result ) => {
				if( result.error ) {
					errorContainer.find('.notice').html( result.error );
					errorContainer.show();
					return;
				}
				errorContainer.hide();
				$( document ).trigger( 'js-toolset-event-update-setting-section-completed' );
			}
		).catch(
			( error ) => {
				$( document ).trigger( 'js-toolset-event-update-setting-section-failed' );
				console.log( error );
			}
		);
	}
} )( jQuery );

