document.addEventListener( 'DOMContentLoaded', function() {
	const aiModel           = document.getElementById('aimodel_id');
	const agentName         = document.querySelector('.agent_name');
	const agentDesc         = document.querySelector('.agent_desc');
	const agentPersona      = document.querySelector('.agent_persona');
	const agentNameInput    = document.getElementById('agent_name');
	const agentDescInput    = document.getElementById('agent_desc');
	const agentPersonaInput = document.getElementById('agent_persona');
	const colorFields       = document.querySelectorAll('.dappier-color-picker');

	// If no agents exist, the default is to create. Show fields and set as required.
	if ( '_create_agent' === aiModel.value ) {
		toggleFields( 'block', true );
	}

	// Hide/show the create agent fields.
	document.getElementById('aimodel_id').addEventListener('change', function() {
		// If creating.
		if ( '_create_agent' === this.value ) {
			toggleFields( 'block', true );
		}
		// Not creating.
		else {
			toggleFields( 'none', false );
		}
	});

	// If we have color fields.
	if ( colorFields.length ) {
		// Initialize them.
		colorFields.forEach(field => {
			jQuery(field).wpColorPicker();
		});
	}

	// Function to toggle display and required.
	function toggleFields( display, required ) {
		agentName.style.display    = display;
		agentDesc.style.display    = display;
		agentPersona.style.display = display;
		agentNameInput.required    = required;
		agentDescInput.required    = required;
		agentPersonaInput.required = required;
	}
});