( function( api ) {

	// Extends our custom "wp-simple" section.
	api.sectionConstructor['wp-simple'] = api.Section.extend( {

		// No events for this type of section.
		attachEvents: function () {},

		// Always make the section active.
		isContextuallyActive: function () {
			return true;
		}
	} );

} )( wp.customize );
