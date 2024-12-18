document.addEventListener( 'DOMContentLoaded', function() {
	const aiModel           = document.getElementById('aimodel_id');
	const agentAdvanced     = document.querySelector('.agent-advanced');
	// const agentName         = document.querySelector('.agent_name');
	// const agentDesc         = document.querySelector('.agent_desc');
	// const agentPersona      = document.querySelector('.agent_persona');
	const agentNameInput    = document.getElementById('agent_name');
	const agentDescInput    = document.getElementById('agent_desc');
	const agentPersonaInput = document.getElementById('agent_persona');
	const colorFields       = document.querySelectorAll('.dappier-color-picker');
	let   fieldsHidden      = true;

	// If no agent value, that means some agents exist but none have been chosen. Hide fields.
	if ( ! aiModel.value ) {
		hideFields();
		fieldsHidden = true;
	}

	// Hide/show the create agent fields.
	document.getElementById('aimodel_id').addEventListener('change', function() {
		// If there is a value.
		if ( aiModel.value ) {
			// If the fields are hidden, show them.
			if ( fieldsHidden ) {
				fieldsHidden = false;
				showFields();
			}

			// If creating a new agent, clear the fields.
			if ( '_create_agent' === aiModel.value ) {
				agentNameInput.value    = '';
				agentDescInput.value    = '';
				agentPersonaInput.value = '';
			}
			// Selecting an existing, get the agent data.
			else {
				// Temp disable fields.
				agentNameInput.disabled    = true;
				agentDescInput.disabled    = true;
				agentPersonaInput.disabled = true;

				// Run ajax to get the agent data.
				jQuery.ajax({
					url: dappierSettings.ajaxUrl,
					type: 'POST',
					data: {
						action: 'dappier_get_agent_data',
						api_key: document.getElementById('api_key').value,
						aimodel_id: aiModel.value,
					},
					success: function( response ) {
						// If successful, populate the fields.
						if ( response.success ) {
							agentNameInput.value    = response.data.name;
							agentDescInput.value    = response.data.description;
							agentPersonaInput.value = response.data.persona;
						}

						// Re-enable fields.
						agentNameInput.disabled    = false;
						agentDescInput.disabled    = false;
						agentPersonaInput.disabled = false;
					},
					error: function( response ) {
						console.log( response );
					}
				});
			}
		}
		// No value, make sure fields are hidden.
		else {
			hideFields();
			fieldsHidden = true;
		}
	});

	// If we have color fields.
	if ( colorFields.length ) {
		// Initialize them.
		colorFields.forEach(field => {
			jQuery(field).wpColorPicker();
		});
	}

	// Function to show fields.
	function showFields() {
		agentAdvanced.setAttribute( 'open', '' );
	}

	// Function to hide fields.
	function hideFields() {
		agentAdvanced.removeAttribute( 'open' );
	}
});

jQuery( function($) {
	// On upload button click.
	$( 'body' ).on( 'click', '.dappier-media__upload', function( event ){
		event.preventDefault(); // prevent default link click and page refresh

		const button         = $(this);
		const imageId        = button.next().next().val();
		const customUploader = wp.media({
			title: dappierSettings.uploaderTitle,
			library : {
				type : 'image',
			},
			button: {
				text: dappierSettings.buttonText,
			},
			multiple: false,
		}).on( 'select', function() { // it also has "open" and "close" events
			const attachment = customUploader.state().get( 'selection' ).first().toJSON();

			button.removeClass( 'button' ).html( '<img src="' + attachment.url + '">' );
			button.next().show(); // show "Remove image" link.
			button.next().next().val( attachment.id ); // Populate the hidden field with image ID.
		})

		// Handle already selected images.
		customUploader.on( 'open', function() {
			if ( ! imageId ) {
				return;
			}

			const selection = customUploader.state().get( 'selection' )
			attachment = wp.media.attachment( imageId );
			attachment.fetch();
			selection.add( attachment ? [attachment] : [] );
		});

		// Open the uploader.
		customUploader.open();
	});

	// on remove button click
	$( 'body' ).on( 'click', '.dappier-media__remove', function( event ){
		event.preventDefault();
		const button = $(this);
		button.next().val( '' ); // emptying the hidden field
		button.hide().prev().addClass( 'button' ).html( 'Upload image' ); // replace the image with text
	});
});