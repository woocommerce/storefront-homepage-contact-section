/**
 * Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function( $ ) {
	wp.customize( 'shcs_contact_address', function( value ) {
		value.bind( function( to ) {
			$( '.shcs-address' ).text( to );
		} );
	} );

	wp.customize( 'shcs_contact_phone_number', function( value ) {
		value.bind( function( to ) {
			$( '.shcs-phone-number' ).text( to );
		} );
	} );

	wp.customize( 'shcs_contact_email_address', function( value ) {
		value.bind( function( to ) {
			$( '.shcs-email' ).text( to );
		} );
	} );
} )( jQuery );