document.addEventListener( 'DOMContentLoaded', function() {
	const colorFields = document.querySelectorAll('.dappier-color-picker');

	// If we have color fields.
	if ( colorFields.length ) {
		// Initialize them.
		colorFields.forEach(field => {
			jQuery(field).wpColorPicker();
		});
	}
});