( function() {
	'use strict';

	function syncColor( picker, hex ) {
		if ( ! picker || ! hex ) {
			return;
		}

		picker.addEventListener( 'input', function() {
			hex.value = picker.value;
		} );

		hex.addEventListener( 'input', function() {
			var value = ( hex.value || '' ).replace( /^#/, '' );

			if ( /^[0-9A-Fa-f]{6}$/.test( value ) ) {
				picker.value = '#' + value;
			}
		} );
	}

	document.addEventListener( 'DOMContentLoaded', function() {
		document.querySelectorAll( '.zrcb-color-picker' ).forEach( function( picker ) {
			var hexId = picker.getAttribute( 'data-hex-target' );
			var hex = hexId ? document.getElementById( hexId ) : null;

			syncColor( picker, hex );
		} );
	} );
}() );
