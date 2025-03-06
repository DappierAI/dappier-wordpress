/******/ (() => { // webpackBootstrap
/*!************************************!*\
  !*** ./src/js/dappier-settings.js ***!
  \************************************/
document.addEventListener('DOMContentLoaded', function () {
  const aiModel = document.getElementById('aimodel_id');
  const agentAdvanced = document.querySelector('.agent-advanced');
  const agentNameInput = document.getElementById('agent_name');
  const agentDescInput = document.getElementById('agent_desc');
  const agentPersonaInput = document.getElementById('agent_persona');
  const branding = document.getElementById('askai_branding');
  const logo = document.querySelector('.askai_logo');
  const logoField = document.querySelector('.askai_logo .dappier-media__upload');
  const logoWidth = document.querySelector('.askai_logo_width');
  const icon = document.querySelector('.askai_icon');
  const iconField = document.querySelector('.askai_icon .dappier-media__upload');
  const iconWidth = document.getElementById('askai_icon_width');
  const title = document.querySelector('.askai_title');
  const colorFields = document.querySelectorAll('.dappier-color-picker');
  let agentFieldsHidden = true;

  // If no agent value, that means some agents exist but none have been chosen. Hide fields.
  if (!aiModel.value) {
    agentAdvanced.removeAttribute('open');
    agentFieldsHidden = true;
  }
  // If we're loading the page with no agents, creating a new agent will be the default. Show fields.
  else if ('_create_agent' === aiModel.value) {
    agentAdvanced.setAttribute('open', '');
    agentFieldsHidden = false;
  }

  // Hide/show the create agent fields.
  aiModel.addEventListener('change', function (e) {
    // If there is a value.
    if (e.target.value) {
      // If the fields are hidden, show them.
      if (agentFieldsHidden) {
        agentAdvanced.setAttribute('open', '');
        agentFieldsHidden = false;
      }

      // If creating a new agent, clear the fields.
      if ('_create_agent' === e.target.value) {
        agentNameInput.value = '';
        agentDescInput.value = '';
        agentPersonaInput.value = '';
      }
      // Selecting an existing, get the agent data.
      else {
        // Temp disable fields.
        agentNameInput.disabled = true;
        agentDescInput.disabled = true;
        agentPersonaInput.disabled = true;

        // Run ajax to get the agent data.
        jQuery.ajax({
          url: dappierSettings.ajaxUrl,
          type: 'POST',
          data: {
            action: 'dappier_get_agent_data',
            api_key: document.getElementById('api_key').value,
            aimodel_id: e.target.value
          },
          success: function (response) {
            // If successful, populate the fields.
            if (response.success) {
              agentNameInput.value = response.data.name;
              agentDescInput.value = response.data.description;
              agentPersonaInput.value = response.data.persona;
            }

            // Re-enable fields.
            agentNameInput.disabled = false;
            agentDescInput.disabled = false;
            agentPersonaInput.disabled = false;
          },
          error: function (response) {
            console.log(response);
          }
        });
      }
    }
    // No value, make sure fields are hidden.
    else {
      agentAdvanced.removeAttribute('open');
      agentFieldsHidden = true;
    }
  });

  // Handle branding on page load.
  handleBranding(branding.value);

  // Handle branding on change.
  branding.addEventListener('change', function (e) {
    handleBranding(e.target.value);
  });

  // Handle logo width on page load.
  handleElWidth(logoField, logoWidth.value);

  // Handle logo width input.
  logoWidth.addEventListener('change', function (e) {
    handleElWidth(logoField, e.target.value);
  });

  // Handle icon width on page load.
  handleElWidth(iconField, iconWidth.value);

  // Handle icon width input.
  iconWidth.addEventListener('change', function (e) {
    handleElWidth(iconField, e.target.value);
  });

  // If we have color fields.
  if (colorFields.length) {
    // Initialize them.
    colorFields.forEach(field => {
      jQuery(field).wpColorPicker();
    });
  }

  // Handle branding fields.
  function handleBranding(value) {
    switch (value) {
      case 'logo':
        logo.style.display = 'block';
        logoWidth.style.display = 'block';
        title.style.display = 'none';
        break;
      case 'title':
        logo.style.display = 'none';
        logoWidth.style.display = 'none';
        title.style.display = 'block';
        break;
      default:
        logo.style.display = 'none';
        logoWidth.style.display = 'none';
        title.style.display = 'none';
        break;
    }
  }

  // Handle element width.
  function handleElWidth(element, value) {
    if (!value) {
      return;
    }
    element.style.maxWidth = value + 'px';
  }

  // Handle submit button loading state.
  document.querySelectorAll('.dappier-form input[type="submit"]').forEach(button => {
    button.addEventListener('click', function () {
      this.value = dappierSettings.loadingText;
    });
  });
});
jQuery(function ($) {
  // On upload button click.
  $('body').on('click', '.dappier-media__upload', function (event) {
    event.preventDefault(); // prevent default link click and page refresh

    const button = $(this);
    const imageId = button.next().next().val();
    const customUploader = wp.media({
      title: dappierSettings.uploaderTitle,
      library: {
        type: 'image'
      },
      button: {
        text: dappierSettings.buttonText
      },
      multiple: false
    }).on('select', function () {
      // it also has "open" and "close" events
      const attachment = customUploader.state().get('selection').first().toJSON();
      button.removeClass('button').html('<img src="' + attachment.url + '">');
      button.next().show(); // show "Remove image" link.
      button.next().next().val(attachment.id); // Populate the hidden field with image ID.
    });

    // Handle already selected images.
    customUploader.on('open', function () {
      if (!imageId) {
        return;
      }
      const selection = customUploader.state().get('selection');
      attachment = wp.media.attachment(imageId);
      attachment.fetch();
      selection.add(attachment ? [attachment] : []);
    });

    // Open the uploader.
    customUploader.open();
  });

  // on remove button click
  $('body').on('click', '.dappier-media__remove', function (event) {
    event.preventDefault();
    const button = $(this);
    button.next().val(''); // emptying the hidden field
    button.hide().prev().addClass('button').html('Upload image'); // replace the image with text
  });
});
/******/ })()
;
//# sourceMappingURL=dappier-settings.js.map