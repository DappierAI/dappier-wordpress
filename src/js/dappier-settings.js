document.addEventListener( 'DOMContentLoaded', function() {
	const aiModel           = document.getElementById('aimodel_id');
	const agentName         = document.querySelector('.agent_name');
	const agentDesc         = document.querySelector('.agent_desc');
	const agentPersona      = document.querySelector('.agent_persona');
	const agentNameInput    = document.getElementById('agent_name');
	const agentDescInput    = document.getElementById('agent_desc');
	const agentPersonaInput = document.getElementById('agent_persona');
	const colorFields       = document.querySelectorAll('.dappier-color-picker');
	let   fieldsHidden      = false;

	// If no agent value, that means some agents exist but none have been chosen. Hide fields.
	if ( ! aiModel.value ) {
		toggleFields( 'none' );
		fieldsHidden = true;
	}

	// Hide/show the create agent fields.
	document.getElementById('aimodel_id').addEventListener('change', function() {
		// If there is a value and the fields are hidden, show them.
		if ( aiModel.value && fieldsHidden ) {
			toggleFields( 'block' );
			fieldsHidden = false;
		}
	});

	// If we have color fields.
	if ( colorFields.length ) {
		// Initialize them.
		colorFields.forEach(field => {
			jQuery(field).wpColorPicker();
		});
	}

	// Function to toggle display.
	function toggleFields( display ) {
		agentName.style.display    = display;
		agentDesc.style.display    = display;
		agentPersona.style.display = display;
	}
});